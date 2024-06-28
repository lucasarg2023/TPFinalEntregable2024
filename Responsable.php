<?php

class Responsable {
    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $mensajeoperacion;

    public function __construct() {
        $this->rnumeroempleado = "";
        $this->rnumerolicencia = "";
        $this->rnombre = "";
        $this->rapellido = "";
    }

    public function cargar($rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido) {		
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setRnumerolicencia($rnumerolicencia);
        $this->setRnombre($rnombre);
        $this->setRapellido($rapellido);
    }

    // Getters
    public function getRnumeroempleado() {
        return $this->rnumeroempleado;
    }

    public function getRnumerolicencia() {
        return $this->rnumerolicencia;
    }

    public function getRnombre() {
        return $this->rnombre;
    }

    public function getRapellido() {
        return $this->rapellido;
    }

    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }

    // Setters
    public function setRnumeroempleado($rnumeroempleado) {
        $this->rnumeroempleado = $rnumeroempleado;
    }

    public function setRnumerolicencia($rnumerolicencia) {
        $this->rnumerolicencia = $rnumerolicencia;
    }

    public function setRnombre($rnombre) {
        $this->rnombre = $rnombre;
    }

    public function setRapellido($rapellido) {
        $this->rapellido = $rapellido;
    }

    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    // Método __toString
    public function __toString() {
        $cadena = "Número de Empleado: " . $this->getRnumeroempleado() . "\n" .
                  "Número de Licencia: " . $this->getRnumerolicencia() . "\n" .
                  "Nombre: " . $this->getRnombre() . "\n" .
                  "Apellido: " . $this->getRapellido() . "\n";
        return $cadena;
    }

   
    
/*
    public function buscar($rnumeroempleado) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado = " . $rnumeroempleado;
        $resp = false;
        if ($base->iniciar()) {
            if ($base->ejecutar($consulta)) {
                if ($row2 = $base->registro()) {
                    $this->setRnumeroempleado($row2["rnumeroempleado"]);
                    $this->setRnumerolicencia($row2["rnumerolicencia"]);
                    $this->setRnombre($row2["rnombre"]);
                    $this->setRapellido($row2["rapellido"]);
                    $resp = true;
                    
                }                
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

*/
    public function buscar($rnumeroempleado) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado = " . $rnumeroempleado;
        $resp = false;
        if ($base->iniciar()) {
            if ($base->ejecutar($consulta)) {
                if ($row2 = $base->registro()) {
                    $this->cargar(
                        $rnumeroempleado,
                        $row2['rnumerolicencia'],
                        $row2['rnombre'],
                        $row2['rapellido']
                    );
                    
                    $resp = true;
                }                
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = "") {
        $arregloResponsables = null;
        $base = new BaseDatos();
        $consultaResponsables = "SELECT * FROM responsable";
        if ($condicion != "") {
            $consultaResponsables .= " WHERE " . $condicion;
        }
        $consultaResponsables .= " ORDER BY rnumeroempleado";
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaResponsables)) {
                $arregloResponsables = array();
                while ($row2 = $base->registro()) {
                    $rnumeroempleado = $row2['rnumeroempleado'];
                    $rnumerolicencia = $row2['rnumerolicencia'];
                    $rnombre = $row2['rnombre'];
                    $rapellido = $row2['rapellido'];
                    
                    $responsable = new Responsable();
                    $responsable->cargar($rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido);
                    array_push($arregloResponsables, $responsable);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloResponsables;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable(rnumeroempleado, rnumerolicencia, rnombre, rapellido) 
                             VALUES ('" . $this->getRnumeroempleado() . "','" . $this->getRnumerolicencia() . "',
                             '" . $this->getRnombre() . "','" . $this->getRapellido() . "')";
        
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaInsertar)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE responsable SET rnumerolicencia='" . $this->getRnumerolicencia() . "',
                             rnombre='" . $this->getRnombre() . "', rapellido='" . $this->getRapellido() . "'
                             WHERE rnumeroempleado=" . $this->getRnumeroempleado();
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado=" . $this->getRnumeroempleado();
        if ($base->iniciar()) {
            
            if ($base->ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }
}