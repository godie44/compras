<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductoEn
 *
 * @author diego
 */
class ProductoEn {
    //put your code here
    private $idProducto;
    private $nombre;
    private $precio;
    private $cantidad;
    
    public function getIdProducto() {
        return $this->idProducto;
    }

    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }


    
    
}

?>
