<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
    include "../conexion.php";
    if (!empty($_POST)) {
        $alert = "";
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $foto_actual = $_POST['foto_actual'];
        $foto = $_FILES['foto'];
        $fecha = date('YmdHis');
        if (empty($plato) || empty($precio) || $precio < 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            $nombre = null;
            if (!empty($foto['name'])) {
                $nombre_img = '../assets/img/platos/' . $fecha . '.jpg';
            } else if (!empty($foto_actual) && empty($foto['name'])) {
                $nombre_img = $foto_actual;
            }

            if (empty($id)) {
                $query = mysqli_query($conexion, "SELECT * FROM productos WHERE nombre = '$nombre' AND estado = 1");
                $result = mysqli_fetch_array($query);
                if ($result > 0) {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El producto ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $query_insert = mysqli_query($conexion, "INSERT INTO producto (nombre,precio,imagen) VALUES ('$nombre', '$precio', '$nombre_img')");
                    if ($query_insert) {
                        if (!empty($foto['name'])) {
                            move_uploaded_file($foto['tmp_name'], $nombre_img);
                        }
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                    } else {
                        $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el plato
                  </div>';
                    }
                }
            } else {
                $query_update = mysqli_query($conexion, "UPDATE productos SET nombre = '$nombre', precio=$precio, imagen='$nombre_img' WHERE id = $id");
                if ($query_update) {
                    if (!empty($foto['name'])) {
                        move_uploaded_file($foto['tmp_name'], $nombre_img);
                    }
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        }
    }
    include_once "includes/header.php";
?>

            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Producto</th>
                                        <th>Precio</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "../conexion.php";
                                    $query = mysqli_query($conexion, "SELECT * FROM productos WHERE estado = 1");
                                    $result = mysqli_num_rows($query);
                                    if ($result > 0) {
                                        while ($data = mysqli_fetch_assoc($query)) { ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['nombre']; ?></td>
                                                <td><?php echo $data['precio']; ?></td>
                                                
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permisos.php');
}
?>