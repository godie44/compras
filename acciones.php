<?php
include 'Conexion.php';


session_start();

if($_POST["producto"])
{
   $total = 0;
   $con = new Conexion();
   if(isset($_SESSION['productos'])){
   $listaProductos=$_SESSION['productos'];}
   else{$listaProductos=array();}
   $prod =$con->InfoProductos($_POST["producto"]);
   $prod->setCantidad($_POST["cantidad"]);
   array_push($listaProductos,$prod);
   echo '<table style="border-collapse:collapse;border: 1px solid blue;border-spacing:50px 0">
           <tr style="border-collapse:collapse;border: 1px solid blue;">
           <th>Id</th>
           <th>Nombre</th>
           <th>Cantidad solicitada</th>
           <th>Costo Unitario</th>
           <th>Total</th>
           <th></th>
           </tr>';
   foreach ($listaProductos as $producto){
       echo '
           <tr>
           <td>'.$producto->getIdProducto().'</td>
           <td>'.$producto->getNombre().'</td>
           <td>'.$producto->getCantidad().'</td>
           <td>'.$producto->getPrecio().'</td>
           <td>'.$producto->getPrecio() * $producto->getCantidad().'</td>';
           $cant=$con->InfoProductos($producto->getIdProducto());
           if($cant->getCantidad()<$producto->getCantidad()){
               echo '<td style="color:green">Se hara un pedido extra por los '.($producto->getCantidad()-$cant->getCantidad()).' faltantes</td>
                     </tr>';
           }else{
               echo '<td></td></tr>';
           }
           $total += $producto->getPrecio() * $producto->getCantidad();
       
   }
   echo '<tr style="border-collapse:collapse;border: 1px solid blue;"><td></td>';
   echo '<td></td>';
   echo '<td></td>';
   echo '<td></td>';
   echo '<td>'.$total.'</td>';
   echo '<td></td></tr>';
   
   echo '</table>';
   $_SESSION['productos']= $listaProductos;
   $_POST['producto']=NULL;
}elseif($_POST['idProducto'])
    {
        
        $con = new Conexion();
        $prod =$con->InfoProductos($_POST["idProducto"]);
        echo 'Cantidad disponible: '.$prod->getCantidad().'<br/>Precio: '.$prod->getPrecio();
    }
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
