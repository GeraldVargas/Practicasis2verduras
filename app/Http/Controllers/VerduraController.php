<?php

namespace App\Http\Controllers;

use App\Models\Verdura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerduraController extends Controller
{
   private array $precios = [
    'apio'      => 2.00,
    'lechuga'   => 3.00,
    'cebolla'   => 3.50,
    'pepino'    => 4.00,
    'zanahoria' => 4.50,
    'remolacha' => 5.50,
    'espinaca'  => 6.50,
    'tomate'    => 8.00,
    'berenjena' => 10.00,
    'papa'      => 15.00,
    ];

   public function index()
    {
        $precioBase = min($this->precios);

        $registros = DB::table('verduras')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY costo) AS puesto, ROUND(costo / ?, 2) AS multiplicador', [$precioBase])
            ->orderBy('costo')
            ->get();

        return view('verduras.index', [
            'registros' => $registros,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_verdura' => 'required|string|max:50',
        ]);

        $nombre = strtolower(trim($request->nombre_verdura));
        $nombre = preg_replace('/[^\p{L}\s]/u', '', $nombre);
        $nombre = trim($nombre);

        $costo = $this->precios[$nombre] ?? null;

        if (!$costo) {
            return redirect()->route('verduras.index')
                ->with('error', "No reconozco la verdura \"$nombre\".");
        }

        $yaExiste = Verdura::whereRaw('LOWER(nombre_verdura) = ?', [$nombre])->exists();

        if ($yaExiste) {
            return redirect()->route('verduras.index')
                ->with('error', ucfirst($nombre)." ya está registrada, no se puede repetir.");
        }

        $precioBase = min($this->precios);
        $multiplicador = round($costo / $precioBase, 2);

        Verdura::create([
            'nombre_verdura' => ucfirst($nombre),
            'costo' => $costo,
        ]);

        return redirect()->route('verduras.index')
            ->with('exito', "Se registró \"$nombre\" a Bs$costo la libra (x$multiplicador el precio del apio, que es el más barato)");
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'buscar' => 'required|string|max:50',
        ]);

        $nombre = strtolower(trim($request->buscar));
        $nombre = preg_replace('/[^\p{L}\s]/u', '', $nombre);
        $nombre = trim($nombre);

        $encontrado = Verdura::whereRaw('LOWER(nombre_verdura) = ?', [$nombre])->first();

        if (!$encontrado) {
            return redirect()->route('verduras.index')
                ->with('error', "No encontré \"$nombre\" entre los registros. Primero regístrala.");
        }
        $puesto = Verdura::where('costo', '<', $encontrado->costo)->count() + 1;
        $precioBase = min($this->precios);
        $multiplicador = round($encontrado->costo / $precioBase, 2);

        return redirect()->route('verduras.index')
            ->with('exito', ucfirst($nombre)." está en el puesto $puesto - Precio: " . number_format($encontrado->costo, 2) . " Bs (x$multiplicador el precio del apio, que es el más barato)");
    }
}