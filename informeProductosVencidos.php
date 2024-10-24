<?php
require_once('conf.php');
require_once('dompdf/autoload.inc.php');


$filename = "Reporte_productos_vencidos_septiembre_2024_" . date("Ymd_His") . "pdf";
$query  = "SELECT id, producto, existencias, bodegas, vencimiento
            FROM tbl_invesproduct 
            WHERE vencimiento BETWEEN '2024-09-01' AND '2024-10-01' ORDER BY vencimiento";

$result = mysqli_query($conn, $query);

$html = "
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    h2 {
        text-align: center;
    }
</style>
    <h2>Reporte de Productos Vencidos (Septiembre 2024)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Existencias</th>
                    <th>Bodegas</th>
                    <th>Vencimiento</th>
                </tr>
            </thead>
            <tbody>";
while ($row = mysqli_fetch_assoc($result)) {
    $html .= "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['producto'] . "</td>
                    <td>" . $row['existencias'] . "</td>
                    <td>" . $row['bodegas'] . "</td>
                    <td>" . $row['vencimiento'] . "</td>
                </tr>";
}
$html .= "</tbody></table>";

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($filename);
