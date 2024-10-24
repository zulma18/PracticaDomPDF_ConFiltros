<?php
require_once('conf.php');
require_once('dompdf/autoload.inc.php');


$filename = "Reporte_productos_menores_existencias_" . date("Ymd_His") . "pdf";
$query  = "SELECT id, producto, existencias, bodegas FROM `tbl_invesproduct` WHERE existencias < 10 ORDER BY existencias";
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
    <h2>Reporte de Productos con existencias menores a 10</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Existencias</th>
                    <th>Bodegas</th>
                </tr>
            </thead>
            <tbody>";
while ($row = mysqli_fetch_assoc($result)) {
    $html .= "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['producto'] . "</td>
                    <td>" . $row['existencias'] . "</td>
                    <td>" . $row['bodegas'] . "</td>
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
