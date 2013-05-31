<?php

include 'ProductoEn.php';
include 'ClienteEn.php';
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
        $info=0;
        try {
            $this->Conectar();
            if ($idProducto == NULL) {
                $info = array();
                $query = mssql_query('select * from compras.dbo.producto', $this->conexion);
                while ($row = mssql_fetch_array($query)) {
                    $prod = new ProductoEn();
                    $prod->setIdProducto($row['idProducto']);
                    $prod->setNombre($row['nombre']);
                    $prod->setCantidad($row['cantidad']);
                    $prod->getPrecio($row['precio']);

                    array_push($info, $prod);
                }
            } else {
                $info = new ProductoEn();
                $info->setIdProducto($row['idProducto']);
                $info->setNombre($row['nombre']);
                $info->setCantidad($row['cantidad']);
                $info->getPrecio($row['precio']);
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
            $query = mssql_query('insert into compras.dbo.factura(idCliente,idUsuario,fecha,descuento) values(' . $idCliente . ',' . $idUsuario . ',getdate(),' . $descuento . ')', $this->conexion);

            if (mssql_num_rows($query)) {
                $idFetch = mssql_query('select top 1 idFactura,fecha from compras.dbo.factura where idUsuario=' . $idUsuario . ' order by fecha desc', $this->conexion);
                while ($row = mssql_fetch_array($idFetch)) {
                    $idF = $row['idFactura'];
                }
                foreach ($productos as $producto) {
                    $infoProducto = $this->InfoProductos($producto->getIdProducto);
                    if ($infoProducto->getCantidad() - $producto->getCantidad() >= 0) {
                        $querydesglose = mssql_query('insert into compras.dbo.desgloseFactura(idFactura,idProducto,cantidad,precioUnitario) values(' . $idF . ',' . $producto->getIdProducto() . ',' . $producto->getCantidad() . ',' . $producto->getPrecio() . ')', $this->conexion);
                        if (!mssql_num_rows($querydesglose)) {
                            echo 'No se pudo insertar el producto: ' . $infoProducto->getNombre();
                        } else {
                            $act = mssql_query('update compras.dbo.producto set cantidadInventario=' . ($infoProducto->getCantidad() - $producto->getCantidad()) . ' where idProducto=' . $infoProducto->getIdProducto());
                        }
                    }else{
                        $querydesglose = mssql_query('insert into compras.dbo.desgloseFactura(idFactura,idProducto,cantidad,precioUnitario) values(' . $idF . ',' . $producto->getIdProducto() . ',' . $infoProducto->getCantidad() . ',' . $producto->getPrecio() . ')', $this->conexion);
                        $solicitud = new ProductoEn();
                        $solicitud->setIdProducto($infoProducto->getIdProducto());
                        $solicitud->setNombre($infoProducto->getNombre());
                        $solicitud->setCantidad($producto->getCantidad()-$infoProducto->getCantidad());
                        $solicitud->setPrecio($infoProducto->getPrecio());
                        
                        $this->PedidoXFalta($idF, $solicitud, $idUsuario);
                        
                        if (!mssql_num_rows($querydesglose)) {
                            echo 'No se pudo insertar el producto: ' . $infoProducto->getNombre();
                        } else {
                            $act = mssql_query('update compras.dbo.producto set cantidadInventario=0 where idProducto=' . $infoProducto->getIdProducto());
                        }
                        
                    }
                    
                    $total= mssql_query('select sum(cantidad * precioUnitario) from compras.dbo.desgloseFactura where idFactura='.$idF,  $this->conexion);
                    while($row=  mssql_fetch_array($total)){
                        $totalNeto= $row[0];
                    }
                    mssql_query('update compras.dbo.factura set total = ',$this->conexion);
                    
                }
            } else {
                echo 'No se pudo insertar la factura';
            }


            mssql_close($this->conexion);
        } catch (Exception $e) {
            echo $e;
            mssql_close($this->conexion);
        }
    }

    public function PedidoXFalta($idFactura, $infoProducto, $idUsuario) {
        try {
            $this->Conectar();
            $query = mssql_query('select idPedido from compras.dbo.pedidosXFactura where idFactura=' . $idFactura, $this->conexion);
            if (!mssql_num_rows($query)) {
                $query = mssql_query('insert into compras.dbo.pedido(fecha,idUsuario) values(getdate(),'.$idUsuario.')');
            }
            $query = mssql_query('select top 1 idPedido,fecha from compras.dbo.pedido where idUsuario=' . $idUsuario . ' order by fecha desc', $this->conexion);
            $row = mssql_fetch_array($query);
            $idP = $row[0];

            mssql_query('insert into compras.dbo.pedidosXFactura(idFactura,idPedido) values('.$idFactura.','.$idP.')', $this->conexion);
            
            $inProd = mssql_query('insert into compras.dbo.desglosePedido(idPedido,idProducto,cantidad,precio) values('.$idP.','.$infoProducto->getIdProducto().','.$infoProducto->getCantidad().','.$infoProducto->getPrecio().')', $this->conexion);
            
            mssql_close($this->conexion);
        } catch (Exception $e) {
            echo $e;
            mssql_close($this->conexion);
        }
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
    
    
    public function GetDescuento($_total){
        try{
            $this->Conectar();
            
            mssql_query('select idDescuento,descuento,precio from compras.dbo.descuento order by precio desc',  $this->conexion);
            
            mssql_close($this->conexion);
        }  catch (Exception $ex){
            echo $ex->getMessage();
            mssql_close($this->conexion);
    }
    }

}

?>
