<?php 
require 'conecta.php';

if (isset($_GET['excluir'])) {
    $codigo = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM produtos WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    if ($stmt->execute()) {
        echo "<script>alert('Produto excluído com sucesso!');</script>";
        echo "<script>window.location.href = 'produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir produto.');</script>";
    }
    $stmt->close();
}

$produto_editavel = null;
if (isset($_GET['editar'])) {
    $codigo = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM produtos WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $produto_editavel = $resultado->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_produto'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $cor = $_POST['cor'];
    $dimensoes = $_POST['dimensoes'];
    $peso = $_POST['peso'];
    $preco = $_POST['preco'];
    $tempo_fabricacao = $_POST['tempo_fabricacao'];
    $desenho = $_POST['desenho'];

    $stmt = $bancodedados->prepare("UPDATE produtos SET nome=?, cor=?, dimensoes=?, peso=?, preco=?, tempo_fabricacao=?, desenho=? WHERE codigo=?");
    $stmt->bind_param("sssddisi", $nome, $cor, $dimensoes, $peso, $preco, $tempo_fabricacao, $desenho, $codigo);

    if ($stmt->execute()) {
        echo "<script>alert('Produto atualizado com sucesso!');</script>";
        echo "<script>window.location.href = 'produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar produto.');</script>";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_produto'])) {
    $nome = $_POST['nome'];
    $cor = $_POST['cor'];
    $dimensoes = $_POST['dimensoes'];
    $peso = $_POST['peso'];
    $preco = $_POST['preco'];
    $tempo_fabricacao = $_POST['tempo_fabricacao'];
    $desenho = $_POST['desenho'];

    $stmt = $bancodedados->prepare("INSERT INTO produtos (nome, cor, dimensoes, peso, preco, tempo_fabricacao, desenho) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddis", $nome, $cor, $dimensoes, $peso, $preco, $tempo_fabricacao, $desenho);

    if ($stmt->execute()) {
        echo "<script>alert('Produto cadastrado com sucesso!');</script>";
        echo "<script>window.location.href = 'produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar produto: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"></nav>

    <div class="container mt-5">
        <h1>Produtos</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroProdutoModal">Cadastrar Produto</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Cor</th>
                    <th>Dimensões</th>
                    <th>Peso (kg)</th>
                    <th>Preço (R$)</th>
                    <th>Tempo de Fabricação (dias)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT codigo, nome, cor, dimensoes, peso, preco, tempo_fabricacao, desenho FROM produtos";
                $resultado = mysqli_query($bancodedados, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['codigo']}</td>";
                        echo "<td>{$row['nome']}</td>";
                        echo "<td>{$row['cor']}</td>";
                        echo "<td>{$row['dimensoes']}</td>";
                        echo "<td>{$row['peso']}</td>";
                        echo "<td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>";
                        echo "<td>{$row['tempo_fabricacao']}</td>";
                        echo "<td>{$row['desenho']}</td>";
                        echo "<td>
                                <a href='produtos.php?editar={$row['codigo']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='produtos.php?excluir={$row['codigo']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este produto?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Nenhum produto encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="cadastroProdutoModal" tabindex="-1" aria-labelledby="cadastroProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroProdutoModalLabel"><?php echo $produto_editavel ? 'Editar Produto' : 'Cadastrar Produto'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="produtos.php" enctype="multipart/form-data">
                        <?php if ($produto_editavel): ?>
                            <input type="hidden" name="editar_produto" value="1">
                            <input type="hidden" name="codigo" value="<?php echo $produto_editavel['codigo']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="cadastrar_produto" value="1">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo $produto_editavel ? $produto_editavel['nome'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="cor" class="form-label">Cor</label>
                            <input type="text" class="form-control" name="cor" value="<?php echo $produto_editavel ? $produto_editavel['cor'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="dimensoes" class="form-label">Dimensões</label>
                            <input type="text" class="form-control" name="dimensoes" value="<?php echo $produto_editavel ? $produto_editavel['dimensoes'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="number" class="form-control" name="peso" step="0.1" value="<?php echo $produto_editavel ? $produto_editavel['peso'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço (R$)</label>
                            <input type="number" class="form-control" name="preco" step="0.01" value="<?php echo $produto_editavel ? $produto_editavel['preco'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tempo_fabricacao" class="form-label">Tempo de Fabricação (dias)</label>
                            <input type="number" class="form-control" name="tempo_fabricacao" value="<?php echo $produto_editavel ? $produto_editavel['tempo_fabricacao'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="desenho" class="form-label">Desenho (Upload de Imagem)</label>
                            <input type="file" class="form-control" name="desenho" accept="image/*">
                        </div>      
                        <button type="submit" class="btn btn-primary"><?php echo $produto_editavel ? 'Salvar Edição' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($produto_editavel): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('cadastroProdutoModal'));
                modal.show();
            });
        <?php endif; ?>
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
