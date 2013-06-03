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
        $(document).ready(function(){
            $("#btnFacturar").hide();
            $("#ddlProductos").change(function()
        {
            var id = $("#ddlProductos").val();
            $.ajax({
                data: {idProducto: id},
                url: "acciones.php",
                type: "POST"
                 
            }).done(function(response){
                    
                    $("#disponible").html(response);
                });
        });
        $('#btnAgregar').click(function()
        {
            
            
            $.ajax({
                    data: {producto:$("#ddlProductos").val(),cantidad:$("#txtCantidad").val() },
                    url: "acciones.php",
                    type: "POST",
                    beforeSend: function () {
                        $("#idProductos").html("Procesando, espere por favor...");}

                    }).done(function(response){
                        
                        
                        $("#idProductos").fadeOut(1000,function(){$("#idProductos").html(response);});
                        $("#btnFacturar").fadeIn(2500);
                        $("#idProductos").fadeIn(2000);
                    });
                        
            });
            
            
            $('#btnFacturar').click(function()
        {
            
            
            $.ajax({
                    data: {producto:$("#ddlProductos").val(),cantidad:$("#txtCantidad").val() },
                    url: "acciones.php",
                    type: "POST",
                    beforeSend: function () {
                        $("#idProductos").html("Procesando, espere por favor...");}

                    }).done(function(response){
                        
                        
                        $("#idProductos").fadeOut(1000,function(){$("#idProductos").html(response);});
                        $("#btnFacturar").fadeIn(2500);
                        $("#idProductos").fadeIn(2000);
                    });
                        
            });
            
            
            
            
        });
        
        
        
        
        </script>
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
        ?>
        <div id="content">
            <div id="top"></div>
            <?php echo 'Usuario actual:'.$_SESSION['user'];?>
            <center>
                <form action="factura.php" method="POST">
            <span>Clientes</span>
            <select name="idCliente" id="ddlCliente">
                <?php
                    $clientes = $con->InfoClientes();
                    foreach($clientes as $cliente){
                        echo('<option value="'.$cliente->getIdCliente().'">'.$cliente->getNombre().'</option>');
                    }
                ?>                                       
            </select>
            <br/><br/>
            <Span>Productos</span>
            <select name="idProducto" id="ddlProductos">
                <?php
                    $productos = $con->InfoProductos();
                    foreach ($productos as $producto){
                        echo('<option value="'.$producto->getIdProducto().'">'.$producto->getNombre().'</option>');
                                
                    }
                ?>
            </select><br/>
            <div id="disponible"></div><br/>
            <span>Cantidad deseada</span><span><input type="text" id="txtCantidad" style="width: 30px"></text></span>
            <button type="button" id="btnAgregar">Agregar</button>
            <div id="idProductos"></div>
            <br/><br/>
            <input type="submit" id="btnFacturar" name="Enviar" value="Generar Factura"/>
            </form>
            </center>
            
        
        </div>
        
        
    </body>
</html>
