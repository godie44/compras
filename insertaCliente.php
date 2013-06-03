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
        include ("Conexion.php");
        
        session_start();
            
            
            if(!isset($_SESSION['usuario'])){
                header("Location:index.php");
            }else{
                $con = new Conexion();
            }
        if($_POST['Enviar']){
            $cliente = new ClienteEn();
            $cliente->setNombre($_POST['nombre']);
            $cliente->setTelefono($_POST['telefono']);
            $cliente->setDireccion($_POST['direccion']);
            $cliente->setContacto($_POST['contacto']);
            $cliente->setTipo($_POST['tipo']);
            echo $_POST['nombre'].$cliente->getTelefono();
            $con->InsertaCliente($cliente);
            //header("Location:inicio.php");
        }
        ?>
        <div id="content">
            <div id="top"></div>
            <form method="POST" action="insertaCliente.php">
            <table>
                <tr><td>Nombre:</td><td><input type="text" name="nombre"></text></td></tr>
                <tr><td>Telefono:</td><td><input type="text" name="telefono"></text></td></tr>
                <tr><td>Direccion:</td><td><textarea name="direccion"></textarea></td></tr>
                <tr><td>Contacto:</td><td><input type="text" name="contacto"></text></td></tr>
                <tr><td>Tipo de cliente:</td><td><select name="tipo"><option value="1">Mayoreo</option><option value="2">Menudeo</option></select></td></tr>
            </table>
                <input type="submit" name="Enviar" value="Guardar">
                    
            </form>
        </div>
    </body>
</html>
