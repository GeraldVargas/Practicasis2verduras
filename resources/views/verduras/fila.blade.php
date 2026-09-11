<tr>
    <td>{{ $registro->puesto }}</td>
    <td>{{ $registro->nombre_verdura }}</td>
    <td>{{ $registro->id }}</td>
    <td>{{ number_format($registro->costo, 2) }}</td>
    <td>x{{ number_format($registro->multiplicador, 2) }}</td>
</tr>