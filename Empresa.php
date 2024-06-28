<?php

class Empresa
{
    private $idempresa;  //bd
    private $enombre;    //bd
    private $edireccion; //bd
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idempresa = 0;
        $this->enombre = " ";
        $this->edireccion = " ";
    }

    public function cargar($idempresa, $enombre, $edireccion)
    {
        $this->setIdempresa($idempresa);
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

    // GETTERS

    public function getIdempresa()
    {
        return $this->idempresa;
    }
    public function getEnombre()
    {
        return $this->enombre;
    }
    public function getEdireccion()
    {
        return $this->edireccion;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    // SETTERS

    public function setIdempresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }
    public function setEnombre($enombre)
    {
        $this->enombre = $enombre;
    }
    public function setEdireccion($edireccion)
    {
        $this->edireccion = $edireccion;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __toString()
    {
        $cadena = "Id de la empresa: " . $this->getIdempresa() . "\n" .
            "Nombre de la empresa: " . $this->getEnombre() . "\n" .
            "Dirección de la empresa: " . $this->getEdireccion() . "\n";
        return $cadena;
    }

    // Buscar una empresa por idempresa
    public function buscar($idempresa)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa WHERE idempresa = " . $idempresa;
        $resp = false;
        if ($base->iniciar()) {
            if ($base->ejecutar($consulta)) {
                if ($row2 = $base->registro()) {
                    $this->cargar(
                        $idempresa,
                        $row2['enombre'],
                        $row2['edireccion']

                        
                    );
                    $resp = true;
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }


    /*
    public function buscar($idempresa)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa WHERE idempresa = " . $idempresa;
        $resp = false;
        if ($base->iniciar()) {
            if ($base->ejecutar($consulta)) {
                if ($row2 = $base->registro()) {
                    $this->setIdempresa($idempresa);
                    $this->setEnombre($row2["enombre"]);
                    $this->setEdireccion($row2["edireccion"]);
                    $this->setIdempresa($row2['idempresa']);
                    $resp = true;
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }
    
*/
    // Listar empresas con una condición opcional
    public static function listar($condicion = "")
    {
        $arregloEmpresas = null;
        $base = new BaseDatos();
        $consultaEmpresas = "SELECT * FROM empresa";
        if ($condicion != "") {
            $consultaEmpresas .= " WHERE " . $condicion;
        }
        $consultaEmpresas .= " ORDER BY idempresa";
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaEmpresas)) {
                $arregloEmpresas = array();
                while ($row2 = $base->registro()) {
                    $empresa = new Empresa();
                    $empresa->cargar(
                        $row2['idempresa'],
                        $row2['enombre'],
                        $row2['edireccion']
                    );
                    array_push($arregloEmpresas, $empresa);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloEmpresas;
    }

    // Insertar una empresa en la base de datos
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(idempresa, enombre, edireccion) 
                             VALUES ('" . $this->getIdempresa() . "','" . $this->getEnombre() . "','" . $this->getEdireccion() . "')";
        
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaInsertar)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    // Modificar una empresa en la base de datos
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET enombre='" . $this->getEnombre() . "', edireccion='" . $this->getEdireccion() . "' WHERE idempresa=" . $this->getIdempresa();
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }
// priemro verificar el registro y luego ejecuta la accion
    // Eliminar una empresa de la base de datos
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaBorra = "DELETE FROM empresa WHERE idempresa=" . $this->getIdempresa();
        if ($base->iniciar()) {   
            if ($base->ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }
}