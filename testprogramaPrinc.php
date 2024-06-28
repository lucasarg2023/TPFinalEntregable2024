<?php


//lucas lopez fai-3327
//grupo 31
//no se puede eliminar viaje si contiene pasajeros a bordo

// mp se puede eliminar una empresa que contenga viajes

/*
1. Ejecute el script sql provisto para crear la base de datos bdviajes y sus tablas.
2. Implementar dentro de la clase TestViajes una operación que permita ingresar, modificar
y eliminar la información de la empresa de viajes.
3. Implementar dentro de la clase TestViajes una operación que permita ingresar, modificar
y eliminar la información de un viaje, teniendo en cuenta las particularidades expuestas
en el dominio a lo largo del cuatrimestre */

include_once '../parte6/Viaje.php';
include_once '../parte6/Pasajero.php';
include_once '../parte6/Responsable.php';
include_once '../parte6/BaseDatos.php';
include_once '../parte6/Empresa.php';

//insertar pasasajeros de prueba







function seleccionarOpcion()
{


    echo "Menu de opciones: \n
        1) Buscar un pasajero (al insertar dni) 
        2) ver todos los pasajeros de un viaje (de un id)
        3) crear pasajero  
        4) modificar datos del pasajero 
        5) Eliminar pasajero del viaje
        /////\n 
        6) Buscar un viaje con sus pasajeros
        7)  ver todos los viajes 
        8) crear un viaje 
        9) modificar viaje 
        10) eliminar viaje 
        /////\n
        11) buscar empresa con sus viajes
        12) modificar empresa 
        13) ver todas la empresas 
        14) crear empresa
        15) eliminar empresa
         /////\n
        16) buscar responsable con sus viajes
        17) crear responsable
        18) Eliminar responsable;
        19) Salir \n";

    $opcionVal = solicitarNumeroEntre(1, 19);
    return $opcionVal;
}

function solicitarNumeroEntre($min, $max)
{
    //int $numero

    $numero = trim(fgets(STDIN));

    if (is_numeric($numero)) {
        $numero  = $numero * 1;
    }
    while (!(is_numeric($numero) && (($numero == (int)$numero) && ($numero >= $min && $numero <= $max)))) {
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": ";
        $numero = trim(fgets(STDIN));
        if (is_numeric($numero)) {
            $numero  = $numero * 1;
        }
    }
    return $numero;
}


do {
    $opcion = seleccionarOpcion();

    switch ($opcion) {
        case 1:
            buscarPasajeroPorDNI();
            break;
        case 2:
            verPasajerosDeViaje();
            break;
        case 3:
            crearPasajero();
            break;
        case 4:
            modificarDatosPasajero();
            break;
        case 5:
            elimnarPasajeroViaje();
            break;
        case 6:
            BuscarViajeconPasajeros();
            break;
        case 7:
            verTodosViajes();
            break;
        case 8:
            crearViaje();
            break;
        case 9:
            modificarViaje();
            break;
        case 10:
            eliminarViaje();
            break;
        case 11:
            buscarEmperesa();
            break;
        case 12:
            modificarEmpresa();
            break;
        case 13:
            verTodasLasEmpresas();
            break;
        case 14:
            crearEmpresa();
            break;
        case 15:
            eliminarEmpresa();
            break;
        case 16:
            buscarResponsable();
            break;
        case 17:
            eliminarResponsable();
            break;
        case 18:
            // Agrega aquí tu decimoctavo caso
            break;
    }
} while ($opcion != 19);


///////////1----- buscarPasajeroPorDNI/////////////
function buscarPasajeroPorDNI()
{
    echo "Buscar pasajero por DNI \n";
    echo "Ingrese el DNI del pasajero que quiere buscar: \n";
    $dniPasajero = trim(fgets(STDIN));

    $objPasajero = new Pasajero();

    if ($objPasajero->buscar($dniPasajero)) {
        echo "Pasajero encontrado:\n";
        echo $objPasajero . "\n"; // to string pasajero

        $idviaje = $objPasajero->getIdviaje()->getIdviaje();        //objviaje lo obtengo gracias al idviaje pasajero
        $objViaje = new Viaje();
        if ($objViaje->buscar($idviaje)) {
            echo "-------------------------------------------------------\n";
            echo "Los datos de su viaje son:\n";
            echo $objViaje . "\n"; // to string viaje

            $numEmpleado = $objViaje->getRnumeroempleado()->getRnumeroempleado();
            $objResp = new Responsable();                                            //objresponsable lo obtengo gracias al numEmpleado de obj viaje
            if ($objResp->buscar($numEmpleado)) {
                echo "-------------------------------------------------------\n";
                echo "Los datos del responsable son:\n";
                echo $objResp . "\n"; // to string responsable
            } else {
                echo "Responsable no encontrado\n";
            }

            $idEmpresa = $objViaje->getobjempresa()->getIdempresa();
            $objEmpresa = new Empresa();
            if ($objEmpresa->buscar($idEmpresa)) {                     //objempresa lo obtengo gracias al idEmpesa de obj viaje
                echo "-------------------------------------------------------\n";
                echo "Los datos de la empresa son:\n";
                echo $objEmpresa . "\n"; // to string empresa
            } else {
                echo "Empresa no encontrada\n";
            }
        } else {
            echo "error de conexion\n";
        }
    } else {
        echo "Pasajero no encontrado\n";
    }
}




///////////2----- verPasajerosDe un Viaje/////////////
function verPasajerosDeViaje()
{
    echo "ver todos los pasajero de un viaje \n";
    echo "Ingrese el id del viaje que quiere ver: \n";
    $idviaje = trim(fgets(STDIN));

    $objViaje = new Viaje();
    if ($objViaje->buscar($idviaje)) {
        $objPasajero = new Pasajero();
        $condicion = "idviaje = " . $idviaje;
        $colPasajeros = $objPasajero->listar($condicion);

        $mensaje = "Para el viaje con id $idviaje.\nSus pasajeros son: \n";
        foreach ($colPasajeros as $pasajero) {
            $mensaje .= "$pasajero\n";
        }
        echo $mensaje;
    } else {
        echo "Viaje no encontrado\n";
    }
}

///////////3----- crearPasajero////////////
function  crearPasajero()
{
    // 3) crear pasajero 
    echo "*************************************:\n";
    echo "Ingrese el ID del viaje al que desea agregar el pasajero: ";
    $idviaje = trim(fgets(STDIN));

    $objViaje = new Viaje();
    $respuesta = $objViaje->buscar($idviaje);

    if ($respuesta) {
        // Obtener la cantidad máxima de pasajeros permitida para el viaje
        $cantMaxPasajeros = $objViaje->getVcantmaxpasajeros();

        // Listar los pasajeros actuales del viaje
        $objPasajero = new Pasajero();
        $coleccionPasajeros = $objPasajero->listar("idviaje = " . $idviaje);

        if (count($coleccionPasajeros) < $cantMaxPasajeros) {
            echo "Ingrese el documento del pasajero: \n";
            $pdocumento = trim(fgets(STDIN));

            $respuesta = $objPasajero->buscar($pdocumento);

            if (!$respuesta) {
                echo "Ingrese el nombre del pasajero: \n";
                $pnombre = trim(fgets(STDIN));
                echo "Ingrese el apellido del pasajero: \n";
                $papellido = trim(fgets(STDIN));
                echo "Ingrese el teléfono del pasajero: \n";
                $ptelefono = trim(fgets(STDIN));

                $objPasajero->cargar($pdocumento, $pnombre, $papellido, $ptelefono, $idviaje);
                if ($objPasajero->insertar()) {
                    echo "Pasajero agregado exitosamente.\n";
                    echo "-------------------------------------------------------\n";
                } else {
                    echo "Error al agregar el pasajero.\n";
                }
            } else {
                echo "El documento del pasajero ya existe.\n";
            }
        } else {
            echo "El viaje ha alcanzado su capacidad máxima de pasajeros.\n";
        }
    } else {
        echo "Viaje no encontrado.\n";
    }
}




///////////4----- modificar datos del pasajero /////////////
function modificarDatosPasajero()
{

    echo "*************************************:\n";
    echo "Ingrese el DNI del pasajero que desea modificar: ";
    $dniPasajero = trim(fgets(STDIN));

    // Creo la instancia de Pasajero
    $objPasajero = new Pasajero();

    // Busco el pasajero a modificar
    $pasajeroBuscado = $objPasajero->buscar($dniPasajero);

    if ($pasajeroBuscado) {
        echo "Datos actuales del pasajero:\n" . $objPasajero;

        echo "Ingrese el nuevo nombre del pasajero: ";
        $nvoNomPasajero = trim(fgets(STDIN));
        echo "Ingrese el nuevo apellido del pasajero: ";
        $nvoApePasajero = trim(fgets(STDIN));
        echo "Ingrese el nuevo teléfono del pasajero: ";
        $nvoTelPasajero = trim(fgets(STDIN));
        echo "Ingrese el nuevo ID del viaje del pasajero: ";
        $nvoIdViaje = trim(fgets(STDIN));

        // Crear instancia de Viaje para verificar capacidad
        $objViaje = new Viaje();
        if ($objViaje->buscar($nvoIdViaje)) {
            $coleccionPasj = $objPasajero->listar("idviaje = " . $nvoIdViaje);
            $cantPasajeros = $objViaje->getVcantmaxpasajeros();

            if (count($coleccionPasj) < $cantPasajeros) {
                // Actualizo los valores en el objeto Pasajero
                $objPasajero->setPnombre($nvoNomPasajero);
                $objPasajero->setPapellido($nvoApePasajero);
                $objPasajero->setPtelefono($nvoTelPasajero);
                $objPasajero->setIdviaje($nvoIdViaje);
                $objPasajero->getIdviaje()->setIdviaje($nvoIdViaje);

                $respuesta = $objPasajero->modificar();
                if ($respuesta) {
                    echo "Datos del pasajero modificados con éxito.\n";
                    // Busco todos los pasajeros almacenados en la BD para verificar la modificación
                    $colPasajeros = $objPasajero->listar();
                    foreach ($colPasajeros as $unPasajero) {
                        echo $unPasajero . "\n";
                        echo "-------------------------------------------------------\n";
                    }
                } else {
                    echo $objPasajero->getmensajeoperacion();
                }
            } else {
                echo "El viaje con ID $nvoIdViaje ya está lleno. No se puede asignar el pasajero a este viaje.\n";
            }
        } else {
            echo "El ID del viaje ingresado no es válido.\n";
        }
    } else {
        echo "Pasajero no encontrado.\n";
    }
}

///////////5----- elimnarPasajeroViaje/////////////
function elimnarPasajeroViaje()
{
    //     Eliminar pasajero del viaje
    echo "*************************************:\n";
    echo "Ingrese el DNI del pasajero que desea eliminar: ";
    $dniPasajero = trim(fgets(STDIN));

    $objPasajero = new Pasajero();
    if ($objPasajero->buscar($dniPasajero)) {
        $respuesta = $objPasajero->eliminar();
        if ($respuesta == true) {
            // Busco todos los pasajeros almacenados en la BD para verificar la eliminación
            echo "Pasajero eliminado correctamente\n";
            echo "lista actualizada de todos los pasajeros : ";
            echo "-------------------------------------------------------\n";
            $colPasajeros = $objPasajero->listar();
            foreach ($colPasajeros as $unPasajero) {
                echo $unPasajero;
                echo "-------------------------------------------------------\n";
            }
        } else {
            echo $objPasajero->getMensajeoperacion();
        }
    } else {
        echo "Pasajero no encontrado\n";
    }
}

///////////6----- un viaje con sus pasajeros/////////////
function BuscarViajeconPasajeros()
{
    //6) Buscar un viaje con sus pasajeros
    echo "*************************************:\n";
    echo "Ingrese el ID del viaje para obtener todos los pasajeros: ";
    $idviaje = trim(fgets(STDIN));

    $objViaje = new Viaje();
    if ($objViaje->buscar($idviaje)) {
        $objPasajero = new Pasajero();
        $condicion = "idviaje = " . $idviaje;
        $colPasajeros = $objPasajero->listar($condicion);


        echo "*************************************:\n";
        foreach ($colPasajeros as $pasajero) {
            echo  $pasajero . "\n";
            echo "-------------------------------------------------------\n";
        }
    } else {
        echo "Viaje no encontrado\n";
    }
}

///////////7----- ver todos los viajes /////////////
function verTodosViajes()
{

    // ver todos los viajes 
    echo "Ver todos los viajes \n";

    $objViaje = new Viaje();
    $colViajes = $objViaje->listar();

    if (count($colViajes) > 0) {
        foreach ($colViajes as $viaje) {
            echo $viaje . "\n";
            echo "-------------------------------------------------------\n";
        }
    } else {
        echo "No se encontraron viajes\n";
    }
}


///////////8----- crear un viaje/////////////
function crearViaje()
{
    //crear una viaje (lo hace el responsable)
    echo "*************************************:\n";
    echo "Ingrese el nuevo ID del viaje que quiere crear: ";
    $idviaje = trim(fgets(STDIN));

    $objViaje = new Viaje();
    $respuesta = $objViaje->buscar($idviaje);

    if (!$respuesta) {
        echo "Ingrese el destino: \n";
        $destino = trim(fgets(STDIN));
        echo "Ingrese la cantidad máxima de pasajeros: \n";
        $cantMaxPasajeros = trim(fgets(STDIN));
        echo "Ingrese el ID de la empresa: \n";
        $idEmpresa = trim(fgets(STDIN));

        $objEmpresa = new Empresa();
        $respuesta = $objEmpresa->buscar($idEmpresa);

        if ($respuesta) {
            echo "Ingrese su número de empleado: \n";
            $responsable = trim(fgets(STDIN));

            $objResponsable = new Responsable();
            $respuesta = $objResponsable->buscar($responsable);

            if ($respuesta) {
                echo "Ingrese el importe: \n";
                $importe = trim(fgets(STDIN));

                $objViaje->cargar($idviaje, $destino, $cantMaxPasajeros, $idEmpresa, $responsable, $importe);
                if ($objViaje->insertar()) {
                    echo "Viaje creado exitosamente.\n";
                    echo "-------------------------------------------------------\n";
                }
            } else {
                echo "No existe el responsable.\n";
            }
        } else {
            echo "Empresa no encontrada.\n";
        }
    } else {
        echo "Este ID de viaje ya existe.\n";
    }
}


///////////9----- modificar viaje/////////////
function modificarViaje()
{
    // modificar viaje 
    echo "*************************************:\n";
    echo "Ingrese el ID del viaje para modificarlo: ";
    $idviaje = trim(fgets(STDIN));

    $objViaje = new Viaje();
    $respuesta = $objViaje->buscar($idviaje);

    if ($respuesta) {
        echo "Ingrese el nuevo destino: \n";
        $destino = trim(fgets(STDIN));
        echo "Ingrese la cantidad máxima de pasajeros: \n";
        $cantMaxPasajeros = trim(fgets(STDIN));
        echo "Ingrese el ID de la empresa: \n";
        $idEmpresa = trim(fgets(STDIN));

        $objEmpresa = new Empresa();
        $respuestaEmpresa = $objEmpresa->buscar($idEmpresa);

        if ($respuestaEmpresa) {
            echo "Ingrese su número de empleado: \n";
            $responsable = trim(fgets(STDIN));
            $objResponsable = new Responsable();
            $respuestaResponsable = $objResponsable->buscar($responsable);

            if ($respuestaResponsable) {
                echo "Ingrese el importe: \n";
                $importe = trim(fgets(STDIN));

                // Actualizo los valores en el objeto Viaje
                $objViaje->setVdestino($destino);
                $objViaje->setVcantmaxpasajeros($cantMaxPasajeros);
                $objViaje->setobjempresa($objEmpresa);
                $objViaje->setRnumeroempleado($objResponsable);
                $objViaje->setImporte($importe);

                $respuestaModificar = $objViaje->modificar();
                if ($respuestaModificar) {
                    echo "Viaje modificado exitosamente.\n";
                    echo "-------------------------------------------------------\n";

                    // Listar todos los viajes para verificar la modificación
                    $colViajes = $objViaje->listar();
                    foreach ($colViajes as $viaje) {
                        echo $viaje . "\n";
                        echo "-------------------------------------------------------\n";
                    }
                } else {
                    echo "Error al modificar el viaje.\n";
                }
            } else {
                echo "No existe el responsable con ese ID.\n";
            }
        } else {
            echo "Empresa no encontrada.\n";
        }
    } else {
        echo "El ID de viaje que selecciono no existe.\n";
    }
}



///////////10----- eliminar viaje/////////////
function eliminarViaje()
{
    // 
    echo "*************************************:\n";
    echo "Ingrese el ID del viaje para eliminarlo: ";
    $idviaje = trim(fgets(STDIN));

    $objViaje = new Viaje();
    if ($objViaje->buscar($idviaje)) {
        $objPasajero = new Pasajero();
        $consulta = "idviaje = " . $idviaje;
        $colpsjsconviajes = $objPasajero->listar($consulta);

        if (count($colpsjsconviajes) > 0) {
            echo "No se puede eliminar un viaje que contiene pasajeros.\n";
        } else {
            if ($objViaje->eliminar()) {
                echo "Eliminación con éxito.\n";
                echo "-------------------------------------------------------\n";

                // Listar todos los viajes para verificar la eliminación
                $colViajes = $objViaje->listar();
                foreach ($colViajes as $viaje) {
                    echo $viaje . "\n";
                    echo "-------------------------------------------------------\n";
                }
            } else {
                echo "Error al eliminar el viaje: " . $objViaje->getMensajeOperacion() . "\n";
            }
        }
    } else {
        echo "Viaje no encontrado.\n";
    }
}


///////////11----- buscarEmperesa/////////////
function buscarEmperesa()
{
    echo "Ver todos los viajes de una empresa\n";
    echo "Ingrese el id de la empresa que quiere ver: \n";
    $idempresa = trim(fgets(STDIN));

    $objempresa = new Empresa();
    if ($objempresa->buscar($idempresa)) {
        $objViaje = new Viaje();
        $condicion = "idempresa = " . $idempresa;
        $colviajes = $objViaje->listar($condicion);

        $mensaje = "Para la empresa con id $idempresa.\nSus viajes son:\n";
        foreach ($colviajes as $viaje) {
            $mensaje .= $viaje . "\n";
        }
        echo $mensaje;
    } else {
        echo "Empresa no encontrada\n";
    }
}

///////////12----- modificar empresa /////////////
function modificarEmpresa()
{
    echo "*************************************:\n";
    echo "Ingrese el ID de la empresa que desea modificar: ";
    $idEmpr = trim(fgets(STDIN));

    // Creo la instancia de Empresa
    $objEmpresa = new Empresa();

    // Busco la empresa a modificar
    $empresaBuscada = $objEmpresa->Buscar($idEmpr);

    if ($empresaBuscada) {
        echo "Datos actuales de la empresa:\n" . $objEmpresa;

        echo "Ingrese el nuevo nombre de la empresa: ";
        $nvoNomEmpr = trim(fgets(STDIN));
        echo "Ingrese la nueva dirección de la empresa: ";
        $nvaDire = trim(fgets(STDIN));

        // Actualizo los valores en el objeto Empresa
        $objEmpresa->setEnombre($nvoNomEmpr);
        $objEmpresa->setEdireccion($nvaDire);
        $respuesta = $objEmpresa->modificar();
        if ($respuesta == true) {
            // Busco todas las empresas almacenadas en la BD para verificar la modificación
            $colEmpresas = $objEmpresa->listar();


            // ya se modifico ahora vuelvo a mostrar toda la coleccio
            echo " \nOP MODIFICACION: Los datos fueron actualizados correctamente.\n";
            foreach ($colEmpresas as $unaEmpresa) {
                echo $unaEmpresa;
                echo "-------------------------------------------------------\n";
            }
        } else {
            echo $objEmpresa->getmensajeoperacion();
        }
    } else {
        echo "No se encontró una empresa con el ID: " . $idEmpr . "\n";
    }
}


///////////13-----  ver todos las empresas /////////////
function verTodasLasEmpresas()
{
    //
    echo "*************************************:\n";
    $objEmpresa =  new Empresa();

    //Busco todas las empresasj almacenadas en la BD
    $colEmpresas = $objEmpresa->listar("");
    foreach ($colEmpresas as $unaEmpr) {

        echo $unaEmpr .  "\n";
        echo "-------------------------------------------------------" . "\n";
    }
}





///////////14-----  crear empresa /////////////
function crearEmpresa()
{

    echo "*************************************:\n";
    echo "Ingrese el nuevo nombre de la empresa: ";
    $nvoNomEmpr = trim(fgets(STDIN));
    echo "Ingrese la nueva dirección de la empresa: ";
    $nvaDire = trim(fgets(STDIN));

    $objEmpresa = new Empresa();
    // Cargar empresa sin idempresa
    $objEmpresa->cargar(null, $nvoNomEmpr, $nvaDire);
    $respuesta = $objEmpresa->insertar();
    // Inserto el objeto Empresa en la base de datos
    if ($respuesta == true) {
        echo "La nueva empresa fue ingresada en la BD\n";
        $colEmpresas = $objEmpresa->listar("");
        foreach ($colEmpresas as $unaEmpresa) {
            echo $unaEmpresa . "\n";
            echo "-------------------------------------------------------\n";
        }
    } else {
        echo $objEmpresa->getmensajeoperacion();
    }
}


///////////15-----  eliminar empresa/////////////
function eliminarEmpresa()
{

    echo "ELIMINAR EMPRESA \n\n";
    echo "Ingrese el ID de la empresa a eliminar: ";
    $idEmpresa = trim(fgets(STDIN));

    $objEmpresa = new Empresa();

    if ($objEmpresa->Buscar($idEmpresa)) {
        $objViaje = new Viaje();
        $consulta = "idempresa = $idEmpresa";
        $colViajesConEmpresa = $objViaje->listar($consulta);

        if (count($colViajesConEmpresa) > 0) {
            echo "No se puede eliminar una empresa que constiene viajes.\n";
        } else {
            if ($objEmpresa->eliminar()) {
                echo "Empresa eliminada con éxito.\n";

                // Muestra todas las empresas restantes
                $colEmpresas = $objEmpresa->listar("");
                echo "Nueva lista de empresas actualizada:\n";
                echo "-------------------------------------------------------\n";
                foreach ($colEmpresas as $unaEmpresa) {
                    echo $unaEmpresa . "\n";
                    echo "-------------------------------------------------------\n";
                }
            } else {
                echo "Error al eliminar la empresa: " . $objEmpresa->getmensajeoperacion() . "\n";
            }
        }
    } else {
        echo "Empresa no encontrada.\n";
    }
}





///////////16-----  buscar responsable/////////////
function  buscarResponsable()
{


    echo "Ver todos los viajes de un responsable\n";
    echo "Ingrese el número de empleado del responsable que quiere ver: \n";
    $rnumeroempleado = trim(fgets(STDIN));

    $objResponsable = new Responsable();
    if ($objResponsable->buscar($rnumeroempleado)) {
        // Mostrar datos del responsable
        echo "Datos del responsable:\n";
        echo $objResponsable . "\n";
        echo "*************************************\n";

        $objViaje = new Viaje();
        $condicion = "rnumeroempleado = " . $rnumeroempleado;
        $colViajes = $objViaje->listar($condicion);

        $mensaje = "Para el responsable con número de empleado $rnumeroempleado.\nSus viajes son:\n";
        foreach ($colViajes as $viaje) {
            $mensaje .= $viaje . "\n";
        }
        echo $mensaje;
    } else {
        echo "Responsable no encontrado\n";
    }
}


///////////17-----  crear responsable/////////////
function crearResponsable()
{
    echo "Crear un nuevo responsable\n";
    echo "Ingrese el número de empleado del nuevo responsable: ";
    $rnumeroempleado = trim(fgets(STDIN));

    $objResponsable = new Responsable();
    $respuesta = $objResponsable->buscar($rnumeroempleado);

    if (!$respuesta) {
        echo "Ingrese el nombre del responsable: \n";
        $rnombre = trim(fgets(STDIN));
        echo "Ingrese el apellido del responsable: \n";
        $rapellido = trim(fgets(STDIN));
        echo "Ingrese el número de licencia del responsable: \n";
        $rnumerolicencia = trim(fgets(STDIN));

        $objResponsable->cargar($rnumeroempleado, $rnombre, $rapellido, $rnumerolicencia);

        if ($objResponsable->insertar()) {
            echo "Responsable creado exitosamente.\n";
            echo "Detalles del nuevo responsable:\n";
            echo $objResponsable . "\n";
            echo "-------------------------------------------------------\n";
        } else {
            echo "Error al crear el responsable.\n";
            echo $objResponsable->getMensajeoperacion() . "\n";
        }
    } else {
        echo "El número de empleado ya existe.\n";
    }
}

///////////18-----  eliminar resopnsable/////////////
function eliminarResponsable()
{
    echo "Eliminar un responsable\n";
    echo "Ingrese el número de empleado del responsable que desea eliminar: ";
    $rnumeroempleado = trim(fgets(STDIN));

    $objResponsable = new Responsable();
    if ($objResponsable->buscar($rnumeroempleado)) {
        // Mostrar detalles del responsable antes de eliminar
        echo "Detalles del responsable a eliminar:\n";
        echo $objResponsable . "\n";
        echo "-------------------------------------------------------\n";

        // Verificar si el responsable tiene viajes asignados
        $objViaje = new Viaje();
        $condicion = "rnumeroempleado = " . $rnumeroempleado;
        $colViajes = $objViaje->listar($condicion);

        if (count($colViajes) > 0) {
            echo "No se puede eliminar un responsable que tiene viajes asignados.\n";
        } else {
            if ($objResponsable->eliminar()) {
                echo "Responsable eliminado exitosamente.\n";
            } else {
                echo "Error al eliminar el responsable.\n";
                echo $objResponsable->getMensajeoperacion() . "\n";
            }
        }
    } else {
        echo "Responsable no encontrado.\n";
    }
}
// usar funciones en lugar de echo!!
