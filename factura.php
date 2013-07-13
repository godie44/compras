<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/compras.css" rel="stylesheet" type="text/css"/>
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <title></title>
    </head>
    <body>
        <div id="content">
            <div id="top"></div>
            <div id="menu" style="background-color: lightblue;width: 800px">
                <div style="background-color: lightblue;width: 120px;float: left"><a href="inicio.php">Inicio</a></div>
            </div>
            <?php
            include 'Conexion.php';
            session_start();

            if ($_POST['Enviar']) {
                if (!isset($_SESSION['usuario'])) {
                    header("Location:index.php");
                } else {
                    
                    $con = new Conexion();
                    $cliente = $con->InfoCliente($_POST['idCliente']);
                    $productos = $_SESSION['productos'];
                    $idFactura = $con->InsertarCompra($cliente->getIdCliente(), $_SESSION['usuario'], $productos, $cliente->getTipo());
                    
                    $factura = $con->GetDetallesFactura($idFactura);
                    $infoPersonal = $con->GetInfoFactura($idFactura);
                    if($idFactura != -99){
                     echo '<table style="border-collapse:collapse;width:100%;border: 2px solid blue;">
                        <tr >
                        <th></th>
                        <th>Fecha:'.$infoPersonal->getFecha().'</th>
                        <th></th>
                        <th></th>
                        <th>Factura:'.$idFactura.'</th>
                        <th></th>
                        </tr>

                        <tr>
                        <th></th>
                        <th>Nombre: '.$infoPersonal->getNombreCliente().'</th>
                        <th>Telefono: '.$infoPersonal->getTelefono().'</th>
                        <th></th>
                        <th>Cajero: '.$infoPersonal->getNombreUsuario().'</th>
                        <th></th>
                        </tr>

                        <tr style="border-collapse:collapse;border: 2px solid blue;">
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Cantidad solicitada</th>
                        <th>Costo Unitario</th>
                        <th>Total</th>
                        <th></th>
                        </tr>';
                    foreach ($factura as $info) {
                                              
                        echo '
                        <tr style="border-collapse:collapse;border: 1px solid blue;">
                            <td>' . $info->getIdProducto() . '</td>
                            <td>' . $info->getNombreProducto() . '</td>
                            <td>' . $info->getCantidadProducto() . '</td>
                            <td>' . $info->getPrecioProducto() . '</td>
                            <td>' . $info->getPrecioProducto() * $info->getCantidadProducto() . '</td>';
                   echo '<td></td></tr>';
                            
                            $total = $info->getTotal();
                        }
                        echo '<tr><td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Descuento:</td>';
                        echo '<td>' . $info->getDescuento() . '%</td>';
                        echo '<td></td></tr>';
                        
                        echo '<tr><td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Impuesto:</td>';
                        echo '<td>' . $info->getException() . '%</td>';
                        echo '<td></td></tr>';
                        
                        echo '<tr style="border-collapse:collapse;border: 1px solid blue;"><td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Total a pagar:</td>';
                        echo '<td>' . $total . '</td>';
                        echo '<td></td></tr>';

                        echo '</table>';
                }
                        $_SESSION['productos'] = array();
                }
            } else {
                header("Location:index.php");
            }
            
            ?>

        </div> 
    </body>
</html>
