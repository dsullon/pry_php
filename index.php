<?php
require_once('data/database.php');
//VARIABLES
$listado_tipos = [];
$listado_clientes = [];
$cantidad_clientes = 0;
$tipoCliente = null;
$tipo = 0;

$sql = "SELECT * FROM tipo_cliente";
$resultado = $db->query($sql);
$numero_filas = $resultado->num_rows;
for ($idx=0; $idx < $numero_filas; $idx++) { 
    $row = $resultado->fetch_assoc();
    $listado_tipos[] = $row;
}

if($_SERVER['REQUEST_METHOD'] ==='POST'){
    $tipo = $_POST['cboTipo'];
    $sql = "SELECT * FROM tipo_cliente WHERE id = $tipo";
    $resultado = $db->query($sql);
    if($resultado->num_rows > 0){
        $tipoCliente = $resultado->fetch_object();
    }

    $sql = "SELECT A.id, razon_social, B.nombre AS tipo_cliente, 
                C.nombre AS tipo_documento, A.numero_documento
            FROM cliente A INNER JOIN tipo_cliente B
                ON A.id_tipo_cliente = B.id INNER JOIN tipo_documento C
                ON A.id_tipo_documento = C.id
            WHERE A.id_tipo_cliente = $tipo;";
    $resultado = $db->query($sql);
    if($resultado->num_rows > 0){
        $cantidad_clientes = $resultado->num_rows;
        while($row = $resultado->fetch_assoc()):
            $listado_clientes[] = $row;
        endwhile;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Consulta</title>
</head>

<body>
    <div class="container">
        <h1>Consulta de clientes</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="cboTipo" class="form-label">Tipo de cliente</label>
                <select name="cboTipo" id="cboTipo" class="form-control">
                    <option value="0">--SELECCIONE--</option>
                    <?php 
                    if(count($listado_tipos) > 0){
                       foreach ($listado_tipos as $tipo):
                    ?>
                    <option value="<?php echo $tipo['id']?>"><?php echo $tipo['nombre'] ?></option>
                    <?php
                       endforeach;
                    }
                    ?>
                </select>
            </div>

            <input type="submit" value="Consultar" class="btn btn-primary">
        </form>
        <?php 
        if(isset($tipoCliente)):
        ?>
        <h3>Resultados de la consulta para el tipo: <?php echo $tipoCliente->nombre; ?></h3>
        <?php
            if(count($listado_clientes) > 0):
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo Cliente</th>
                        <th>Tipo Doc.</th>
                        <th>N° Documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($listado_clientes as $cliente):
                    ?>
                            <tr>
                                <td><?php echo $cliente['razon_social'] ?></td>
                                <td><?php echo $cliente['tipo_cliente'] ?></td>
                                <td><?php echo $cliente['tipo_documento'] ?></td>
                                <td><?php echo $cliente['numero_documento'] ?></td>
                                <td><a href="#" class="btn btn-primary">Ver detalles</a></td>
                            </tr>
                    <?php
                        endforeach;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Cantidad de registros: <?php echo $cantidad_clientes ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php
            endif;
        ?>

        
        <?php
        endif;
        ?>
        
    </div>
</body>

</html>