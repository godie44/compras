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
        <?php
        include 'Conexion.php';
        session_start();
        
        if($_POST['Enviar']){
            if(!isset($_SESSION['usuario'])){
                header("Location:index.php");
            }else{
                echo 'entro';
                $con = new Conexion();
                $cliente = $con->InfoCliente($_POST['idCliente']);
                $productos=$_SESSION['productos'];
                $idFactura = $con->InsertarCompra($cliente->getIdCliente(), $_SESSION['usuario'], $productos, $cliente->getTipo());
                
                $factura = $con->GetDetallesFactura($idFactura);
                echo 'entro2';
                foreach($factura as $info)
                    {
                        echo $info->getIdFactura();
                        echo $info->getIdProducto();
                        echo $info->getNombreProducto();
                    }
            }
        }  else {
            header("Location:index.php");
        }
        ?>
    </body>
</html>
