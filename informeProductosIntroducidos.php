<?php
require_once('conf.php');
require_once('dompdf/autoload.inc.php');

$filename = "Reporte_productos_introducidos_" . date("Ymd_His") . "pdf";
$query  = "SELECT id, producto, proveedor, existencias, bodegas, precio, introduccion
            FROM tbl_invesproduct 
            WHERE introduccion BETWEEN '2023-11-01' AND '2023-11-30'
            ORDER BY introduccion";

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
    <h2>Reporte de Productos Introducidos por última vez</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Proveedor</th>
                    <th>Existencias</th>
                    <th>Bodegas</th>
                    <th>Precio</th>
                    <th>Introducción</th>
                </tr>
            </thead>
            <tbody>";
while ($row = mysqli_fetch_assoc($result)) {
    $html .= "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['producto'] . "</td>
                    <td>" . $row['proveedor'] . "</td>
                    <td>" . $row['existencias'] . "</td>
                    <td>" . $row['bodegas'] . "</td>
                    <td>" . $row['precio'] . "</td>
                    <td>" . $row['introduccion'] . "</td>
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
?>