<?php
include 'FacturaEn.php';
include 'ProductoEn.php';
include 'ClienteEn.php';
include 'Conexion.php';

session_start();

if($_POST["producto"])
{
   $con = new Conexion();
   $listaProductos=$_SESSION['productos'];
   $prod =$con->InfoProductos($_POST["producto"]);
   $prod->setCantidad($_POST["cantidad"]);
   array_push($listaProductos,$prod);
   
   foreach ($listaProductos as $producto){
       echo '<table>
           <tr>
           <th>Id</th>
           <th>Nombre</th>
           <th>Cantidad solicitada</th>
           <th>Costo Unitario</th>
           <th>Total</th>
           </tr>
           <tr>
           <td>'.$producto->getIdProducto.'</td>
           <td>'.$producto->getNombre.'</td>
           <td>'.$producto->getCantidad.'</td>
           <td>'.$producto->getPrecio.'</td>
           <td>'.$producto->getPrecio * $producto->getCantidad.'</td>
           </tr>
           </table>';
       
   }
   $_SESSION['productos']= $listaProductos;
   
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
