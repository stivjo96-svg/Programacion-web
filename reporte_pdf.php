<?php

require('pdf/fpdf.php');
include('conexion.php');

$sql = "
SELECT
p.*,
c.cat_nombre,
m.mar_nombre
FROM productos p
INNER JOIN categorias c
ON p.cat_id = c.cat_id
INNER JOIN marcas m
ON p.mar_id = m.mar_id
";

$resultado = $conexion->query($sql);

$totalProductos = $conexion->query("
SELECT COUNT(*) FROM productos
")->fetchColumn();

$stockBajo = $conexion->query("
SELECT COUNT(*) FROM productos
WHERE pro_stock <= pro_stock_min
")->fetchColumn();

$conIVA = $conexion->query("
SELECT COUNT(*) FROM productos
WHERE pro_paga_iva = 1
")->fetchColumn();

$pdf = new FPDF();

$pdf->AddPage();

$logo = __DIR__ . "/img/logo_tienda.png";
$pdf->Image($logo,10,10,25);

$pdf->SetFont('Arial','B',18);
$pdf->Ln(5);
$pdf->Cell(0,10,'REPORTE DE INVENTARIO',0,1,'C');

$pdf->SetFont('Arial','B',12);

$pdf->Ln(10);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(55,10,'Descripcion',1);
$pdf->Cell(35,10,'Categoria',1);
$pdf->Cell(35,10,'Marca',1);
$pdf->Cell(30,10,'Precio',1);
$pdf->Cell(25,10,'Stock',1);

$pdf->Ln();

$pdf->SetFont('Arial','',9);

while($fila = $resultado->fetch(PDO::FETCH_ASSOC))
{
    $pdf->Cell(
        55,
        7,
        utf8_decode($fila['pro_descripcion']),
        1
    );

    $pdf->Cell(
        35,
        7,
        utf8_decode($fila['cat_nombre']),
        1
    );

    $pdf->Cell(
        35,
        7,
        utf8_decode($fila['mar_nombre']),
        1
    );

    $pdf->Cell(
        30,
        7,
        '$'.$fila['pro_precio_v'],
        1
    );

    $pdf->Cell(
        25,
        7,
        $fila['pro_stock'],
        1
    );

    $pdf->Ln();
}

$pdf->Ln(10);

$pdf->Cell(60,8,'Total Productos:',1);
$pdf->Cell(30,8,$totalProductos,1);
$pdf->Ln();

$pdf->Cell(60,8,'Stock Bajo:',1);
$pdf->Cell(30,8,$stockBajo,1);
$pdf->Ln();

$pdf->Cell(60,8,'Productos con IVA:',1);
$pdf->Cell(30,8,$conIVA,1);
$pdf->Ln(15);

$pdf->SetFont('Arial','I',9);

$pdf->Cell(
    0,
    10,
    'Generado el '.date('d/m/Y H:i:s').' | Sistema de Inventario - Stiven Vallejo',
    0,
    1,
    'C'
);

$pdf->Output();

?>