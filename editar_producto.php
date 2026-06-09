<?php

include("conexion.php");

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE pro_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);

$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['actualizar']))
{
    $descripcion = $_POST['descripcion'];
    $stock = $_POST['stock'];
    $precio = $_POST['precio'];

    $sqlUpdate = "
    UPDATE productos
    SET
        pro_descripcion = ?,
        pro_stock = ?,
        pro_precio_v = ?
    WHERE pro_id = ?
    ";

    $stmtUpdate = $conexion->prepare($sqlUpdate);

    $stmtUpdate->execute([
        $descripcion,
        $stock,
        $precio,
        $id
    ]);

    header("Location: productos.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Editar Producto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f4f4f4;">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <h3>Editar Producto</h3>

        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label>Descripción</label>

                    <input
                        type="text"
                        name="descripcion"
                        class="form-control"
                        value="<?php echo $producto['pro_descripcion']; ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label>Stock</label>

                    <input
                        type="number"
                        name="stock"
                        class="form-control"
                        value="<?php echo $producto['pro_stock']; ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label>Precio Venta</label>

                    <input
                        type="number"
                        step="0.01"
                        name="precio"
                        class="form-control"
                        value="<?php echo $producto['pro_precio_v']; ?>"
                        required>
                </div>

                <button
                    type="submit"
                    name="actualizar"
                    class="btn btn-success">

                    Actualizar Producto

                </button>

                <a
                    href="productos.php"
                    class="btn btn-secondary">

                    Volver

                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>