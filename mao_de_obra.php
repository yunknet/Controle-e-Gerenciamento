<?php
require 'conecta.php';

// Lógica para excluir empregado
if (isset($_GET['excluir'])) {
    $matricula = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM mao_de_obra WHERE matricula = ?");
    $stmt->bind_param("i", $matricula);
    if ($stmt->execute()) {
        echo "<script>alert('Empregado excluído com sucesso!');</script>";
        echo "<script>window.location.href = 'mao_de_obra.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir empregado.');</script>";
    }
    $stmt->close();
}

// Lógica para editar empregado
$empregado_editavel = null;
if (isset($_GET['editar'])) {
    $matricula = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM mao_de_obra WHERE matricula = ?");
    $stmt->bind_param("i", $matricula);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $empregado_editavel = $resultado->fetch_assoc();
    $stmt->close();
}

// Lógica para salvar edição
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_empregado'])) {
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $salario = $_POST['salario'];
    $data_admissao = $_POST['data_admissao'];
    $qualificacoes = $_POST['qualificacoes'];
    $matricula_gerente = $_POST['matricula_gerente'];

    $stmt = $bancodedados->prepare("UPDATE mao_de_obra SET nome=?, cargo=?, salario=?, data_admissao=?, qualificacoes=?, matricula_gerente=? WHERE matricula=?");
    $stmt->bind_param("ssdssii", $nome, $cargo, $salario, $data_admissao, $qualificacoes, $matricula_gerente, $matricula);

    if ($stmt->execute()) {
        echo "<script>alert('Empregado atualizado com sucesso!');</script>";
        echo "<script>window.location.href = 'mao_de_obra.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar empregado.');</script>";
    }
    $stmt->close();
}

// Lógica para cadastrar empregado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_empregado'])) {
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $salario = $_POST['salario'];
    $data_admissao = $_POST['data_admissao'];
    $qualificacoes = $_POST['qualificacoes'];
    $matricula_gerente = $_POST['matricula_gerente'];

    $stmt = $bancodedados->prepare("INSERT INTO mao_de_obra (nome, cargo, salario, data_admissao, qualificacoes, matricula_gerente) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssi", $nome, $cargo, $salario, $data_admissao, $qualificacoes, $matricula_gerente);

    if ($stmt->execute()) {
        echo "<script>alert('Empregado cadastrado com sucesso!');</script>";
        echo "<script>window.location.href = 'mao_de_obra.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar empregado: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mão de Obra - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
<?php include 'navbar.php'; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Navbar (igual ao seu código atual) -->
    </nav>

    <div class="container mt-5">
        <h1>Mão de Obra</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroEmpregadoModal">Cadastrar Empregado</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Salário (R$)</th>
                    <th>Data de Admissão</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM mao_de_obra";
                $resultado = mysqli_query($bancodedados, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['matricula']}</td>";
                        echo "<td>{$row['nome']}</td>";
                        echo "<td>{$row['cargo']}</td>";
                        echo "<td>R$ " . number_format($row['salario'], 2, ',', '.') . "</td>";
                        echo "<td>{$row['data_admissao']}</td>";
                        echo "<td>
                                <a href='mao_de_obra.php?editar={$row['matricula']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='mao_de_obra.php?excluir={$row['matricula']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este empregado?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum empregado encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Cadastro/Edição de Empregado -->
    <div class="modal fade" id="cadastroEmpregadoModal" tabindex="-1" aria-labelledby="cadastroEmpregadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroEmpregadoModalLabel"><?php echo $empregado_editavel ? 'Editar Empregado' : 'Cadastrar Empregado'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="mao_de_obra.php">
                        <?php if ($empregado_editavel): ?>
                            <input type="hidden" name="editar_empregado" value="1">
                            <input type="hidden" name="matricula" value="<?php echo $empregado_editavel['matricula']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="cadastrar_empregado" value="1">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo $empregado_editavel ? $empregado_editavel['nome'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="cargo" class="form-label">Cargo</label>
                            <input type="text" class="form-control" name="cargo" value="<?php echo $empregado_editavel ? $empregado_editavel['cargo'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="salario" class="form-label">Salário (R$)</label>
                            <input type="number" class="form-control" name="salario" step="0.01" value="<?php echo $empregado_editavel ? $empregado_editavel['salario'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_admissao" class="form-label">Data de Admissão</label>
                            <input type="date" class="form-control" name="data_admissao" value="<?php echo $empregado_editavel ? $empregado_editavel['data_admissao'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="qualificacoes" class="form-label">Qualificações</label>
                            <textarea class="form-control" name="qualificacoes"><?php echo $empregado_editavel ? $empregado_editavel['qualificacoes'] : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="matricula_gerente" class="form-label">Gerente</label>
                            <select class="form-select" name="matricula_gerente">
                                <option value="">Nenhum</option>
                                <?php
                                $sql_gerentes = "SELECT matricula, nome FROM mao_de_obra";
                                $result_gerentes = mysqli_query($bancodedados, $sql_gerentes);
                                while ($gerente = mysqli_fetch_assoc($result_gerentes)) {
                                    $selected = ($empregado_editavel && $empregado_editavel['matricula_gerente'] == $gerente['matricula']) ? 'selected' : '';
                                    echo "<option value='{$gerente['matricula']}' $selected>{$gerente['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $empregado_editavel ? 'Salvar Edição' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Abre o modal automaticamente se estiver editando
        <?php if ($empregado_editavel): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('cadastroEmpregadoModal'));
                modal.show();
            });
        <?php endif; ?>
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php include 'footer.php'; ?>