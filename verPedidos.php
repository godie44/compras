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


        if (!isset($_SESSION['usuario'])) {
            header("Location:index.php");
        } else {

            $con = new Conexion();
        }
        ?>
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
        
        
        
        
    </body>
</html>
