<?php

include("conexion.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $sql = "DELETE FROM productos WHERE pro_id = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([$id]);
}

header("Location: productos.php");
exit();

?>