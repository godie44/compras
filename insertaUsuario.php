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
            $cliente = new UsuarioEn();
            $cliente->setNombre($_POST['nombre']);
            $cliente->setApellidos($_POST['apellidos']);
            $cliente->setTelefono($_POST['telefono']);
            $cliente->setDireccion($_POST['direccion']);
            $cliente->setUsuario($_POST['usuario']);
            $cliente->setPassword($_POST['password']);
            $con->InsertaUsuario($cliente);
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
                <h1>Usuario Nuevo</h1>
            <form method="POST" action="insertaUsuario.php">
            <table>
                <tr><td>Nombre:</td><td><input type="text" name="nombre"></text></td></tr>
                <tr><td>Apellidos:</td><td><input type="text" name="apellidos"></text></td></tr>
                <tr><td>Telefono:</td><td><input type="text" name="telefono"></text></td></tr>
                <tr><td>Direccion:</td><td><textarea name="direccion"></textarea></td></tr>
                <tr><td>Usuario:</td><td><input type="text" name="usuario"></text></td></tr>
                <tr><td>Password:</td><td><input type="password" name="password"></td></tr>
            </table>
                <input type="submit" name="Enviar" value="Guardar">
                    
            </form>
                </center>
        </div>
    </body>
</html>
