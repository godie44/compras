<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/compras.css" rel="stylesheet" type="text/css"/>
        <title></title>
    </head>
    <body>
        <?php
        
        include ("Conexion.php");
        session_start();
            
            
            if(!isset($_SESSION['usuario'])){
                header("Location:index.php");
            }else{
                echo 'Usuario actual:'.$_SESSION['user'];
                $con = new Conexion();
            }
        ?>
        <div id="content">
            <div id="top"></div>
        <form methos="POST" action="factura.php">
            <center>
            <span>Clientes</span>
            <select>
                <?php
                    $clientes = $con->InfoClientes();
                    foreach($clientes as $cliente){
                        echo('<option value="'.$cliente->getIdCliente().'">'.$cliente->getNombre().'</option>');
                    }
                ?>                                       
            </select>
            <br/><br/>
            <Span>Productos</span>
            <select>
                <?php
                    $productos = $con->InfoProductos();
                    foreach ($productos as $producto){
                        echo('<option value="'.$producto->getIdProducto().'">'.$producto->getNombre().'</option>');
                                
                    }
                ?>
            </select><br/>
            <span>Cantidad disponible: </span><span id="disponible"></span>
                
            </center>
            
        </form>
        </div>
        
        
    </body>
</html>
