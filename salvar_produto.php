<?php
require 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $cor = $_POST['cor'];
    $dimensoes = $_POST['dimensoes'];
    $peso = $_POST['peso'];
    $preco = $_POST['preco'];
    $tempo_fabricacao = $_POST['tempo_fabricacao'];
    $desenho = $_POST['desenho'];

    // Prepara e executa a query para inserir o produto
    $stmt = $bancodedados->prepare("INSERT INTO produtos (nome, cor, dimensoes, peso, preco, tempo_fabricacao, desenho) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddis", $nome, $cor, $dimensoes, $peso, $preco, $tempo_fabricacao, $desenho);

    if ($stmt->execute()) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar produto: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($bancodedados);
}
?>