<?php

include 'ProductoEn.php';
include 'ClienteEn.php';
include 'FacturaEn.php';
include 'UsuarioEn.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author diego
 */
class Conexion {
    
    public $conexion;

    public function Conectar() {
        try {

            $this->conexion = mssql_connect('190.7.192.3', 'sa', 'D4t42012');


            if ($this->conexion == FALSE) {

                die("No se pudo conectar a la base");
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function Login($usuario, $pass) {
        $resultado = false;
        try {
            $this->Conectar();

            $query = mssql_query("select * from compras.dbo.usuario where usuario='" . $usuario . "' and password='" . $pass . "'", $this->conexion);
            if (!mssql_num_rows($query)) {
                $resultado = false;
            } else {
                $row=  mssql_fetch_array($query);
                
                $resultado = $row['idUsuario'];
            }
            mssql_close($this->conexion);
        } catch (Exception $ex) {
            echo $ex;
            mssql_close($this->conexion);
        }
        return $resultado;
    }

    public function InfoProductos($idProducto = NULL) {
        $info='';
        try {
            $this->Conectar();
            if ($idProducto == NULL) {
                $info = array();
                $query = mssql_query('select * from compras.dbo.producto', $this->conexion);
                while ($row = mssql_fetch_array($query)) {
                    $prod = new ProductoEn();
                    $prod->setIdProducto($row['idProducto']);
                    $prod->setNombre($row['nombre']);
                    $prod->setCantidad($row['cantidadInventario']);
                    $prod->getPrecio($row['precio']);

                    array_push($info, $prod);
                }
            } else {
                $query = mssql_query('select idProducto,nombre,cantidadInventario,precio from compras.dbo.producto where idProducto='.$idProducto, $this->conexion);
                $row = mssql_fetch_array($query);
                $info = new ProductoEn();
                $info->setIdProducto($row['idProducto']);
                $info->setNombre($row['nombre']);
                $info->setCantidad($row['cantidadInventario']);
                $info->setPrecio($row['precio']);
            }

            mssql_close($this->conexion);
        } catch (Exception $e) {
            echo $e;
            mssql_close($this->conexion);
        }
        return $info;
    }

    public function InsertarCompra($idCliente, $idUsuario, $productos, $descuento, $archivo = NULL) {
        $idF = 0;
        try {
            $this->Conectar();
            $query = mssql_query('insert into compras.dbo.factura(idCliente,idUsuario,fecha) values(' . $idCliente . ',' . $idUsuario . ',getdate())', $this->conexion);

            if (mssql_rows_affected($this->conexion)>0) {
                
                $idFetch = mssql_query('select top 1 idFactura,fecha from compras.dbo.factura where idUsuario=' . $idUsuario . ' and idCliente='.$idCliente.' order by fecha desc', $this->conexion);
                
                while ($row = mssql_fetch_array($idFetch)) {
                    $idF = $row['idFactura'];
                    
                }
                foreach ($productos as $producto) {
                    $infoProducto = $this->InfoProductos($producto->getIdProducto());
                    
                    if (($infoProducto->getCantidad() - $producto->getCantidad()) >= 0) {
                        
                        $n = $this->insertaProducto($producto, $idF);
                        
                        if ($n<0) {
                            echo 'No se pudo insertar el producto: ' . $infoProducto->getNombre();
                        } else {
                            
                            $act = mssql_query('update compras.dbo.producto set cantidadInventario=' . ($infoProducto->getCantidad() - $producto->getCantidad()) . ' where idProducto=' . $infoProducto->getIdProducto());
                        }
                    }else{
                        //$producto->setCantidad($infoProducto->getCantidad());
                        $n = $this->insertaProducto($producto, $idF);
                        $solicitud = new ProductoEn();
                        $solicitud->setIdProducto($infoProducto->getIdProducto());
                        $solicitud->setNombre($infoProducto->getNombre());
                        $solicitud->setCantidad(($producto->getCantidad()-$infoProducto->getCantidad()));
                        $solicitud->setPrecio($infoProducto->getPrecio());
                        echo 'Resultado resta'.$producto->getCantidad().'-'.$infoProducto->getCantidad().' ='.$solicitud->getCantidad();
                        $this->PedidoXFalta($idF, $solicitud, $idUsuario);
                        
                        if ($n<0) {
                            echo 'No se pudo insertar el producto: ' . $infoProducto->getNombre();
                        } else {
                            
                            $act = mssql_query('update compras.dbo.producto set cantidadInventario=0 where idProducto=' . $producto->getIdProducto());
                            
                            
                        }
                        
                    }
                    
                    
                }
                $total= mssql_query('select sum(cantidad * precioUnitario) from compras.dbo.desgloseFactura where idFactura='.$idF,  $this->conexion);
                    while($row=  mssql_fetch_array($total)){
                        $totalNeto= $row[0];
                    }
                    
                    if($descuento == 1){
                    $descuentoC = $this->GetDescuento($totalNeto);
                    $descuentoAplicado =$totalNeto*($descuentoC/100);}
                    else{$descuentoC =0;$descuentoAplicado=0;}
                    if(!$this->GetExcepcionImpuestos($idF)){
                    $impuesto = 13;
                    $impuestoAplicado =$totalNeto*(13/100); 
                    }else{
                        $impuestoAplicado=0;
                        $impuesto= 0;
                    }
                    
                    $totalFinal = $totalNeto - $descuentoAplicado +$impuestoAplicado;
                    mssql_query('update compras.dbo.factura set total = '.$totalFinal.',descuento='.$descuentoC.',impuesto='.$impuesto.' where idFactura='.$idF,$this->conexion);
                    
            } else {
                echo 'No se pudo insertar la factura';
            }


            mssql_close($this->conexion);
        } catch (Exception $e) {
            echo $e->getMessage();
            mssql_close($this->conexion);
        }
        return $idF;
    }

    public function PedidoXFalta($idFactura, $infoProducto, $idUsuario) {
        try {
            //$this->Conectar();
            $query = mssql_query('select idPedido from compras.dbo.pedidosXFactura where idFactura=' . $idFactura, $this->conexion);
            if (mssql_num_rows($query)==0) {    
                
                $query2 = mssql_query('insert into compras.dbo.pedido(fecha,idUsuario) values(getdate(),'.$idUsuario.')');
            
            $queryId = mssql_query('select top 1 idPedido,fecha from compras.dbo.pedido where idUsuario=' . $idUsuario . ' order by fecha desc', $this->conexion);
           
            $row = mssql_fetch_array($queryId);
            $idP = $row[0];

            mssql_query('insert into compras.dbo.pedidosXFactura(idFactura,idPedido) values('.$idFactura.','.$idP.')', $this->conexion);
            }else{
                $row = mssql_fetch_array($query);
                $idP = $row[0];
            }
            $inProd = mssql_query('insert into compras.dbo.desglosePedido(idPedido,idProducto,cantidad,precio) values('.$idP.','.$infoProducto->getIdProducto().','.$infoProducto->getCantidad().','.$infoProducto->getPrecio().')', $this->conexion);
            
            //mssql_close($this->conexion);
        } catch (Exception $e) {
            echo $e->getMessage();
            //mssql_close($this->conexion);
        }
    }
    
    
    public function insertaProducto($producto,$idF){
        $this->Conectar();
        $desg = mssql_query('insert into compras.dbo.desgloseFactura(idFactura,idProducto,cantidad,precioUnitario) values(' . $idF . ',' . $producto->getIdProducto() . ',' . $producto->getCantidad() . ',' . $producto->getPrecio() . ')', $this->conexion);
        $n= mssql_rows_affected($this->conexion);
        mssql_close($this->conexion);
        return $n;
    }
    
    
    public function InsertaCliente($cliente){
        
        $this->Conectar();
        $q = mssql_query("insert into compras.dbo.cliente(nombre,telefono,direccion,contacto,tipo) values('". $cliente->getNombre() ."',". $cliente->getTelefono() .",'". $cliente->getDireccion() ."','". $cliente->getContacto() . "',".$cliente->getTipo().")", $this->conexion);
        $n= mssql_rows_affected($this->conexion);
        echo " 
                <script language=’JavaScript’> 
                alert(‘Cliente agregado con exito’); 
                </script>";
        mssql_close($this->conexion);
        return $n;
            
            }
            
            
      public function InsertaUsuario($usuario){
        
        $this->Conectar();
        $q = mssql_query("insert into compras.dbo.usuario(nombre,apellidos,telefono,direccion,usuario,password) values('". $usuario->getNombre() ."','". $usuario->getApellidos() ."',". $usuario->getTelefono() .",'". $usuario->getDireccion() ."','". $usuario->getUsuario() . "','".$usuario->getPassword()."')", $this->conexion);
        $n= mssql_rows_affected($this->conexion);
        echo " 
                <script language=’JavaScript’> 
                alert(‘Usuario agregado con exito’); 
                </script>";
        mssql_close($this->conexion);
        return $n;
            
            }     
       
            
            
            
            
    public function InsertaProductoNuevo($producto){
        
        $this->Conectar();
        $q = mssql_query("insert into compras.dbo.producto(nombre,precio,cantidadInventario) values('". $producto->getNombre() ."',". $producto->getPrecio() .",". $producto->getCantidad() .")", $this->conexion);
        $n= mssql_rows_affected($this->conexion);
        
        mssql_close($this->conexion);
        return $n;
            
            }
            
            
    
    
    
    public function InfoClientes()
    {
        $clientes = array();
        try{
            $this->Conectar();
            
            $query= mssql_query('select idCliente,nombre,telefono,direccion,contacto,tipo from compras.dbo.cliente',  $this->conexion);
            
            while($row = mssql_fetch_array($query)){
                $cliente = new ClienteEn();
                $cliente->setIdCliente($row[0]);
                $cliente->setNombre($row[1]);
                $cliente->setTelefono($row[2]);
                $cliente->setDireccion($row[3]);
                $cliente->setContacto($row[4]);
                $cliente->setTipo($row[5]);
                
                array_push($clientes, $cliente);
            }
            
            mssql_close($this->conexion);
        }  catch (Exception $e){
            echo $e;
            mssql_close($this->conexion);
            
        }
        return $clientes;
    }
    
    
    public function InfoCliente($_idCliente)
    {
       $cliente = new ClienteEn();
        try{
            $this->Conectar();
            
            $query= mssql_query('select idCliente,nombre,telefono,direccion,contacto,tipo from compras.dbo.cliente where idCliente='.$_idCliente,  $this->conexion);
            
            while($row = mssql_fetch_array($query)){
                
                $cliente->setIdCliente($row[0]);
                $cliente->setNombre($row[1]);
                $cliente->setTelefono($row[2]);
                $cliente->setDireccion($row[3]);
                $cliente->setContacto($row[4]);
                $cliente->setTipo($row[5]);
                
                
            }
            
            mssql_close($this->conexion);
        }  catch (Exception $e){
            echo $e;
            mssql_close($this->conexion);
            
        }
        return $cliente;
    }
    
    
    public function GetDescuento($_total){
        $descuento = 0;
        try{
            $this->Conectar();
            
            $query = mssql_query('select idDescuento,descuento,precio from compras.dbo.descuento order by precio desc',  $this->conexion);
            
            while($row= mssql_fetch_array($query)){
                if($_total > $row[2]){
                    $descuento = $row[1];
                    break;
                }
            }
            mssql_close($this->conexion);
        }  catch (Exception $ex){
            echo $ex->getMessage();
            mssql_close($this->conexion);
    }
    return $descuento;
    }
    
    public function GetExcepcionImpuestos($_idFactura){
        $excepcion = false;
        try {
            $this->Conectar();
            $query = mssql_query('select idExcepcion,idCliente,archivo,idFactura from compras.dbo.excepcion where idFactura='.$_idFactura,$this->conexion);
            if(mssql_num_rows($query)){
                $excepcion = true;
            }
            mssql_close($this->conexion);
        }  catch (Exception $ex){
            echo $ex;
            mssql_close($this->conexion);
        }
          return $excepcion;      
    }
    
    public function GetDetallesFactura($_idFactura){
        $desglose = array();
        try{
            $this->Conectar();
            
            $query = mssql_query('select f.idFactura,df.idProducto,df.cantidad,df.precioUnitario,p.nombre,f.idCliente,c.nombre,c.telefono,f.fecha,f.descuento,f.impuesto,f.total,f.idUsuario,u.nombre from compras.dbo.desgloseFactura df inner join compras.dbo.factura f on df.idFactura = f.idFactura inner join compras.dbo.cliente c on f.idCliente = c.idCliente inner join compras.dbo.usuario u on u.idUsuario = f.idUsuario inner join compras.dbo.producto p on df.idProducto = p.idProducto where df.idFactura ='.$_idFactura,  $this->conexion);
            
            while($row=  mssql_fetch_array($query)){
                $infoFact = new FacturaEn();
                $infoFact->setIdFactura($row[0]);
                $infoFact->setIdProducto($row[1]);
                $infoFact->setCantidadProducto($row[2]);
                $infoFact->setPrecioProducto($row[3]);
                $infoFact->setNombreProducto($row[4]);
                echo($infoFact->getNombreProducto());
                $infoFact->setIdCliente($row[5]);
                $infoFact->setNombreCliente($row[6]);
                $infoFact->setTelefono($row[7]);
                $infoFact->setFecha($row[8]);
                $infoFact->setDescuento($row[9]);
                $infoFact->setException($row[10]);
                $infoFact->setTotal($row[11]);
                $infoFact->setIdUsuario($row[12]);
                $infoFact->setNombreUsuario($row[13]);
                
                array_push($desglose, $infoFact);
            }
            
            
            
         mssql_close($this->conexion);
        }  catch (Exception $ex){
            echo $ex;
            mssql_close($this->conexion);
        }
        return $desglose;
    }
    
    
    public function GetInfoFactura($_idFactura)
    {
        $factura = new FacturaEn();
        try{
           $this->Conectar();
           $query = mssql_query('select f.idFactura,f.idCliente,c.nombre,c.telefono,f.fecha,f.idUsuario,u.nombre from compras.dbo.factura f inner join compras.dbo.cliente c on f.idCliente = c.idCliente inner join compras.dbo.usuario u on u.idUsuario = f.idUsuario where f.idFactura ='.$_idFactura,  $this->conexion);
           $row= mssql_fetch_array($query);
           $factura->setIdFactura($row[0]);
           $factura->setIdCliente($row[1]);
           $factura->setNombreCliente($row[2]);
           $factura->setTelefono($row[3]);
           $factura->setFecha($row[4]);
           $factura->setIdUsuario($row[5]);
           $factura->setNombreUsuario($row[6]);
           mssql_close($this->conexion);
        }  catch (Exception $ex){
            echo $ex;
            mssql_close($this->conexion);
        }
        return $factura;
    }

}

?>
