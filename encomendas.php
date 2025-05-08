<?php
require 'conecta.php';

if (isset($_GET['excluir'])) {
    $numero = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM encomendas WHERE numero = ?");
    $stmt->bind_param("i", $numero);
    if ($stmt->execute()) {
        echo "<script>alert('Encomenda excluída com sucesso!');</script>";
        echo "<script>window.location.href = 'encomendas.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir encomenda.');</script>";
    }
    $stmt->close();
}

$encomenda_editavel = null;
if (isset($_GET['editar'])) {
    $numero = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM encomendas WHERE numero = ?");
    $stmt->bind_param("i", $numero);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $encomenda_editavel = $resultado->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_encomenda'])) {
    $numero = $_POST['numero'];
    $data_inclusao = $_POST['data_inclusao'];
    $valor_total = $_POST['valor_total'];
    $valor_desconto = $_POST['valor_desconto'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $valor_liquido = $valor_total - $valor_desconto;
    $stmt = $bancodedados->prepare("UPDATE encomendas SET data_inclusao=?, valor_total=?, valor_desconto=?, valor_liquido=?, forma_pagamento=?, codigo_cliente=? WHERE numero=?");
    $stmt->bind_param("sdddsii", $data_inclusao, $valor_total, $valor_desconto, $valor_liquido, $forma_pagamento, $codigo_cliente, $numero);
    if ($stmt->execute()) {
        echo "<script>alert('Encomenda atualizada com sucesso!');</script>";
        echo "<script>window.location.href = 'encomendas.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar encomenda.');</script>";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_encomenda'])) {
    $data_inclusao = $_POST['data_inclusao'];
    $valor_total = $_POST['valor_total'];
    $valor_desconto = $_POST['valor_desconto'] ?? 0;
    $forma_pagamento = $_POST['forma_pagamento'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $valor_liquido = $valor_total - $valor_desconto;
    $stmt = $bancodedados->prepare("INSERT INTO encomendas (data_inclusao, valor_total, valor_desconto, valor_liquido, forma_pagamento, codigo_cliente) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdddsd", $data_inclusao, $valor_total, $valor_desconto, $valor_liquido, $forma_pagamento, $codigo_cliente);
    if ($stmt->execute()) {
        echo "<script>alert('Encomenda cadastrada com sucesso!');</script>";
        echo "<script>window.location.href = 'encomendas.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar encomenda: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encomendas - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"></nav>

    <div class="container mt-5">
        <h1>Encomendas</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroEncomendaModal">Cadastrar Encomenda</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Data de Inclusão</th>
                    <th>Valor Total (R$)</th>
                    <th>Valor Desconto (R$)</th>
                    <th>Valor Líquido (R$)</th>
                    <th>Forma de Pagamento</th>
                    <th>Cliente</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT e.numero, e.data_inclusao, e.valor_total, e.valor_desconto, e.valor_liquido, e.forma_pagamento, c.razao_social AS cliente 
                        FROM encomendas e 
                        JOIN clientes c ON e.codigo_cliente = c.codigo";
                $resultado = mysqli_query($bancodedados, $sql);
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['numero']}</td>";
                        echo "<td>{$row['data_inclusao']}</td>";
                        echo "<td>R$ " . number_format($row['valor_total'], 2, ',', '.') . "</td>";
                        echo "<td>R$ " . number_format($row['valor_desconto'], 2, ',', '.') . "</td>";
                        echo "<td>R$ " . number_format($row['valor_liquido'], 2, ',', '.') . "</td>";
                        echo "<td>{$row['forma_pagamento']}</td>";
                        echo "<td>{$row['cliente']}</td>";
                        echo "<td>
                                <a href='encomendas.php?editar={$row['numero']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='encomendas.php?excluir={$row['numero']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir esta encomenda?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhuma encomenda encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="cadastroEncomendaModal" tabindex="-1" aria-labelledby="cadastroEncomendaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroEncomendaModalLabel"><?php echo $encomenda_editavel ? 'Editar Encomenda' : 'Cadastrar Encomenda'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="encomendas.php">
                        <?php if ($encomenda_editavel): ?>
                            <input type="hidden" name="editar_encomenda" value="1">
                            <input type="hidden" name="numero" value="<?php echo $encomenda_editavel['numero']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="cadastrar_encomenda" value="1">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="data_inclusao" class="form-label">Data de Inclusão</label>
                            <input type="date" class="form-control" name="data_inclusao" value="<?php echo $encomenda_editavel ? $encomenda_editavel['data_inclusao'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor_total" class="form-label">Valor Total (R$)</label>
                            <input type="number" class="form-control" name="valor_total" step="0.01" value="<?php echo $encomenda_editavel ? $encomenda_editavel['valor_total'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor_desconto" class="form-label">Valor Desconto (R$)</label>
                            <input type="number" class="form-control" name="valor_desconto" step="0.01" value="<?php echo $encomenda_editavel ? $encomenda_editavel['valor_desconto'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
                            <input type="text" class="form-control" name="forma_pagamento" value="<?php echo $encomenda_editavel ? $encomenda_editavel['forma_pagamento'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_cliente" class="form-label">Cliente</label>
                            <select class="form-select" name="codigo_cliente" required>
                                <?php
                                $sql_clientes = "SELECT codigo, razao_social FROM clientes";
                                $result_clientes = mysqli_query($bancodedados, $sql_clientes);
                                while ($cliente = mysqli_fetch_assoc($result_clientes)) {
                                    $selected = ($encomenda_editavel && $encomenda_editavel['codigo_cliente'] == $cliente['codigo']) ? 'selected' : '';
                                    echo "<option value='{$cliente['codigo']}' $selected>{$cliente['razao_social']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $encomenda_editavel ? 'Salvar Edição' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr
