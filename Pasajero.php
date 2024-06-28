

<?php
class Pasajero {
    private $pdocumento;  // bd
    private $pnombre;     // bd
    private $papellido;   // bd
    private $ptelefono;   // bd
    private $idviaje;     // bd ref viaje
    private $mensajeoperacion;

    public function __construct() {
        $this->pdocumento = " ";
        $this->pnombre = " ";
        $this->papellido = " ";
        $this->ptelefono = " ";
        $this->idviaje = new Viaje();
    }

    public function cargar($pdocumento, $pnombre, $papellido, $ptelefono, $idviaje) {
        $this->setPdocumento($pdocumento);
        $this->setPnombre($pnombre);
        $this->setPapellido($papellido);
        $this->setPtelefono($ptelefono); 
        $this->setIdviaje($idviaje);
    }

    public function getPdocumento() {
        return $this->pdocumento;
    }

    public function getPnombre() {
        return $this->pnombre;
    }

    public function getPapellido() {
        return $this->papellido;
    }

    public function getPtelefono() {
        return $this->ptelefono;
    }

    public function getIdviaje() {
        return $this->idviaje;
    }

    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }

    public function setPdocumento($pdocumento) {
        $this->pdocumento = $pdocumento;
    }

    public function setPnombre($pnombre) {
        $this->pnombre = $pnombre;
    }

    public function setPapellido($papellido) {
        $this->papellido = $papellido;
    }

    public function setPtelefono($ptelefono) {
        $this->ptelefono = $ptelefono;
    }

    public function setIdviaje($idviaje) {
        $this->idviaje = $idviaje;
    }

    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

 
/*
    public function __toString() {
        $cadena = "Número de documento del pasajero: " . $this->getPdocumento() . "\n" . 
                  "Nombre del pasajero: " . $this->getPnombre() . "\n" . 
                  "Apellido del pasajero: " . $this->getPapellido() . "\n" .
                  "Número de teléfono del pasajero: " . $this->getPtelefono() . "\n" .
                  "ID de viaje: " . $this->getIdviaje()->getIdviaje() . "\n";
        return $cadena;
    }


*/

    /**
     * Recupera los datos de un pasajero por documento
     * @param int $pdocumento
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($pdocumento) {
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero WHERE pdocumento=" . $pdocumento;

        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                if ($row2 = $base->Registro()) {
                    $idviaje = new Viaje();
                    $idviaje->Buscar($row2["idviaje"]);

                    $this->cargar(
                       $pdocumento,
                        $row2["pnombre"],
                        $row2["papellido"],
                        $row2["ptelefono"],
                        $idviaje
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

    // Muestra una colección de objetos con una determinada condición
    public static function listar($condicion = "") {
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero";
        if ($condicion != "") {
            $consultaPasajero .= " WHERE " . $condicion;
        }
        $consultaPasajero .= " ORDER BY papellido";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {
                    $idviaje = new Viaje();
                    $idviaje->Buscar($row2["idviaje"]);

                    $pdocumento = $row2['pdocumento'];
                    $pnombre = $row2['pnombre'];
                    $papellido = $row2['papellido'];
                    $ptelefono = $row2['ptelefono'];
                   
                    $pasajero = new Pasajero();
                    $pasajero->cargar($pdocumento, $pnombre, $papellido, $ptelefono, $idviaje);
                    array_push($arregloPasajero, $pasajero);
                }
} else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloPasajero;
    }

    // Retorna true si la inserta o false si no la inserta
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
                VALUES ('" . $this->getPdocumento() . "','" . $this->getPnombre() . "','" . $this->getPapellido() . "','" . $this->getPtelefono() . "','" . $this->getIdviaje() . "')";
        
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

    public function modificar() {
        $resp = false; 
        $base = new BaseDatos();

        $idviaje = $this->getIdviaje()->getIdviaje();
        $consultaModifica = "UPDATE pasajero SET pnombre='" . $this->getPnombre() . "', papellido='" . $this->getPapellido() . "', ptelefono='" . $this->getPtelefono() . "', idviaje='" . $idviaje . "' WHERE pdocumento='" . $this->getPdocumento() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
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
        $consultaBorra = "DELETE FROM pasajero WHERE pdocumento='" . $this->getPdocumento() . "'";
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


/*

    public function modificar() {
        $resp = false; 
        $base = new BaseDatos();
        $consultaModifica = "UPDATE pasajero SET pnombre='" . $this->getPnombre() . "', papellido='" . $this->getPapellido() . "', ptelefono='" . $this->getPtelefono() . "', idviaje='" . $this->getIdviaje() . "' WHERE pdocumento='" . $this->getPdocumento() . "'";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

*/








}

