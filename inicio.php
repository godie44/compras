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
        <script>
            $(document).ready(function() {
                $("#btnFacturar").hide();
                $("#ddlProductos").change(function()
                {
                    var id = $("#ddlProductos").val();
                    $.ajax({
                        data: {idProducto: id},
                        url: "acciones.php",
                        type: "POST"

                    }).done(function(response) {

                        $("#disponible").html(response);
                    });
                });
                $('#btnAgregar').click(function()
                {


                    $.ajax({
                        data: {producto: $("#ddlProductos").val(), cantidad: $("#txtCantidad").val()},
                        url: "acciones.php",
                        type: "POST",
                        beforeSend: function() {
                            $("#idProductos").html("Procesando, espere por favor...");
                        }

                    }).done(function(response) {


                        $("#idProductos").fadeOut(1000, function() {
                            $("#idProductos").html(response);
                        });
                        $("#btnFacturar").fadeIn(2500);
                        $("#idProductos").fadeIn(1000);
                    });

                });







            });




        </script>
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
                <div style="background-color: lightblue;width: 120px;float: left"><a href="insertaCliente.php">Nuevo Cliente</a></div>
                <div style="background-color: lightblue;width: 120px;float: left"><a href="#">Nuevo Usuario</a></div>
                <div style="background-color: lightblue;width: 120px;float: left"><a href="productos.php">Productos</a></div>
            </div><br/>
            <span style="float: right">
<?php echo 'Usuario actual:' . $_SESSION['user']; ?>
                </span>
            <center>
                <form action="factura.php" method="POST">

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
                        <br/><br/>
                        <tr><td>
                                <Span>Productos</span>
                            </td><td>
                                <select name="idProducto" id="ddlProductos">
                                    <?php
                                    $productos = $con->InfoProductos();
                                    foreach ($productos as $producto) {
                                        echo('<option value="' . $producto->getIdProducto() . '">' . $producto->getNombre() . '</option>');
                                    }
                                    ?>
                                </select><br/>
                            </td>
                        <tr><td>
                                <div id="disponible"></div>
                            </td>
                        <tr><td>
                                <span>Cantidad deseada</span><span</td><td><input type="text" id="txtCantidad" style="width: 30px"/></td>
                        </tr>
                        </table>
                        <button id="btnAgregar" type="button">Agregar</button>
                    
                    <div id="idProductos"></div>

                    <br/><br/>
                    <input type="submit" id="btnFacturar" name="Enviar" value="Generar Factura"/>
                </form>

            </center>


        </div>


    </body>
</html>
