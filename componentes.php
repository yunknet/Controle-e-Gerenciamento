<?php
require 'conecta.php';


if (isset($_GET['excluir'])) {
    $codigo = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM componentes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    if ($stmt->execute()) {
        echo "<script>alert('Componente excluído com sucesso!');</script>";
        echo "<script>window.location.href = 'componentes.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir componente.');</script>";
    }
    $stmt->close();
}


if (isset($_GET['editar'])) {
    $codigo = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM componentes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $componente_editavel = $resultado->fetch_assoc();
    $stmt->close();
} else {
    $componente_editavel = null;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_componente'])) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $quantidade_estoque = $_POST['quantidade_estoque'];
    $preco_unitario = $_POST['preco_unitario'];
    $unidade_estoque = $_POST['unidade_estoque'];
    $tempo_vida = $_POST['tempo_vida'] ?? null;
    $data_compra = $_POST['data_compra'] ?? null;
    $data_garantia = $_POST['data_garantia'] ?? null;

    $stmt = $bancodedados->prepare("UPDATE componentes SET nome=?, tipo=?, quantidade_estoque=?, preco_unitario=?, unidade_estoque=?, tempo_vida=?, data_compra=?, data_garantia=? WHERE codigo=?");
    $stmt->bind_param("ssidsissi", $nome, $tipo, $quantidade_estoque, $preco_unitario, $unidade_estoque, $tempo_vida, $data_compra, $data_garantia, $codigo);

    if ($stmt->execute()) {
        echo "<script>alert('Componente atualizado com sucesso!');</script>";
        echo "<script>window.location.href = 'componentes.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar componente.');</script>";
    }
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_componente'])) {
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $quantidade_estoque = $_POST['quantidade_estoque'];
    $preco_unitario = $_POST['preco_unitario'];
    $unidade_estoque = $_POST['unidade_estoque'];
    $tempo_vida = $_POST['tempo_vida'] ?? null;
    $data_compra = $_POST['data_compra'] ?? null;
    $data_garantia = $_POST['data_garantia'] ?? null;

    $stmt = $bancodedados->prepare("INSERT INTO componentes (nome, tipo, quantidade_estoque, preco_unitario, unidade_estoque, tempo_vida, data_compra, data_garantia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidsiss", $nome, $tipo, $quantidade_estoque, $preco_unitario, $unidade_estoque, $tempo_vida, $data_compra, $data_garantia);

    if ($stmt->execute()) {
        echo "<script>alert('Componente cadastrado com sucesso!');</script>";
        echo "<script>window.location.href = 'componentes.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar componente: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Componentes - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <p></p>
        <p></p>
        <h1>Componentes</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroComponenteModal">Cadastrar Componente</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Quantidade em Estoque</th>
                    <th>Preço Unitário (R$)</th>
                    <th>Unidade de Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM componentes";
                $resultado = mysqli_query($bancodedados, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['codigo']}</td>";
                        echo "<td>{$row['nome']}</td>";
                        echo "<td>{$row['tipo']}</td>";
                        echo "<td>{$row['quantidade_estoque']}</td>";
                        echo "<td>R$ " . number_format($row['preco_unitario'], 2, ',', '.') . "</td>";
                        echo "<td>{$row['unidade_estoque']}</td>";
                        echo "<td>
                                <a href='componentes.php?editar={$row['codigo']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='componentes.php?excluir={$row['codigo']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este componente?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum componente encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

 
    <div class="modal fade" id="cadastroComponenteModal" tabindex="-1" aria-labelledby="cadastroComponenteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroComponenteModalLabel">Cadastrar Componente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="componentes.php">
                        <input type="hidden" name="cadastrar_componente" value="1">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" required onchange="verificarTipo()">
                                <option value="Matéria-Prima">Matéria-Prima</option>
                                <option value="Material Diverso">Material Diverso</option>
                                <option value="Máquina">Máquina</option>
                                <option value="Ferramenta">Ferramenta</option>
                            </select>
                        </div>

                        <div id="dadosMaquina" style="display: none;">
                            <div class="mb-3">
                                <label for="tempo_vida" class="form-label">Tempo Médio de Vida (anos)</label>
                                <input type="number" class="form-control" name="tempo_vida">
                            </div>
                            <div class="mb-3">
                                <label for="data_compra" class="form-label">Data da Compra</label>
                                <input type="date" class="form-control" name="data_compra">
                            </div>
                            <div class="mb-3">
                                <label for="data_garantia" class="form-label">Data Fim da Garantia</label>
                                <input type="date" class="form-control" name="data_garantia">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
                            <input type="number" class="form-control" name="quantidade_estoque" required>
                        </div>
                        <div class="mb-3">
                            <label for="preco_unitario" class="form-label">Preço Unitário (R$)</label>
                            <input type="number" class="form-control" name="preco_unitario" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="unidade_estoque" class="form-label">Unidade de Estoque</label>
                            <input type="text" class="form-control" name="unidade_estoque" required>
                        </div>
                        <button type="submit" class="btn btn-success">Cadastrar Componente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editarComponenteModal" tabindex="-1" aria-labelledby="editarComponenteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarComponenteModalLabel">Editar Componente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($componente_editavel): ?>
                        <form method="POST" action="componentes.php">
                            <input type="hidden" name="editar_componente" value="1">
                            <input type="hidden" name="codigo" value="<?php echo $componente_editavel['codigo']; ?>">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="<?php echo $componente_editavel['nome']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" name="tipo" required onchange="verificarTipo()">
                                    <option value="Matéria-Prima" <?php echo ($componente_editavel['tipo'] == 'Matéria-Prima') ? 'selected' : ''; ?>>Matéria-Prima</option>
                                    <option value="Material Diverso" <?php echo ($componente_editavel['tipo'] == 'Material Diverso') ? 'selected' : ''; ?>>Material Diverso</option>
                                    <option value="Máquina" <?php echo ($componente_editavel['tipo'] == 'Máquina') ? 'selected' : ''; ?>>Máquina</option>
                                    <option value="Ferramenta" <?php echo ($componente_editavel['tipo'] == 'Ferramenta') ? 'selected' : ''; ?>>Ferramenta</option>
                                </select>
                            </div>

                            <div id="dadosMaquina" style="display: <?php echo ($componente_editavel['tipo'] == 'Máquina') ? 'block' : 'none'; ?>;">
                                <div class="mb-3">
                                    <label for="tempo_vida" class="form-label">Tempo Médio de Vida (anos)</label>
                                    <input type="number" class="form-control" name="tempo_vida" value="<?php echo $componente_editavel['tempo_vida']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="data_compra" class="form-label">Data da Compra</label>
                                    <input type="date" class="form-control" name="data_compra" value="<?php echo $componente_editavel['data_compra']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="data_garantia" class="form-label">Data Fim da Garantia</label>
                                    <input type="date" class="form-control" name="data_garantia" value="<?php echo $componente_editavel['data_garantia']; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
                                <input type="number" class="form-control" name="quantidade_estoque" value="<?php echo $componente_editavel['quantidade_estoque']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="preco_unitario" class="form-label">Preço Unitário (R$)</label>
                                <input type="number" class="form-control" name="preco_unitario" value="<?php echo $componente_editavel['preco_unitario']; ?>" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="unidade_estoque" class="form-label">Unidade de Estoque</label>
                                <input type="text" class="form-control" name="unidade_estoque" value="<?php echo $componente_editavel['unidade_estoque']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Atualizar Componente</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function verificarTipo() {
            var tipo = document.querySelector('[name="tipo"]').value;
            var dadosMaquina = document.getElementById('dadosMaquina');
            if (tipo === 'Máquina') {
                dadosMaquina.style.display = 'block';
            } else {
                dadosMaquina.style.display = 'none';
            }
        }
        
               window.onload = verificarTipo;
    </script>
</body>
</html>