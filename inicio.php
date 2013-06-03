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
        function agregaProducto()
        {
            var parametros = {"producto":$("#ddlProductos").val(),"cantidad":$("#txtCantidad").val() };
            
            $.ajax({
                    data: parametros,
                    url: "acciones.php",
                    type: "POST",
                    success: function(response){
                        $("#idProductos").html(response);
                    }
            });
        }
        function infoProducto()
        {
            alert($("#ddlProductos").val());
            var parametros={"idProducto": $("#ddlProductos").val()};
            $.ajax({
                data: parametros,
                url: "acciones.php",
                type: "POST"
                 
            }).done(function(response){
                    $("#disponible").html(response);
                });
        }
        </script>
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
            <select id="ddlCliente">
                <?php
                    $clientes = $con->InfoClientes();
                    foreach($clientes as $cliente){
                        echo('<option value="'.$cliente->getIdCliente().'">'.$cliente->getNombre().'</option>');
                    }
                ?>                                       
            </select>
            <br/><br/>
            <Span>Productos</span>
            <select id="ddlProductos" onchange="infoProducto()">
                <?php
                    $productos = $con->InfoProductos();
                    foreach ($productos as $producto){
                        echo('<option value="'.$producto->getIdProducto().'">'.$producto->getNombre().'</option>');
                                
                    }
                ?>
            </select><br/>
            <span>Cantidad disponible: </span><span id="disponible"></span><br/>
            <span>Cantidad deseada</span><span><input type="text" id="txtCantidad"></text></span>
            <div id="idProductos"></div>
            </center>
            
        </form>
        </div>
        
        
    </body>
</html>
