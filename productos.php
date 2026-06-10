<?php

$host = "localhost";
$dbname = "db_tienda";
$user = "root";
$pass = "root";

try {

    $conexion = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    die("Error de conexión: " . $e->getMessage());

}

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

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Gestión de Inventario - Productos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<style>

td{
    vertical-align:middle !important;
}

table.dataTable{
    border-radius:10px;
    overflow:hidden;
}

</style>

</head>

<body style="background-color:#dcdcdc;">

<?php

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

?>

<div class="container mt-4">

    <div class="card shadow-lg">

        <div class="card-header bg-success text-white">

            <div style="display:flex; align-items:center;">

                <img src="img/logo_tienda.png"
                    width="60"
                    height="60"
                    style="margin-right:15px;">

                <h2 style="margin:0;">
                    Gestión de Inventario - Productos
                </h2>

                <a href="nuevo_producto.php"
                    style="
                    margin-left:auto;
                    background:white;
                    color:black;
                    padding:12px 25px;
                    border-radius:10px;
                    text-decoration:none;
                    font-size:20px;
                    font-weight:bold;
                    ">

                    ➕ Nuevo Producto

                </a>

            </div>

            <p style="
            margin-top:10px;
            font-size:20px;
            font-weight:bold;
            color:white;
            ">
                Por: Stiven Vallejo
            </p>

            <p style="color:white;">
                Fecha: <?php echo date('d/m/Y'); ?>
            </p>

        </div>

        <div class="card-body">

        <?php

        if(isset($_GET['mensaje']))
        {

            if($_GET['mensaje']=="guardado")
            {
                echo '
                <div class="alert alert-success alert-dismissible fade show">

                    ✅ Producto registrado correctamente.

                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                    </button>

                </div>';
            }

            if($_GET['mensaje']=="actualizado")
            {
                echo '
                <div class="alert alert-primary alert-dismissible fade show">

                    ✏️ Producto actualizado correctamente.

                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                    </button>

                </div>';
            }

            if($_GET['mensaje']=="eliminado")
            {
                echo '
                <div class="alert alert-danger alert-dismissible fade show">

                    🗑️ Producto eliminado correctamente.

                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                    </button>

                </div>';
            }

        }

        ?>

            <div class="row mb-3">

                <div class="col-md-4">
                    <div class="alert alert-primary">
                        📦 Total Productos:
                        <strong><?php echo $totalProductos; ?></strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-danger">
                        ⚠ Stock Bajo:
                        <strong><?php echo $stockBajo; ?></strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-success">
                        💰 Con IVA:
                        <strong><?php echo $conIVA; ?></strong>
                    </div>
                </div>

            </div>

            <table id="tablaProductos" class="table table-striped table-hover">

                <thead>

                <tr>

                    <th>Imagen</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>IVA</th>
                    <th>Acciones</th>

                </tr>

                </thead>

                <tbody>

                <?php
                while($fila = $resultado->fetch(PDO::FETCH_ASSOC))
                {
                ?>

                <tr>

                    <td>

                        <img
                        src="img/<?php echo $fila['pro_imagen']; ?>"
                        width="70"
                        height="70"
                        class="rounded shadow">

                    </td>

                    <td>
                        <?php echo $fila['pro_descripcion']; ?>
                    </td>

                    <td>
                        <?php echo $fila['cat_nombre']; ?>
                    </td>

                    <td>
                        <?php echo $fila['mar_nombre']; ?>
                    </td>

                    <td>
                        $<?php echo $fila['pro_precio_v']; ?>
                    </td>

                    <td>
                        <?php echo $fila['pro_stock']; ?>
                    </td>

                    <td>

                        <?php

                        if($fila['pro_stock'] <= $fila['pro_stock_min'])
                        {
                            echo '<span class="badge bg-danger">🔴 Stock Bajo</span>';
                        }
                        else
                        {
                            echo '<span class="badge bg-success">🟢 Normal</span>';
                        }

                        ?>

                    </td>

                    <td>

                        <?php

                        if($fila['pro_paga_iva']==1)
                        {
                            echo '<span class="badge bg-success">Sí</span>';
                        }
                        else
                        {
                            echo '<span class="badge bg-secondary">No</span>';
                        }

                        ?>

                    </td>

                    <td>

                        <a
                        href="editar_producto.php?id=<?php echo $fila['pro_id']; ?>"
                        class="btn btn-warning btn-sm">

                            ✏️ Editar

                        </a>

                        <a
                        href="eliminar.php?id=<?php echo $fila['pro_id']; ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('¿Desea eliminar este producto?');">

                            🗑 Eliminar

                        </a>

                    </td>

                </tr>

                <?php
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

$(document).ready(function () {

    $('#tablaProductos').DataTable({

        language: {

            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'

        }

    });

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>