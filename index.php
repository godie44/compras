<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link href="css/compras.css" rel="stylesheet" type="text/css"/>
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <title>Sistema de Compras</title>
<script>
  $(function() {
    $( "#login" ).dialog({
      height: 250,
      modal: true      
    });
  });
  </script>
        
    </head>
    <body>
        <?php
        include ("Conexion.php");
        session_start();
        $_SESSION['productos']=NULL;
        if($_POST['usuario'] and $_POST['password']){
        $con = new Conexion();
        
        $res=$con->Login($_POST['usuario'], $_POST['password']);
        if($res){
            $_SESSION['usuario'] = $res;
            $_SESSION['user'] = $_POST['usuario'];
            header("Location:inicio.php");
        }else{
            echo '<script type="text/javascript">alert("Usuario y/o password incorrecto")</script>';
        }
        }
        ?>
    <div id="content">

            <div id="top">
            </div> 
        Iniciar sesion para accesar al sistema
    </div>
        
        <div id="login">
            <form method="POST" action="index.php">
         <fieldset class="textbox">
        <label class="usuario">
        <span>Usuario</span>
        <input id="usuario" name="usuario" value="" type="text" autocomplete="on" placeholder="Usuario">
        </label>
        <label class="password">
        <span>Password</span>
        <input id="password" name="password" value="" type="password" placeholder="Password">
        </label>
        <button class="submit button" type="submit">Iniciar</button>
             
        </fieldset>
  </form>
           
        </div>
    

    
    </body>
</html>
