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
   $nuevo=true;
   $prod =$con->InfoProductos($_POST["producto"]);
   $prod->setCantidad($_POST["cantidad"]);
   foreach($listaProductos as $p)
       {
            if($p->getIdProducto() == $prod->getIdProducto())
                {
                    $p->setCantidad($p->getCantidad()+$prod->getCantidad());
                    $nuevo=false;
                }
       }
   if($prod->getCantidad()>0){
   if($nuevo==true){    
   array_push($listaProductos,$prod);
   }
   
   }else
       {
       echo 'Por favor solicitar una cantidad mayor a 0';
       }
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
    elseif ($_POST["idPedido"]) {
        
        //EN IDPEDIDO SE ENVIA EL ID DEL CLIENTE
        $con = new Conexion();
        $infoPedidos = $con->GetInfoPedido($_POST["idPedido"]);
                //SE USO LA ENTIDAD DE LA FACTURA PERO TIENE LA INFORMACION DE LOS PEDIDOS!!!!!!!!!!!!
                
                foreach($infoPedidos as $infoP)
                {
                    $pedidos = $con->GetDetallesPedido($infoP->getIdFactura());
                    echo '<br/><br/><br/>
                        <table style="border-collapse:collapse;width:100%;border: 2px solid blue;">
                        <tr >
                        <th></th>
                        <th>Fecha:'.$infoP->getFecha().'</th>
                        <th></th>
                        <th></th>
                        <th>Pedido:'.$infoP->getIdFactura().'</th>
                        <th></th>
                        </tr>

                        <tr>
                        <th></th>
                        <th>Nombre: '.$infoP->getNombreCliente().'</th>
                        <th>Telefono: '.$infoP->getTelefono().'</th>
                        <th></th>
                        <th>Cajero: '.$infoP->getNombreUsuario().'</th>
                        <th></th>
                        </tr>

                        <tr style="border-collapse:collapse;border: 2px solid blue;">
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Cantidad solicitada</th>
                        <th>Costo Unitario</th>
                        <th>Total</th>
                        <th></th>
                        </tr>';
                    foreach ($pedidos as $info) {
                                              
                        echo '
                        <tr style="border-collapse:collapse;border: 1px solid blue;">
                            <td>' . $info->getIdProducto() . '</td>
                            <td>' . $info->getNombreProducto() . '</td>
                            <td>' . $info->getCantidadProducto() . '</td>
                            <td>' . $info->getPrecioProducto() . '</td>
                            <td>' . $info->getPrecioProducto() * $info->getCantidadProducto() . '</td>';
                   echo '<td></td></tr>';
                            
                            $total = $info->getTotal();
                        }
                                                
                        echo '<tr style="border-collapse:collapse;border: 1px solid blue;"><td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Total a pagar:</td>';
                        echo '<td>' . $total . '</td>';
                        echo '<td></td></tr>';

                        echo '</table>';
                }
        
        
        
            
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
