<?php
include("conexion.php");
$categorias = $conexion->query("SELECT * FROM categorias");
$marcas = $conexion->query("SELECT * FROM marcas");
if(isset($_POST['guardar']))
{
    $descripcion = $_POST['descripcion'];
    $umedida = $_POST['umedida'];
    $stock = $_POST['stock'];
    $stock_min = $_POST['stock_min'];

    $precio_c = $_POST['precio_c'];
    $precio_v = $_POST['precio_v'];
    $precio_p = $_POST['precio_p'];

    $fecha = $_POST['fecha'];
    $iva = $_POST['iva'];

    $especificaciones = $_POST['especificaciones'];

    $marca = $_POST['marca'];
    $categoria = $_POST['categoria'];

    $imagen = $_FILES['imagen']['name'];

    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "img/".$imagen
    );

    $sql = "INSERT INTO productos
    (
        pro_descripcion,
        pro_umedida,
        pro_stock,
        pro_stock_min,
        pro_precio_c,
        pro_precio_v,
        pro_precio_p,
        pro_fecha_elab,
        pro_paga_iva,
        pro_especificaciones,
        pro_imagen,
        mar_id,
        cat_id
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )";

    $sentencia = $conexion->prepare($sql);

    $sentencia->execute([
        $descripcion,
        $umedida,
        $stock,
        $stock_min,
        $precio_c,
        $precio_v,
        $precio_p,
        $fecha,
        $iva,
        $especificaciones,
        $imagen,
        $marca,
        $categoria
    ]);

    header("Location: productos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Producto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body style="background:#f4f4f4;">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h3>Registrar Nuevo Producto</h3>
        </div>

        <div class="card-body">

            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Unidad de Medida</label>
                    <input type="text" name="umedida" class="form-control" value="Unidad">
                </div>

                <div class="mb-3">
                    <label>Categoría</label>
                    <select name="categoria" class="form-control" required>

                        <?php
                        while($cat = $categorias->fetch(PDO::FETCH_ASSOC))
                        {
                        ?>

                        <option value="<?php echo $cat['cat_id']; ?>">
                            <?php echo $cat['cat_nombre']; ?>
                        </option>

                        <?php
                        }
                        ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label>Marca</label>
                    <select name="marca" class="form-control" required>

                        <?php
                        while($mar = $marcas->fetch(PDO::FETCH_ASSOC))
                        {
                        ?>

                        <option value="<?php echo $mar['mar_id']; ?>">
                            <?php echo $mar['mar_nombre']; ?>
                        </option>

                        <?php
                        }
                        ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Stock Mínimo</label>
                    <input type="number" name="stock_min" class="form-control" value="5">
                </div>

                <div class="mb-3">
                    <label>Precio Compra</label>
                    <input type="number" step="0.01" name="precio_c" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Precio Venta</label>
                    <input type="number" step="0.01" name="precio_v" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Precio Promoción</label>
                    <input type="number" step="0.01" name="precio_p" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Fecha Elaboración</label>
                    <input type="date" name="fecha" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Paga IVA</label>
                    <select name="iva" class="form-control">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Especificaciones</label>
                    <textarea name="especificaciones"
                            class="form-control"
                            rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label>Imagen</label>
                    <input type="file"
                        name="imagen"
                        class="form-control"
                        accept=".jpg,.jpeg,.png">
                </div>

                <button type="submit" name="guardar" class="btn btn-success">
                    Guardar Producto
                </button>

                <a href="productos.php" class="btn btn-secondary">
                    Volver
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>