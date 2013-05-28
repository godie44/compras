<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClienteEn
 *
 * @author diego
 */
class ClienteEn {
    //put your code here
    private $idCliente;
    private $nombre;
    private $telefono;
    private $direccion;
    private $contacto;
    private $tipo;
    
    public function getIdCliente() {
        return $this->idCliente;
    }

    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getContacto() {
        return $this->contacto;
    }

    public function setContacto($contacto) {
        $this->contacto = $contacto;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }


}

?>
