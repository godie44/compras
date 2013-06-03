<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include ("Conexion.php");
        
        session_start();
            
            
            if(!isset($_SESSION['usuario'])){
                header("Location:index.php");
            }else{
                $con = new Conexion();
            }
        if($_POST['Enviar']){
            $producto = new ProductoEn();
            $producto->setNombre($_POST['nombre']);
            $producto->setPrecio($_POST['precio']);
            $producto->setCantidad($_POST['cantidad']);
            
            $con->InsertaProducto($producto);
            header("Location:inicio.php");
        }
        ?>
        <div id="content">
            <div id="top"></div>
            <center>
            <form method="POST" action="productos.php">
            <table>
                <tr><td>Nombre:</td><td><input type="text" name="nombre"></text></td></tr>
                <tr><td>Precio:</td><td><input type="text" name="precio"></text></td></tr>
                <tr><td>Cantidad:</td><td><input type="text" name="cantidad"></textarea></td></tr>
            </table>
                <input type="submit" name="Enviar" value="Guardar">
                    
            </form>
                </center>
        </div>
    </body>
</html>
