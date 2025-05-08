<?php
require 'conecta.php';

// Função para validar CNPJ
function validarCNPJ($cnpj) {
    // Remove caracteres não numéricos
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    // Verifica se todos os dígitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }

    // Validação do CNPJ
    if (strlen($cnpj) != 14) {
        return false;
    }

    // Cálculo dos dígitos verificadores
    $tamanho = strlen($cnpj) - 2;
    $numeros = substr($cnpj, 0, $tamanho);
    $digitos = substr($cnpj, $tamanho);
    $soma = 0;
    $pos = $tamanho - 7;

    for ($i = $tamanho; $i >= 1; $i--) {
        $soma += $numeros[$tamanho - $i] * $pos--;
        if ($pos < 2) {
            $pos = 9;
        }
    }

    $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
    if ($resultado != $digitos[0]) {
        return false;
    }

    $tamanho++;
    $numeros = substr($cnpj, 0, $tamanho);
    $soma = 0;
    $pos = $tamanho - 7;

    for ($i = $tamanho; $i >= 1; $i--) {
        $soma += $numeros[$tamanho - $i] * $pos--;
        if ($pos < 2) {
            $pos = 9;
        }
    }

    $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
    if ($resultado != $digitos[1]) {
        return false;
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $cnpj = $_POST['cnpj'];
    $razao_social = $_POST['razao_social'];
    $endereco_cobranca = $_POST['endereco_cobranca'];
    $endereco_correspondencia = $_POST['endereco_correspondencia'];
    $endereco_entrega = $_POST['endereco_entrega'];
    $telefones = $_POST['telefones'];
    $pessoa_contato = $_POST['pessoa_contato'];
    $ramo_atividade = $_POST['ramo_atividade'];
    $data_cadastro = $_POST['data_cadastro'];

    // Valida o CNPJ
    if (!validarCNPJ($cnpj)) {
        die("CNPJ inválido.");
    }

    // Prepara e executa a query para inserir o cliente
    $stmt = $bancodedados->prepare("INSERT INTO clientes (cnpj, razao_social, endereco_cobranca, endereco_correspondencia, endereco_entrega, telefones, pessoa_contato, ramo_atividade, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $cnpj, $razao_social, $endereco_cobranca, $endereco_correspondencia, $endereco_entrega, $telefones, $pessoa_contato, $ramo_atividade, $data_cadastro);

    if ($stmt->execute()) {
        echo "Cliente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar cliente: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($bancodedados);
}
?>