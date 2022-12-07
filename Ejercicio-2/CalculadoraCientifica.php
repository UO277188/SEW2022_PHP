<?php
session_start();

class CalculadoraMilan {
    protected $operacion;
    protected $pantalla;
    protected $memoria;

    public function __construct() {
        $this->pantalla = "";
        $this->operacion = "";
        $this->memoria = 0;
    }

    public function digitos($numero) {
        $this->pantalla .= $numero;
        $this->operacion .= $numero;
    }

    public function punto() {
        if (!(str_ends_with($this->pantalla, ".") || $this->hayOperador())) {
            $this->pantalla .= ".";
            $this->operacion .= ".";
        }
    }

    public function suma() {
        $this->compruebaOperador();

        $this->pantalla .= "+";
        $this->operacion .= "+";
    }

    public function resta() {
        $this->compruebaOperador();

        $this->pantalla .= "-";
        $this->operacion .= "-";
    }

    public function multiplicacion() {
        $this->compruebaOperador();

        $this->pantalla .= "*";
        $this->operacion .= "*";
    }

    public function division() {
        $this->compruebaOperador();

        $this->pantalla .= "/";
        $this->operacion .= "/";
    }

    public function mrc() {
        $this->pantalla .= strval($this->memoria);
        $this->operacion .= strval($this->memoria);
    }

    public function mMenos() {
        $this->memoria = floatval($this->memoria) - $this->evaluar($this->operacion);
    }

    public function mMas() {
        $this->memoria = floatval($this->memoria) + $this->evaluar($this->operacion);
    }

    public function borrar() {
        $this->pantalla = "";
        $this->operacion = "";
    }

    public function igual() {
        if ($this->hayOperador()) {
            $this->pantalla = "";
            $this->operacion = "";
        } else {
            try {
                $this->pantalla = $this->evaluar($this->operacion);
                $this->operacion = $this->evaluar($this->operacion);
            } catch (Exception $e) {
                $this->pantalla = "";
                $this->operacion = "";
            }
        }
    }

    public function porcentaje() {
        $this->compruebaOperador();
        $this->pantalla .= "%";
        $this->operacion .= "/100";
    }

    public function raiz() {
        $this->compruebaOperador();
        $numbers = preg_split("/\D/", $this->pantalla);
        $lastNumber = $numbers[count($numbers) - 1];
        $numberSize = strlen($numbers[count($numbers) - 1]);

        $this->pantalla .= "√";
        $this->operacion = substr($this->operacion, $numberSize);
        $this->operacion = floatval($this->operacion) + sqrt(floatval($lastNumber));
    }

    public function masMenos() {
        if ($this->hayOperador()) {
            $this->pantalla = "";
            $this->operacion = "";
        } else {
            try {
                $res = $this->evaluar($this->operacion);
                $this->pantalla = $this->evaluar(-1 * $res);
                $this->operacion = $this->evaluar(-1 * $res);
            } catch (Exception $e) {
                $this->pantalla = "";
                $this->operacion = "";
            }
        }
    }

    public function compruebaOperador() {
        if ($this->hayOperador()) {
            $this->pantalla = substr($this->pantalla, 0, -1);
            $this->operacion = substr($this->operacion, 0, -1);
        }

        if ($this->pantalla == "") {
            $this->pantalla .= 0;
            $this->operacion .= 0;
        }
    }

    public function hayOperador() {
        $caracter = substr($this->pantalla, -1);
        return ($caracter == "+" || $caracter == "-" || $caracter == "*"
            || $caracter == "/" || $caracter == ".");
    }

    public function getPantalla() {
        return $this->pantalla;
    }

    public function mostrarError() {
        $this->operacion = "";
        $this->pantalla = "ERROR";
    }

    protected function evaluar($operacion) {
        try {
            return eval("return $operacion;");
        } catch (Exception $e) {
            $this->mostrarError();
        }
    }
}

class CalculadoraCientifica extends CalculadoraMilan {

    protected $hiperbolicas;
    protected $inversas;
    protected $grados;

    public function __construct() {
        parent::__construct();
        $this->hiperbolicas = false;
        $this->inversas = false;
        $this->grados = true;
    }

    public function deg() {
        if ($this->grados)
            $this->grados = false;
        else
            $this->grados = true;
    }

    public function hyp() {
        if ($this->inversas)
            return;

        if ($this->hiperbolicas)
            $this->hiperbolicas = false;
        else
            $this->hiperbolicas = true;
    }

    public function fe() {
        $this->pantalla .= "*10^";
        $this->operacion .= "*10**";
        $this->exponencial = true;
    }

    public function mc() {
        $this->memoria = 0;
    }

    public function ms() {
        $this->memoria = $this->evaluar($this->operacion);
    }

    public function cuadrado() {
        $result = $this->evaluar($this->operacion) * $this->evaluar($this->operacion);
        $this->operacion = $result;
        $this->pantalla = $result;
    }

    public function elevar() {
        if (parent::hayOperador()) {
            $this->pantalla = substr($this->pantalla, 0, -1);
            $this->operacion = substr($this->operacion, 0, -1);
        }
        $this->operacion .= "**";
        $this->pantalla .= "^";
    }

    public function sin() {
        if ($this->grados)
            $numero = $this->evaluar($this->operacion) * pi() / 180;
        else
            $numero = $this->evaluar($this->operacion);

        if ($this->hiperbolicas) {
            $result = sinh($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        } else if ($this->inversas) {
            $result = asin($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        } else {
            $result = sin($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        }
    }

    public function cos() {
        if ($this->grados)
            $numero = $this->evaluar($this->operacion) * pi() / 180;
        else
            $numero = $this->evaluar($this->operacion);

        if ($this->hiperbolicas) {
            $result = cosh($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        } else if ($this->inversas) {
            $result = acos($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        } else {
            $result = cos($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        }
    }

    public function tan() {
        if ($this->grados)
            $numero = $this->evaluar($this->operacion) * pi() / 180;
        else
            $numero = $this->evaluar($this->operacion);

        if ($this->hiperbolicas) {
            $result = tanh($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        } else if ($this->inversas) {
            $result = atan($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        } else {
            $result = tan($numero);
            $this->operacion = $result;
            $this->pantalla = $result;
        }
    }

    public function raiz() {
        $result = sqrt($this->evaluar($this->operacion));
        $this->operacion = $result;
        $this->pantalla = $result;
    }

    public function diezElevadoA() {
        $result = pow(10, $this->evaluar($this->operacion));
        $this->operacion = $result;
        $this->pantalla = $result;
    }

    public function log() {
        $result = log($this->evaluar($this->operacion));
        $this->operacion = $result;
        $this->pantalla = $result;
    }

    public function exp() {
        $result = exp($this->evaluar($this->operacion));
        $this->operacion = $result;
        $this->pantalla = $result;
    }

    public function modulo() {
        $this->compruebaOperador();

        $this->operacion .= "%";
        $this->pantalla .= "%";
    }

    public function inv() {
        if ($this->hiperbolicas)
            return;

        if ($this->inversas)
            $this->inversas = false;
        else
            $this->inversas = true;
    }

    public function retroceso() {
        if ($this->operacion != "") {
            $caracter = substr($this->pantalla, -1);
            $this->pantalla = substr($this->pantalla, 0, -1);
            $this->operacion = substr($this->operacion, 0, -1);

            if ($caracter == "^")
                $this->operacion = substr($this->operacion, 0, -1);
        }
    }

    public function pi() {
        $this->digitos(pi());
    }

    public function factorial() {
        $result = $this->factorialRecursivo($this->evaluar($this->operacion));
        $this->operacion = $result;
        $this->pantalla = $this->evaluar($this->operacion);
    }

    public function factorialRecursivo($n) {
        return ($n != 1) ? $n * $this->factorialRecursivo($n - 1) : 1;
    }

    public function parentesisAbrir() {
        $this->operacion += "(";
        $this->pantalla += "(";
    }

    public function parentesisCerrar() {
        $this->operacion += ")";
        $this->pantalla += ")";
    }
}

if (!isset($_SESSION['calc'])) {
    $_SESSION['calc'] = new CalculadoraCientifica();
}

if (count($_POST) > 0) {
    $calc = $_SESSION['calc'];

    if (isset($_POST['DEG'])) $calc->deg();
    if (isset($_POST['HYP'])) $calc->hyp();
    if (isset($_POST['F-E'])) $calc->fe();

    if (isset($_POST['MC'])) $calc->mc();
    if (isset($_POST['MR'])) $calc->mrc();
    if (isset($_POST['M+'])) $calc->mMas();
    if (isset($_POST['M-'])) $calc->mMenos();
    if (isset($_POST['MS'])) $calc->ms();

    if (isset($_POST['x^2'])) $calc->cuadrado();
    if (isset($_POST['x^y'])) $calc->elevar();
    if (isset($_POST['sin'])) $calc->sin();
    if (isset($_POST['cos'])) $calc->cos();
    if (isset($_POST['tan'])) $calc->tan();

    if (isset($_POST['√'])) $calc->raiz();
    if (isset($_POST['10^x'])) $calc->diezElevadoA();
    if (isset($_POST['log'])) $calc->log();
    if (isset($_POST['Exp'])) $calc->exp();
    if (isset($_POST['Mod'])) $calc->modulo();

    if (isset($_POST['↑'])) $calc->inv();
    if (isset($_POST['CE'])) $calc->borrar();
    if (isset($_POST['C'])) $calc->borrar();
    if (isset($_POST['⌫'])) $calc->retroceso();
    if (isset($_POST['÷'])) $calc->division();

    if (isset($_POST['π'])) $calc->pi();
    if (isset($_POST['7'])) $calc->digitos(7);
    if (isset($_POST['8'])) $calc->digitos(8);
    if (isset($_POST['9'])) $calc->digitos(9);
    if (isset($_POST['X'])) $calc->multiplicacion();

    if (isset($_POST['n!'])) $calc->factorial();
    if (isset($_POST['4'])) $calc->digitos(4);
    if (isset($_POST['5'])) $calc->digitos(5);
    if (isset($_POST['6'])) $calc->digitos(6);
    if (isset($_POST['-'])) $calc->resta();

    if (isset($_POST['±'])) $calc->masMenos();
    if (isset($_POST['1'])) $calc->digitos(1);
    if (isset($_POST['2'])) $calc->digitos(2);
    if (isset($_POST['3'])) $calc->digitos(3);
    if (isset($_POST['+'])) $calc->suma();

    if (isset($_POST['('])) $calc->parentesisAbrir();
    if (isset($_POST[')'])) $calc->parentesisCerrar();
    if (isset($_POST['0'])) $calc->digitos(0);
    if (isset($_POST['punto'])) $calc->punto();
    if (isset($_POST['='])) $calc->igual();

    $_SESSION['calc'] = $calc;
}
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />

    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Diego Villa García" />
    <meta name="description" content="Ejercicio 2 de PHP: Calculadora Científica" />
    <meta name="keywords" content="ejercicio, php, calculadora cientifica" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Ejercicio 2 de PHP: Calculadora Científica</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraCientifica.css" />
</head>

<body>
    <main>
        <form action='#' method='POST'>
            <label>Calculadora<input type="text" disabled value='<?php echo $_SESSION['calc']->getPantalla(); ?>' /></label>

            <input type="submit" value="DEG" name="DEG">
            <input type="submit" value="HYP" name="HYP">
            <input type="submit" value="F-E" name="F-E">

            <input type="submit" value="MC" name="MC">
            <input type="submit" value="MR" name="MR">
            <input type="submit" value="M+" name="M+">
            <input type="submit" value="M-" name="M-">
            <input type="submit" value="MS" name="MS">

            <input type="submit" value="x^2" name="x^2">
            <input type="submit" value="x^y" name="x^y">
            <input type="submit" value="sin" name="sin">
            <input type="submit" value="cos" name="cos">
            <input type="submit" value="tan" name="tan">

            <input type="submit" value="√" name="√">
            <input type="submit" value="10^x" name="10^x">
            <input type="submit" value="log" name="log">
            <input type="submit" value="Exp" name="Exp">
            <input type="submit" value="Mod" name="Mod">

            <input type="submit" value="↑" name="↑">
            <input type="submit" value="CE" name="CE">
            <input type="submit" value="C" name="C">
            <input type="submit" value="⌫" name="⌫">
            <input type="submit" value="÷" name="÷">

            <input type="submit" value="π" name="π">
            <input type="submit" value="7" name="7">
            <input type="submit" value="8" name="8">
            <input type="submit" value="9" name="9">
            <input type="submit" value="X" name="X">

            <input type="submit" value="n!" name="n!">
            <input type="submit" value="4" name="4">
            <input type="submit" value="5" name="5">
            <input type="submit" value="6" name="6">
            <input type="submit" value="-" name="-">

            <input type="submit" value="±" name="±">
            <input type="submit" value="1" name="1">
            <input type="submit" value="2" name="2">
            <input type="submit" value="3" name="3">
            <input type="submit" value="+" name="+">

            <input type="submit" value="(" name="(">
            <input type="submit" value=")" name=")">
            <input type="submit" value="0" name="0">
            <input type="submit" value="." name="punto">
            <input type="submit" value="=" name="=">
        </form>
    </main>
</body>

</html>