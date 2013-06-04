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
        <title>Producto nuevo</title>
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
            
            $con->InsertaProductoNuevo($producto);
            echo "<script language=’JavaScript’>alert(‘Producto agregado con exito’);</script>";
            header("Location:inicio.php");
        }
        ?>
        <div id="content">
            <div id="top"></div>
            <div id="menu" style="background-color: lightblue;width: 800px">
                <div style="background-color: lightblue;width: 120px;float: left"><a href="inicio.php">Inicio</a></div>
            </div>
            <br/><br/>
            <center>
                <h1>Producto Nuevo</h1>
            <form method="POST" action="productos.php">
            <table>
                <tr><td>Nombre de Producto:</td><td><input type="text" name="nombre"></text></td></tr>
                <tr><td>Precio:</td><td><input type="text" name="precio"></text></td></tr>
                <tr><td>Cantidad:</td><td><input type="text" name="cantidad"></textarea></td></tr>
            </table>
                <input type="submit" name="Enviar" value="Guardar">
                    
            </form>
                </center>
        </div>
    </body>
</html>
