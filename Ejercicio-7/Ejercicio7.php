<?php
session_start();

class BaseDatos {

    private $usuario = "DBUSER2022";
    private $contraseña = "DBPSWD2022";

    protected $db;
    protected $textoResultado;

    public function __construct() {
        $this->textoResultado = "<p>Pulsa los botones para interactuar con la base de datos.</p>";
    }

    public function conectar() {
        $this->db = new mysqli("localhost", $this->usuario, $this->contraseña);
        if ($this->db->connect_errno)
            $this->textoResultado = "<p>Error al conectar a la base de datos.</p>";
        else
            $this->textoResultado = "<p>Base de datos conectada.</p>";
    }

    public function desconectar() {
        $this->db->close();
    }

    public function ejecutar($query) {
        $resultado = $this->db->query($query);
        if ($resultado)
            return $resultado;
    }

    public function buscarAvion() {
        $this->conectar();
        $this->db->select_db("ejercicio7");

        $query = "SELECT * FROM Avion WHERE modelo LIKE ?";
        $preparedQuery = $this->db->prepare($query);
        if ($_REQUEST['barraBuscar'] == null) {
            $this->textoResultado = "<p>Error: falta el texto para buscar.</p>";
            return;
        }

        $param =  "%" . $_REQUEST['barraBuscar'] . "%";
        $preparedQuery->bind_param("s", $param);
        $preparedQuery->execute();
        $resultados = $preparedQuery->get_result();

        if ($resultados->num_rows > 0) {
            $texto = "";
            while ($fila = $resultados->fetch_array()) {
                $query = "SELECT nombre_aerolinea FROM Aerolinea WHERE id_aerolinea=" . $fila['id_aerolinea'];
                $aerolinea = $this->ejecutar($query)->fetch_array();

                $texto .= "<p>ID: " . $fila['id_avion'] . ", Modelo: " . $fila['modelo'] . ", Aerolínea: " . $aerolinea[0] . "</p>";
            }
            $this->textoResultado = $texto;
        } else
            $this->textoResultado = "<p>No hay resultados.</p>";
    }

    public function buscarAerolineas() {
        $this->conectar();
        $this->db->select_db("ejercicio7");

        $query = "SELECT * FROM Aerolinea WHERE pais LIKE ?";
        $preparedQuery = $this->db->prepare($query);
        if ($_REQUEST['barraBuscar'] == null) {
            $this->textoResultado = "<p>Error: falta el texto para buscar.</p>";
            return;
        }

        $param =  "%" . $_REQUEST['barraBuscar'] . "%";
        $preparedQuery->bind_param("s", $param);
        $preparedQuery->execute();
        $resultados = $preparedQuery->get_result();

        if ($resultados->num_rows > 0) {
            $texto = "";
            while ($fila = $resultados->fetch_array()) {
                $texto .= "<p>ID: " . $fila['id_aerolinea'] . ", Nombre: " . $fila['nombre_aerolinea'] .
                    ", Pais: " . $fila['pais'] . "</p>";
            }
            $this->textoResultado = $texto;
        } else
            $this->textoResultado = "<p>No hay resultados.</p>";
    }

    public function buscarAeropuertos() {
        $this->conectar();
        $this->db->select_db("ejercicio7");

        $query = "SELECT * FROM Aeropuerto WHERE ciudad LIKE ?";
        $preparedQuery = $this->db->prepare($query);
        if ($_REQUEST['barraBuscar'] == null) {
            $this->textoResultado = "<p>Error: falta el texto para buscar.</p>";
            return;
        }

        $param =  "%" . $_REQUEST['barraBuscar'] . "%";
        $preparedQuery->bind_param("s", $param);
        $preparedQuery->execute();
        $resultados = $preparedQuery->get_result();

        if ($resultados->num_rows > 0) {
            $texto = "";
            while ($fila = $resultados->fetch_array()) {
                $texto .= "<p>ID: " . $fila['id_aeropuerto'] . ", Nombre: " . $fila['nombre_aeropuerto'] .
                    ", Ciudad: " . $fila['ciudad'] . "</p>";
            }
            $this->textoResultado = $texto;
        } else
            $this->textoResultado = "<p>No hay resultados.</p>";
    }

    public function buscarVuelosSalida() {
        $this->conectar();
        $this->db->select_db("ejercicio7");

        $query = "SELECT * FROM Vuelo WHERE salida LIKE ?";
        $preparedQuery = $this->db->prepare($query);
        if ($_REQUEST['barraBuscar'] == null) {
            $this->textoResultado = "<p>Error: falta el texto para buscar.</p>";
            return;
        }

        $param =  "%" . $_REQUEST['barraBuscar'] . "%";
        $preparedQuery->bind_param("s", $param);
        $preparedQuery->execute();
        $resultados = $preparedQuery->get_result();

        if ($resultados->num_rows > 0) {
            $texto = "";
            while ($fila = $resultados->fetch_array()) {
                $texto .= "<p>ID: " . $fila['id_vuelo'] . ", ID Avión: " . $fila['id_avion'] .
                    ", Salida: " . $fila['salida'] . ", Destino: " . $fila['destino'] .
                    ", Pasajeros: " . $fila['pasajeros'] . ", Fecha: " . $fila['fecha'] . "</p>";
            }
            $this->textoResultado = $texto;
        } else
            $this->textoResultado = "<p>No hay resultados.</p>";
    }

    public function buscarVuelosDestino() {
        $this->conectar();
        $this->db->select_db("ejercicio7");

        $query = "SELECT * FROM Vuelo WHERE destino LIKE ?";
        $preparedQuery = $this->db->prepare($query);
        if ($_REQUEST['barraBuscar'] == null) {
            $this->textoResultado = "<p>Error: falta el texto para buscar.</p>";
            return;
        }

        $param =  "%" . $_REQUEST['barraBuscar'] . "%";
        $preparedQuery->bind_param("s", $param);
        $preparedQuery->execute();
        $resultados = $preparedQuery->get_result();

        if ($resultados->num_rows > 0) {
            $texto = "";
            while ($fila = $resultados->fetch_array()) {
                $texto .= "<p>ID: " . $fila['id_vuelo'] . ", ID Avión: " . $fila['id_avion'] .
                    ", Salida: " . $fila['salida'] . ", Destino: " . $fila['destino'] .
                    ", Pasajeros: " . $fila['pasajeros'] . ", Fecha: " . $fila['fecha'] . "</p>";
            }
            $this->textoResultado = $texto;
        } else
            $this->textoResultado = "<p>No hay resultados.</p>";
    }

    public function getResultado() {
        return $this->textoResultado;
    }

    public function añadirVuelo() {
        $this->conectar();
        $this->db->select_db("ejercicio7");

        // comprobar si existe el avion
        $query = "SELECT * FROM Avion WHERE id_avion=?";
        $preparedQuery = $this->db->prepare($query);
        $preparedQuery->bind_param("s", $_REQUEST['idAvion']);
        $preparedQuery->execute();
        $resultados = $preparedQuery->get_result();
        if (!$resultados->num_rows > 0) {
            $this->textoResultado = "<p>No existe el avión.</p>";
            return;
        }

        $nuevoID = uniqid();

        $query = "INSERT INTO Vuelo(id_vuelo, id_avion, salida, destino, pasajeros, fecha) 
                    VALUES(?,?,?,?,?,?)";
        $preparedQuery = $this->db->prepare($query);
        $preparedQuery->bind_param(
            "ssssis",
            $nuevoID,
            $_REQUEST['idAvion'],
            $_REQUEST['salida'],
            $_REQUEST['destino'],
            $_REQUEST['pasajeros'],
            $_REQUEST['fecha']
        );

        $resultado = $preparedQuery->execute();
        if ($resultado)
            $this->textoResultado = "<p>Vuelo insertado.</p>";
        else
            $this->textoResultado = "<p>Error al insertar el vuelo.</p>";
    }
}

if (!isset($_SESSION['db_ej7'])) {
    $db = new BaseDatos();
    $db->conectar();
    $_SESSION['db_ej7'] = $db;
}

if (count($_POST) > 0) {
    $db = $_SESSION['db_ej7'];

    if (isset($_POST['buscarAvion'])) $db->buscarAvion();
    if (isset($_POST['buscarAerolineas'])) $db->buscarAerolineas();
    if (isset($_POST['buscarAeropuertos'])) $db->buscarAeropuertos();
    if (isset($_POST['buscarVuelosSalida'])) $db->buscarVuelosSalida();
    if (isset($_POST['buscarVuelosDestino'])) $db->buscarVuelosDestino();
    if (isset($_POST['añadirVuelo'])) $db->añadirVuelo();

    $db->desconectar();
    $_SESSION['db_ej7'] = $db;
}
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />

    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Diego Villa García" />
    <meta name="description" content="Ejercicio 7 de PHP" />
    <meta name="keywords" content="ejercicio, php, base de datos" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Ejercicio 7 de PHP</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio7.css" />
</head>

<body>
    <main>
        <h1>Ejercicio 7 de PHP: Aplicación de vuelos</h1>

        <h2>Resultado de la operación</h2>
        <?php echo $_SESSION['db_ej7']->getResultado(); ?>

        <h2>Buscar</h2>
        <form action='#' method='post'>
            <label>Barra de búsqueda: <input type='text' name='barraBuscar' required></label>
            <input type='submit' value='Buscar avión por modelo' name='buscarAvion' />
            <input type='submit' value='Buscar aerolíneas por país' name='buscarAerolineas' />
            <input type='submit' value='Buscar aeropuertos en una ciudad' name='buscarAeropuertos' />
            <input type='submit' value='Buscar vuelos por salida' name='buscarVuelosSalida' />
            <input type='submit' value='Buscar vuelos por destino' name='buscarVuelosDestino' />
        </form>

        <h2>Añadir vuelo</h2>
        <form action='#' method='post'>
            <label>ID del avión: <input type='text' name='idAvion' required></label>
            <label>Salida: <input type='text' name='salida' required></label>
            <label>Destino: <input type='text' name='destino' required></label>
            <label>Pasajeros: <input type='text' name='pasajeros' required></label>
            <label>Fecha: <input type='date' name='fecha' required></label>
            <input type='submit' value='Añadir' name='añadirVuelo' />
        </form>
    </main>
</body>

</html>