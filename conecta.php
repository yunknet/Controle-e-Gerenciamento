

<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$nomedobanco = "madeira_de_lei";

$bancodedados = mysqli_connect($servidor, $usuario, $senha, $nomedobanco);

if (!$bancodedados) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>


