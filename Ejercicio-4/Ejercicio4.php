<?php
session_start();

class Programa {
    protected $url;
    protected $apiKey;

    protected $fecha;
    protected $divisa;
    protected $peso;

    protected $mensaje = 'Pulsa "Obtener precio" para ver el precio de las onzas de cobre.';

    public function __construct() {
        $this->apiKey = "37yax5yzxz2o79vu2qflf6i5ycz6xq4vj6nm8qpzd13xde1mi8ms4xpxkg6x";
    }

    public function obtenerPrecio() {
        $this->configurarURL();
        $this->cargarDatos();
    }

    public function configurarURL() {
        $this->url = "https://commodities-api.com/api/" . $this->fecha . "?access_key="
            . $this->apiKey . "&base=" . $this->divisa . "&symbols=XCU";
    }

    public function cargarDatos() {
        $datos = file_get_contents($this->url);
        $json = json_decode($datos);

        if ($json == null)
            $this->mensaje = "Ha habido un error al procesar la petición";
        else {
            $precio = $this->peso * $json->data->rates->XCU;
            $this->mensaje = $this->peso . " onzas valen " . $precio;
            if ($this->divisa == "EUR")
                $this->mensaje .= " €";
            else
                $this->mensaje .= " $";
        }
    }

    public function setFecha($fecha) {
        $this->fecha = date('Y-m-d', strtotime($fecha));
    }

    public function setDivisa($divisa) {
        $this->divisa = $divisa;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function getResultado() {
        return $this->mensaje;
    }
}

if (!isset($_SESSION['prog'])) {
    $_SESSION['prog'] = new Programa();
}

if (count($_POST) > 0) {
    $programa = $_SESSION['prog'];

    if (!empty($_POST['fecha']))
        $programa->setFecha($_POST['fecha']);
    else
        $programa->setFecha(date('Y-m-d'));

    if (!empty($_POST['moneda']))
        $programa->setDivisa($_POST['moneda']);
    else
        $programa->setDivisa("EUR");

    $programa->setPeso($_POST['peso']);
    $programa->obtenerPrecio();

    $_SESSION['prog'] = $programa;
}
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />

    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Diego Villa García" />
    <meta name="description" content="Ejercicio 4 de PHP: Consumo de servicio web del precio del cobre" />
    <meta name="keywords" content="ejercicio, php, consumo de servicios web, precio del cobre" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Ejercicio 4 de PHP: Consumo de servicio web del precio del cobre</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio4.css" />
</head>

<body>
    <main>
        <h1>Precio del cobre</h1>
        <p>Selecciona la fecha a consultar (por defecto es hoy), la divisa y el peso en onzas.</p>

        <form action='#' method='POST'>
            <label>Fecha<input type="date" name="fecha" step="1" min="2022-01-01" max="<?php echo date('Y-m-d') ?>"></label>

            <label for="moneda">Divisa</label>
            <select name="moneda" id="moneda">
                <option value="EUR">Euros € (EUR)</option>
                <option value="USD">Dólares estadounidenses $ (USD)</option>
            </select>

            <label>Peso del cobre en onzas<input type="number" value=1 name="peso" min=1 step=0.01></label>

            <input type="submit" value="Obtener precio" name="Obtener precio">
        </form>

        <h2>Precio:</h2>
        <p><?php echo $_SESSION['prog']->getResultado() ?></p>

    </main>
</body>

</html>