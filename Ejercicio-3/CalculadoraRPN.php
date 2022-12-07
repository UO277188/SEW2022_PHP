<?php
session_start();

class Pila {

    protected $pila;

    public function __construct() {
        $this->pila = array();
    }

    public function apilar($valor) {
        array_unshift($this->pila, $valor);
    }

    public function desapilar() {
        return array_shift($this->pila);
    }

    public function borrar() {
        $this->pila = array();
    }

    public function tamaño() {
        return count($this->pila);
    }

    public function mostrar() {
        if ($this->tamaño() > 0) {
            $stringPila = "";
            foreach ($this->pila as $i)
                $stringPila .= $i . "\n";
            return $stringPila;
        }
        return "";
    }
}

class CalculadoraRPN {
    public function __construct() {
        $this->pila = new Pila();
        $this->digito = "";
    }

    public function digitos($numero) {
        $this->digito .= $numero;
    }

    public function punto() {
        if (!str_ends_with($this->digito, "."))
            $this->digito .= ".";
    }

    public function suma() {
        if ($this->pila->tamaño() <= 1)
            return;
        try {
            $num1 = $this->pila->desapilar();
            $num2 = $this->pila->desapilar();
            $this->pila->apilar($num2 + $num1);
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function resta() {
        if ($this->pila->tamaño() <= 1)
            return;
        try {
            $num1 = $this->pila->desapilar();
            $num2 = $this->pila->desapilar();
            $this->pila->apilar($num2 - $num1);
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function multiplicacion() {
        if ($this->pila->tamaño() <= 1)
            return;
        try {
            $num1 = $this->pila->desapilar();
            $num2 = $this->pila->desapilar();
            $this->pila->apilar($num2 * $num1);
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function division() {
        if ($this->pila->tamaño() <= 1)
            return;
        try {
            $num1 = $this->pila->desapilar();
            $num2 = $this->pila->desapilar();
            $this->pila->apilar($num2 / $num1);
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function sen() {
        if ($this->pila->tamaño() == 0)
            return;
        try {
            $num = $this->pila->desapilar();
            $this->pila->apilar(sin($num));
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function cos() {
        if ($this->pila->tamaño() == 0)
            return;
        try {
            $num = $this->pila->desapilar();
            $this->pila->apilar(cos($num));
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function tan() {
        if ($this->pila->tamaño() == 0)
            return;
        try {
            $num = $this->pila->desapilar();
            $this->pila->apilar(tan($num));
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function asen() {
        if ($this->pila->tamaño() == 0)
            return;
        try {
            $num = $this->pila->desapilar();
            $this->pila->apilar(asin($num));
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function acos() {
        if ($this->pila->tamaño() == 0)
            return;
        try {
            $num = $this->pila->desapilar();
            $this->pila->apilar(acos($num));
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function atan() {
        if ($this->pila->tamaño() == 0)
            return;
        try {
            $num = $this->pila->desapilar();
            $this->pila->apilar(atan($num));
            $this->digito = "";
        } catch (error) {
            $this->borrar();
        }
    }

    public function borrar() {
        $this->digito = "";
        $this->pila->borrar();
    }

    public function retroceso() {
        if ($this->digito != "")
            $this->digito = substr($this->digito, 0, -1);
    }

    public function enter() {
        $this->pila->apilar($this->digito);
        $this->digito = "";
    }

    public function getPila() {
        return $this->pila->mostrar();
    }

    public function getEntrada() {
        return $this->digito;
    }
}


if (!isset($_SESSION['calcRPN'])) {
    $_SESSION['calcRPN'] = new CalculadoraRPN();
}

if (count($_POST) > 0) {
    $calc = $_SESSION['calcRPN'];

    if (isset($_POST['sen'])) $calc->sen();
    if (isset($_POST['cos'])) $calc->cos();
    if (isset($_POST['tan'])) $calc->tan();
    if (isset($_POST['⌫'])) $calc->retroceso();

    if (isset($_POST['asen'])) $calc->asen();
    if (isset($_POST['acos'])) $calc->acos();
    if (isset($_POST['atan'])) $calc->atan();
    if (isset($_POST['C'])) $calc->borrar();

    if (isset($_POST['7'])) $calc->digitos(7);
    if (isset($_POST['8'])) $calc->digitos(8);
    if (isset($_POST['9'])) $calc->digitos(9);
    if (isset($_POST['/'])) $calc->division();

    if (isset($_POST['4'])) $calc->digitos(4);
    if (isset($_POST['5'])) $calc->digitos(5);
    if (isset($_POST['6'])) $calc->digitos(6);
    if (isset($_POST['*'])) $calc->multiplicacion();

    if (isset($_POST['1'])) $calc->digitos(1);
    if (isset($_POST['2'])) $calc->digitos(2);
    if (isset($_POST['3'])) $calc->digitos(3);
    if (isset($_POST['-'])) $calc->resta();

    if (isset($_POST['ENTER'])) $calc->enter();
    if (isset($_POST['0'])) $calc->digitos(0);
    if (isset($_POST['punto'])) $calc->punto();
    if (isset($_POST['+'])) $calc->suma();

    $_SESSION['calcRPN'] = $calc;
}
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />

    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Diego Villa García" />
    <meta name="description" content="Ejercicio 3 de PHP: Calculadora RPN" />
    <meta name="keywords" content="ejercicio, php, calculadora rpn" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Ejercicio 3 de PHP: Calculadora RPN</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraRPN.css" />
</head>

<body>
    <main>
        <form action='#' method='POST'>
            <label>Calculadora<textarea rows="6" cols="20" readonly><?php echo $_SESSION['calcRPN']->getPila(); ?></textarea></label>
            <label>Entrada<input type="text" disabled value='<?php echo $_SESSION['calcRPN']->getEntrada(); ?>' /></label>


            <input type="submit" value="sen" name="sen">
            <input type="submit" value="cos" name="cos">
            <input type="submit" value="tan" name="tan">
            <input type="submit" value="⌫" name="⌫">

            <input type="submit" value="asen" name="asen">
            <input type="submit" value="acos" name="acos">
            <input type="submit" value="atan" name="atan">
            <input type="submit" value="C" name="C">

            <input type="submit" value="7" name="7">
            <input type="submit" value="8" name="8">
            <input type="submit" value="9" name="9">
            <input type="submit" value="/" name="/">

            <input type="submit" value="4" name="4">
            <input type="submit" value="5" name="5">
            <input type="submit" value="6" name="6">
            <input type="submit" value="*" name="*">

            <input type="submit" value="1" name="1">
            <input type="submit" value="2" name="2">
            <input type="submit" value="3" name="3">
            <input type="submit" value="-" name="-">

            <input type="submit" value="ENTER" name="ENTER">
            <input type="submit" value="0" name="0">
            <input type="submit" value="." name="punto">
            <input type="submit" value="+" name="+">
        </form>
    </main>
</body>

</html>