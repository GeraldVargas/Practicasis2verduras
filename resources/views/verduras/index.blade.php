<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Verduras por Voz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eaf6ec;
            margin: 0;
            padding: 40px 20px;
            color: #333;
        }

        .contenedor {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 25px 30px;
        }

        h2 {
            color: #4a7c59;
            margin-top: 0;
        }

        .alerta {
            background: #dff0e3;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #365b41;
        }

        .botones {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .btn-mic, .btn-guardar {
            border: none;
            padding: 9px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            color: #333;
        }

        .btn-mic {
            background: #c9e4de;
        }

        .btn-mic.escuchando {
            background: #f7d9c4;
        }

        .btn-buscar {
            background: #fde2e4;
        }

        .btn-buscar.escuchando {
            background: #f7d9c4;
        }

        .btn-guardar {
            background: #cddafd;
        }

        #textoDictado {
            font-weight: bold;
            color: #4a7c59;
        }

        form {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            width: 200px;
            padding: 8px 10px;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
            font-size: 14px;
            background: #f5faf6;
            color: #4a7c59;
            font-weight: bold;
            cursor: default;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background: #c9e4de;
            color: #333;
        }

        tr:nth-child(even) {
            background: #f5faf6;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Registrar Verdura por Voz</h2>

        @if (session('exito'))
            <div class="alerta">{{ session('exito') }}</div>
        @endif
        @if (session('error'))
            <div class="alerta">{{ session('error') }}</div>
        @endif

        <div class="botones">
            <button type="button" class="btn-mic" id="botonMic" onclick="iniciarDictado()">Dictar verdura</button>
            <button type="button" class="btn-buscar" id="botonBuscar" onclick="buscarPorVoz()">Buscar por voz</button>
        </div>
        <p>Escuchado: <span id="textoDictado">—</span></p>

        <form action="{{ route('verduras.store') }}" method="POST">
            @csrf
            <input type="text" name="nombre_verdura" id="nombre_verdura" placeholder="Verdura escuchada" readonly required>
            <button type="submit" class="btn-guardar">Guardar registro</button>
        </form>

        <h2>Verduras registradas</h2>
        <table>
            <tr>
                <th>Puesto</th>
                <th>NombreVerdura</th>
                <th>ID</th>
                <th>Costo (Bs/libra)</th>
                <th>Respecto al apio</th>
            </tr>
            @each('verduras.fila', $registros, 'registro')
        </table>
    </div>

    <script>
        function iniciarDictado() {
            if (!('webkitSpeechRecognition' in window)) {
                alert('Tu navegador no soporta dictado por voz. Usa Google Chrome.');
                return;
            }

            const boton = document.getElementById('botonMic');
            const reconocimiento = new webkitSpeechRecognition();
            reconocimiento.lang = 'es-ES';
            reconocimiento.start();

            boton.classList.add('escuchando');
            boton.innerText = 'Escuchando...';

            reconocimiento.onresult = function (evento) {
                const texto = evento.results[0][0].transcript;
                document.getElementById('textoDictado').innerText = texto;
                document.getElementById('nombre_verdura').value = texto;
            };

            reconocimiento.onerror = function (evento) {
                alert('Error al escuchar: ' + evento.error + '. Revisa tu conexión a internet.');
            };

            reconocimiento.onend = function () {
                boton.classList.remove('escuchando');
                boton.innerText = 'Dictar verdura';
            };
        }

        function buscarPorVoz() {
            if (!('webkitSpeechRecognition' in window)) {
                alert('Tu navegador no soporta dictado por voz. Usa Google Chrome.');
                return;
            }

            const boton = document.getElementById('botonBuscar');
            const reconocimiento = new webkitSpeechRecognition();
            reconocimiento.lang = 'es-ES';
            reconocimiento.start();

            boton.classList.add('escuchando');
            boton.innerText = 'Escuchando...';

            reconocimiento.onresult = function (evento) {
                const texto = evento.results[0][0].transcript;
                document.getElementById('textoDictado').innerText = texto;
                window.location.href = "{{ route('verduras.buscar') }}?buscar=" + encodeURIComponent(texto);
            };

            reconocimiento.onerror = function (evento) {
                alert('Error al escuchar: ' + evento.error + '. Revisa tu conexión a internet.');
            };

            reconocimiento.onend = function () {
                boton.classList.remove('escuchando');
                boton.innerText = 'Buscar por voz';
            };
        }
    </script>
</body>
</html>