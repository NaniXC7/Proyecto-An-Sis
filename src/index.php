<?php
session_start();

if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
    $id = $_SESSION['idUser'];
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header text-center">
        Sucursales
    </div>
    <div class="card-body">
        <div class="row">
            <?php
            include "../conexion.php";
            if ($_SESSION['rol'] == 1) {
                $query = mysqli_query($conexion, "SELECT * FROM sucursal WHERE estado = 1");
            }else{
                $query = mysqli_query($conexion, "SELECT s.id, s.direccion, s.cajas, s.estado FROM sucursal s
                                                    inner join usuarios u
                                                    on s.id = u.sucursal_id where u.id = $id");
            }
            $query_users = mysqli_query($conexion, "SELECT * FROM usuarios WHERE estado = 1");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <div class="col-md-3 shadow-lg">
                        <div class="col-12">
                            <img src="../assets/img/salas.jpg" class="product-image" alt="Product Image">
                        </div>
                        <h6 class="my-3 text-center"><span class="badge badge-success">Sucursal</span></h6>
                        <h6 class="my-3 text-center"><span class="badge badge-info"><?php echo $data['direccion']; ?></span></h6>

                        <div class="mt-4">
                            <a class="btn btn-primary btn-block btn-flat" href="mesas.php?sucursal_id=<?php echo $data['id']; ?>&cajas=<?php echo $data['cajas']; ?>">
                                <i class="far fa-eye mr-2"></i>
                                Cajas Disponibles
                            </a>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php";
} else {
    header('Location: permisos.php');
}
?>