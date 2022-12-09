<?php
session_start();

class BaseDatos {

    private $usuario = "DBUSER2022";
    private $contraseña = "DBPSWD2022";

    protected $db;
    protected $textoResultado;

    public function __construct() {
        $this->textoResultado = "Pulsa los botones para interactuar con la base de datos.";
    }

    public function conectar() {
        $this->db = new mysqli("localhost", $this->usuario, $this->contraseña);
        if ($this->db->connect_errno)
            $this->textoResultado = "Error al conectar a la base de datos.";
        else
            $this->textoResultado = "Base de datos conectada.";
    }

    public function desconectar() {
        $this->db->close();
    }

    public function ejecutar($query) {
        $resultado = $this->db->query($query);
        if ($resultado)
            return $resultado;
    }

    public function crearDB() {
        $this->conectar();
        $this->ejecutar("CREATE DATABASE IF NOT EXISTS dbEjercicio6;");
        $this->db->select_db("dbEjercicio6");
        $this->textoResultado = "Base de datos creada.";
    }

    public function crearTabla() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");

        $this->ejecutar(" CREATE TABLE if not exists pruebasUsabilidad (
            DNI_Supervisor VARCHAR(255) NOT NULL,
            nombreSupervisor VARCHAR(255) NOT NULL,
            apellidosSupervisor VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            telefono INT NOT NULL,
            edad INT NOT NULL,
            sexo VARCHAR(255) NOT NULL,
            nivelInformatico INT NOT NULL,
            tiempoSegundos INT NOT NULL,
            pruebaCompletada BOOLEAN NOT NULL,
            cometarios VARCHAR(255) NOT NULL,
            propuestas VARCHAR(255) NOT NULL,
            valoracion INT NOT NULL
            );
        ");

        $this->textoResultado = "Tabla creada.";
    }

    public function insertar() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");
    }

    public function buscar() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");
    }

    public function modificar() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");
    }

    public function eliminar() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");
    }

    public function generarInforme() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");

        $mediaEdad = $this->ejecutar("SELECT AVG(edad) as edad FROM pruebasUsabilidad")->fetch_array()['edad'];
        $frecuenciaSexos = $this->ejecutar("SELECT COUNT(*) as frecuencias FROM pruebasUsabilidad GROUP BY sexo")->fetch_array()['frecuencias'];
        $mediaNivelInformatico = $this->ejecutar("SELECT AVG(nivelInformatico) as nivel FROM pruebasUsabilidad")->fetch_array()['nivel'];
        $mediaTiempo = $this->ejecutar("SELECT AVG(tiempoSegundos) as tiempo FROM pruebasUsabilidad")->fetch_array()['tiempo'];
        $porcentaje = $this->ejecutar("SELECT SUM(pruebaCompletada) / COUNT(*) * 100 as completada FROM pruebasUsabilidad")->fetch_array()['completada'];

        $this->textoResultado = "Edad media de los usuarios: " . $mediaEdad .
            "\n, Frecuencia de cada tipo de sexo: " . $frecuenciaSexos[0] . "Hombres, " . $frecuenciaSexos[1] . "Mujeres" .
            "\n, Valor medio del nivel informático: " . $mediaNivelInformatico .
            "\n, Tiempo medio para la tarea: " . $mediaTiempo .
            "\n, Porcentaje de usuarios que completaron la tarea: " . $porcentaje;
    }

    public function insertarCSV() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");

        $nombre = $_FILES[0]["name"];
        $ruta = '' . $nombre;

        if (!empty($nombre) && file_exists($ruta)) {
            $archivo = fopen($nombre, "r");
            while (($datos = fgetcsv($archivo, 1000, ",")) !== FALSE) {
                $query = "INSERT INTO pruebasUsabilidad(DNI_Supervisor, nombreSupervisor, apellidosSupervisor, 
                                                        email, telefono, edad, sexo, nivelInformatico, tiempoSegundos, 
                                                        pruebaCompletada, comentarios, propuestas, valoracion) 
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $preparedQuery = $this->db->prepare($query);
                $preparedQuery->bind_param(
                    "s,s,s,s,i,i,s,i,i,b,s,s,i",
                    $datos[0],
                    $datos[1],
                    $datos[2],
                    $datos[3],
                    $datos[4],
                    $datos[5],
                    $datos[6],
                    $datos[7],
                    $datos[8],
                    $datos[9],
                    $datos[10],
                    $datos[11],
                    $datos[12]
                );
                $resultado = $preparedQuery->execute();

                if (isset($resultado)) {
                    $this->textoResultado = "CSV importado.";
                } else {
                    $this->textoResultado = "Error al importar el fichero CSV.";
                }
            }

            fclose($archivo);
        }
    }

    public function descargar() {
        $this->conectar();
        $this->db->select_db("dbEjercicio6");

        $resultado = $this->ejecutar("SELECT * FROM pruebasUsabilidad")->get_result();

        if ($resultado->fetch_assoc() != null) {
            $archivo = fopen("pruebasUsabilidad.csv", "w");

            while ($fila = $resultado->fetch_assoc())
                fputcsv($archivo, $fila);

            fclose($archivo);
        }
    }

    public function getResultado() {
        return $this->textoResultado;
    }
}

if (!isset($_SESSION['db'])) {
    $db = new BaseDatos();
    $db->conectar();
    $_SESSION['db'] = $db;
}

if (count($_POST) > 0) {
    $db = $_SESSION['db'];

    if (isset($_POST['crearBD'])) $db->crearDB();
    if (isset($_POST['crearTabla'])) $db->crearTabla();
    if (isset($_POST['insertar'])) $db->insertar();
    if (isset($_POST['buscar'])) $db->buscar();
    if (isset($_POST['modificar'])) $db->modificar();
    if (isset($_POST['eliminar'])) $db->eliminar();
    if (isset($_POST['informe'])) $db->generarInforme();
    if (isset($_POST['insertarCSV'])) $db->insertarCSV();
    if (isset($_POST['descargar'])) $db->descargar();

    $db->desconectar();
    $_SESSION['db'] = $db;
}
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />

    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Diego Villa García" />
    <meta name="description" content="Ejercicio 6 de PHP" />
    <meta name="keywords" content="ejercicio, php, base de datos" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Ejercicio 6 de PHP</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio6.css" />
</head>

<body>
    <main>
        <h1>Ejercicio 6 de PHP</h1>
        <form action='#' method='post'>
            <input type='submit' value='Crear base de datos' name='crearBD' />
            <input type='submit' value='Crear una tabla' name='crearTabla' />
            <input type='submit' value='Insertar datos' name='insertar' />
            <input type='submit' value='Buscar datos' name='buscar' />
            <input type='submit' value='Modificar datos' name='modificar' />
            <input type='submit' value='Eliminar datos' name='eliminar' />
            <input type='submit' value='Generar informe' name='informe' />

            <label for='file'>Seleccionar CSV</label><br>
            <input type='file' id='file' name='file' />
            <input type='submit' value='Insertar datos del CSV' name='insertarCSV' />

            <input type='submit' value='Descargar CSV' name='descargar' />
        </form>

        <h2>Resultado</h2>
        <p><?php echo $_SESSION['db']->getResultado(); ?></p>
    </main>
</body>

</html>