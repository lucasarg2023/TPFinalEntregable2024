<?php


class Viaje
{

    private $idviaje;           //bd
    private $vdestino;          //bd
    private $vcantmaxpasajeros; //bd
    private $objempresa;        //bd
    private $rnumeroempleado; //bd
    private $vimporte;        //bd
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idviaje = 0;
        $this->vdestino = " ";
        $this->vcantmaxpasajeros = 0;
        $this->objempresa  = new Empresa();
        $this->rnumeroempleado = new Responsable();
        $this->vimporte = " ";
    }

    public function cargar($idviaje, $vdestino, $vcantmaxpasajeros, $objempresa, $rnumeroempleado, $vimporte)
    {
        $this->setIdviaje($idviaje);
        $this->setVdestino($vdestino);
        $this->setVcantmaxpasajeros($vcantmaxpasajeros);
        $this->setobjempresa($objempresa);
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setImporte($vimporte);
    }


    // GETTERS
    public function getIdviaje()
    {
        return $this->idviaje;
    }

    public function getVdestino()
    {
        return $this->vdestino;
    }
    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }
    public function getobjempresa()
    {
        return $this->objempresa;
    }
    public function getRnumeroempleado()
    {
        return $this->rnumeroempleado;
    }
    public function getImporte()
    {
        return $this->vimporte;
    }
    //   public function getColObjPasajeros()  {
    //       return $this->colObjPasajeros;
    //  }


    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }


    // SETTERS
    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }

    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;
    }

    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }

    public function setobjempresa($objempresa)
    {
        $this->objempresa = $objempresa;
    }

    public function setrnumeroempleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;
    }
    public function setImporte($vimporte)
    {
        $this->vimporte = $vimporte;
    }


    //   public function setColObjPasajeros($colObjPasajeros){
    //      $this->colObjPasajeros = $colObjPasajeros;
    // }

    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }


    public function __toString()
    {
        $cadena = "ID del viaje: " . $this->getIdviaje() . "\n" .
            "Destino del viaje: " . $this->getVdestino() . "\n" .
            "Cantidad máxima de pasajeros: " . $this->getVcantmaxpasajeros() . "\n" .
            "ID de la empresa: " . $this->getobjempresa()->getidempresa() . "\n" .
            "Número de empleado responsable: " . $this->getRnumeroempleado()->getRnumeroempleado() . "\n" .
            "Importe del viaje: " . $this->getImporte() . "\n";

        return $cadena;
    }


    /*

    public function buscar($idviaje) {
        $base = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje WHERE idviaje=" . $idviaje;
        $resp = false;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdviaje($idviaje);
                    $this->setVdestino($row2["vdestino"]);
                    $this->setVcantmaxpasajeros($row2["vcantmaxpasajeros"]);
                    
                    $objempresa = new Empresa();
                    $objempresa->Buscar($row2["objempresa"]);
                    $this->setobjempresa($objempresa);

                    $rnumeroempleado = new Responsable();
                    $rnumeroempleado->Buscar($row2["rnumeroempleado"]);
                    $this->setRnumeroempleado($rnumeroempleado);
                    
                    $this->setImporte($row2["importe"]);
                    
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


    public function buscar($idviaje)
    {
        $base = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje WHERE idviaje=" . $idviaje;
        $resp = false;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                if ($row2 = $base->Registro()) {
                    $objempresa = new Empresa();
                    $objempresa->Buscar($row2["idempresa"]);


                    $rnumeroempleado = new Responsable();
                    $rnumeroempleado->Buscar($row2["rnumeroempleado"]);

                    $this->cargar(
                        $row2['idviaje'],
                        $row2['vdestino'],
                        $row2['vcantmaxpasajeros'],
                        $objempresa,
                        $rnumeroempleado,
                        $row2['vimporte']
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




    public static function listar($condicion = "")    //listar viajes 
    {
        $arregloViajes = null;
        $base = new BaseDatos();
        $consultaViajes = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaViajes = $consultaViajes . ' WHERE ' . $condicion;
        }
        $consultaViajes .= " ORDER BY vdestino ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViajes)) {
                $arregloViajes = array();
                while ($row2 = $base->Registro()) {
                    $objempresa = new Empresa();
                    $objempresa->Buscar($row2["idempresa"]);

                    $rnumeroempleado = new Responsable();
                    $rnumeroempleado->Buscar($row2["rnumeroempleado"]);


                    $idviaje = $row2['idviaje'];
                    $vdestino = $row2['vdestino'];
                    $vcantmaxpasajeros = $row2['vcantmaxpasajeros'];
                    $vimporte = $row2['vimporte'];


                    $objViaje = new Viaje();
                    $objViaje->cargar($idviaje, $vdestino, $vcantmaxpasajeros, $objempresa, $rnumeroempleado, $vimporte);
                    array_push($arregloViajes, $objViaje);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloViajes;
    }








    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO viaje(vdestino,vcantmaxpasajeros,idempresa,rnumeroempleado,vimporte) 
        VALUES ('" . $this->getVdestino() . "','" . $this->getVcantmaxpasajeros() . "','" . $this->getobjempresa() . "',
        '" . $this->getRnumeroempleado() . "','" . $this->getImporte() . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaInsertar)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }





    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();

        // Obtener los ID de empresa y responsable
        $objempresa = $this->getobjempresa()->getidempresa();
        $rnumeroempleado = $this->getRnumeroempleado()->getRnumeroempleado();

        // Consulta de modificación
        $consultaModifica = "UPDATE viaje SET vdestino='" . $this->getVdestino() . "', vcantmaxpasajeros='" . $this->getVcantmaxpasajeros() . "', objempresa=" 
        . $objempresa . ", rnumeroempleado=" . $rnumeroempleado . ", vimporte='" . $this->getImporte() . "' WHERE idviaje=" . $this->getIdviaje();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaBorra = "DELETE FROM viaje WHERE idviaje=" . $this->getIdviaje();
        if ($base->Iniciar()) {  
            if ($base->Ejecutar($consultaBorra)) {
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
