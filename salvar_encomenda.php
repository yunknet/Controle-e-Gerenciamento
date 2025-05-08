<?php
require 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo_cliente = $_POST['codigo_cliente'];
    $data_inclusao = $_POST['data_inclusao'];
    $valor_desconto = $_POST['valor_desconto'] ?? 0;
    $forma_pagamento = $_POST['forma_pagamento'];
    $quantidade_parcelas = $_POST['quantidade_parcelas'];
    $produtos = $_POST['produtos'];

    $valor_total = 0;
    foreach ($produtos as $produto) {
        $codigo_produto = $produto['codigo'];
        $quantidade = $produto['quantidade'];

        $stmt = $bancodedados->prepare("SELECT preco FROM produtos WHERE codigo = ?");
        $stmt->bind_param("i", $codigo_produto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produto = $resultado->fetch_assoc();

        $valor_total += $produto['preco'] * $quantidade;
    }

    $valor_liquido = $valor_total - $valor_desconto;

    $stmt = $bancodedados->prepare("INSERT INTO encomendas (data_inclusao, valor_total, valor_desconto, valor_liquido, forma_pagamento, quantidade_parcelas, codigo_cliente) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddddsi", $data_inclusao, $valor_total, $valor_desconto, $valor_liquido, $forma_pagamento, $quantidade_parcelas, $codigo_cliente);

    if ($stmt->execute()) {
        echo "Encomenda cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar encomenda: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($bancodedados);
}
?>
