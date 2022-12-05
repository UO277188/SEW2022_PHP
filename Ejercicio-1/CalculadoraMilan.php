<!DOCTYPE HTML>
<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />

    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Diego Villa García" />
    <meta name="description" content="Ejercicio 1 de PHP: Calculadora Milan" />
    <meta name="keywords" content="ejercicio, php, calculadora" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Ejercicio 1 de PHP: Calculadora Milan</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraMilan.css" />
</head>

<body>
    <?php
        class CalculadoraMilan {
            protected $operacion;
            protected $pantalla;
            protected $memoria;

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
                $this->pantalla = $this->pantalla + $this->memoria;
                $this->operacion = $this->operacion + $this->memoria;
            }

            public function mMenos() {
                $this->memoria = $this->memoria - eval($this->operacion);
            }

            public function mMas() {
                $this->memoria = $this->memoria + eval($this->operacion);
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
                        $this->pantalla = eval($this->operacion);
                        $this->operacion = eval($this->operacion);
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
                $this->operacion = $this->operacion + sqrt($lastNumber);
            }

            public function masMenos() {
                if ($this->hayOperador()) {
                    $this->pantalla = "";
                    $this->operacion = "";
                } else {
                    try {
                        $this->pantalla = eval(-eval($this->operacion));
                        $this->operacion = eval(-eval($this->operacion));
                    } catch (Exception $e) {
                        $this->pantalla = "";
                        $this->operacion = "";
                    }
                }
            }

            public function compruebaOperador() {
                if ($this->hayOperador()) {
                    $this->pantalla = substr($this->pantalla, -1);
                    $this->operacion = substr($this->operacion, -1);
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

            public function getPantalla(){
                print_r($this->pantalla);
            }
        }

        session_start();
        
        if(!isset($_SESSION['calc'])) {
            $_SESSION['calc'] = new CalculadoraMilan();
        }

        $calc = $_SESSION['calc'];

        if (count($_POST)>0) {   
            if(isset($_POST['C'])) $calc->borrar();
            if(isset($_POST['CE'])) $calc->borrar();
            if(isset($_POST['+/-'])) $calc->masMenos();
            if(isset($_POST['√'])) $calc->raiz();
            if(isset($_POST['%'])) $calc->porcentaje();

            if(isset($_POST['7'])) $calc->digitos(7);
            if(isset($_POST['8'])) $calc->digitos(8);
            if(isset($_POST['9'])) $calc->digitos(9);
            if(isset($_POST['*'])) $calc->multiplicacion();
            if(isset($_POST['÷'])) $calc->division();

            if(isset($_POST['4'])) $calc->digitos(4);
            if(isset($_POST['5'])) $calc->digitos(5);
            if(isset($_POST['6'])) $calc->digitos(6);
            if(isset($_POST['-'])) $calc->resta();
            if(isset($_POST['MRC'])) $calc->mrc();

            if(isset($_POST['1'])) $calc->digitos(1);
            if(isset($_POST['2'])) $calc->digitos(2);
            if(isset($_POST['3'])) $calc->digitos(3);
            if(isset($_POST['+'])) $calc->suma();
            if(isset($_POST['M-'])) $calc->mMenos();
            
            if(isset($_POST['0'])) $calc->digitos(0);
            if(isset($_POST['.'])) $calc->punto();
            if(isset($_POST['='])) $calc->igual();
            if(isset($_POST['M+'])) $calc->mMas();
        }

        $_SESSION['calc'] = $calc;
    ?>
    <main>
        <form action="#" method="POST">
            <label>Calculadora<input type="text" disabled value="<?php $calc->getPantalla(); ?>"/></label>

            <input type="button" value="C" name="C" />
            <input type="button" value="CE" name="CE" />
            <input type="button" value="+/-" name="+/-" />
            <input type="button" value="√" name="√" />
            <input type="button" value="%" name="%" />

            <input type="button" value="7" name="7" />
            <input type="button" value="8" name="8" />
            <input type="button" value="9" name="9" />
            <input type="button" value="*" name="*" />
            <input type="button" value="÷" name="÷" />

            <input type="button" value="4" name="4" />
            <input type="button" value="5" name="5" />
            <input type="button" value="6" name="6" />
            <input type="button" value="-" name="-" />
            <input type="button" value="MRC" name="MRC" />

            <input type="button" value="1" name="1" />
            <input type="button" value="2" name="2" />
            <input type="button" value="3" name="3" />
            <input type="button" value="+" name="+" />
            <input type="button" value="M-" name="M-" />

            <input type="button" value="0" name="0" />
            <input type="button" value="." name="." />
            <input type="button" value="=" name="=" />
            <input type="button" value="M+" name="M+" />
        </form>
    </main>
</body>

</html>