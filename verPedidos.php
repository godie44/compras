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


        if (!isset($_SESSION['usuario'])) {
            header("Location:index.php");
        } else {

            $con = new Conexion();
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
        
        
        <table>
                        <tr>
                            <td>
                                <span>Clientes</span>
                            </td><td>
                                <select name="idCliente" id="ddlCliente">
                                    <?php
                                    $clientes = $con->InfoClientes();
                                    foreach ($clientes as $cliente) {
                                        echo('<option value="' . $cliente->getIdCliente() . '">' . $cliente->getNombre() . '</option>');
                                    }
                                    ?>                                       
                                </select>
                            </td>
                        </tr>
      </table>
            </center>
        </div>
        
        
        
    </body>
</html>
