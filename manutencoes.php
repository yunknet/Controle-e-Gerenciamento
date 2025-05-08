<?php
require 'conecta.php';

if (isset($_GET['excluir'])) {
    $codigo = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM manutencoes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    if ($stmt->execute()) {
        echo "<script>alert('Manutenção excluída com sucesso!');</script>";
        echo "<script>window.location.href = 'manutencoes.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir manutenção.');</script>";
    }
    $stmt->close();
}

$manutencao_editavel = null;
if (isset($_GET['editar'])) {
    $codigo = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM manutencoes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $manutencao_editavel = $resultado->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_manutencao'])) {
    $codigo = $_POST['codigo'];
    $codigo_maquina = $_POST['codigo_maquina'];
    $cnpj_empresa = $_POST['cnpj_empresa'];
    $data_manutencao = $_POST['data_manutencao'];
    $descricao = $_POST['descricao'];

    $stmt = $bancodedados->prepare("UPDATE manutencoes SET codigo_maquina=?, cnpj_empresa=?, data_manutencao=?, descricao=? WHERE codigo=?");
    $stmt->bind_param("isssi", $codigo_maquina, $cnpj_empresa, $data_manutencao, $descricao, $codigo);

    if ($stmt->execute()) {
        echo "<script>alert('Manutenção atualizada com sucesso!');</script>";
        echo "<script>window.location.href = 'manutencoes.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar manutenção.');</script>";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_manutencao'])) {
    $codigo_maquina = $_POST['codigo_maquina'];
    $cnpj_empresa = $_POST['cnpj_empresa'];
    $data_manutencao = $_POST['data_manutencao'];
    $descricao = $_POST['descricao'];

    $stmt = $bancodedados->prepare("INSERT INTO manutencoes (codigo_maquina, cnpj_empresa, data_manutencao, descricao) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $codigo_maquina, $cnpj_empresa, $data_manutencao, $descricao);

    if ($stmt->execute()) {
        echo "<script>alert('Manutenção cadastrada com sucesso!');</script>";
        echo "<script>window.location.href = 'manutencoes.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar manutenção: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenções - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"></nav>

    <div class="container mt-5">
        <h1>Manutenções</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroManutencaoModal">Registrar Manutenção</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Máquina</th>
                    <th>Empresa</th>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT m.codigo, c.nome AS maquina, f.razao_social AS empresa, m.data_manutencao, m.descricao 
                        FROM manutencoes m 
                        JOIN componentes c ON m.codigo_maquina = c.codigo 
                        JOIN fornecedores f ON m.cnpj_empresa = f.cnpj";
                $resultado = mysqli_query($bancodedados, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['codigo']}</td>";
                        echo "<td>{$row['maquina']}</td>";
                        echo "<td>{$row['empresa']}</td>";
                        echo "<td>{$row['data_manutencao']}</td>";
                        echo "<td>{$row['descricao']}</td>";
                        echo "<td>
                                <a href='manutencoes.php?editar={$row['codigo']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='manutencoes.php?excluir={$row['codigo']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir esta manutenção?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhuma manutenção encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="cadastroManutencaoModal" tabindex="-1" aria-labelledby="cadastroManutencaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroManutencaoModalLabel"><?php echo $manutencao_editavel ? 'Editar Manutenção' : 'Registrar Manutenção'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="manutencoes.php">
                        <?php if ($manutencao_editavel): ?>
                            <input type="hidden" name="editar_manutencao" value="1">
                            <input type="hidden" name="codigo" value="<?php echo $manutencao_editavel['codigo']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="cadastrar_manutencao" value="1">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="codigo_maquina" class="form-label">Máquina</label>
                            <select class="form-select" name="codigo_maquina" required>
                                <?php
                                $sql_maquinas = "SELECT codigo, nome FROM componentes WHERE tipo = 'Máquina'";
                                $result_maquinas = mysqli_query($bancodedados, $sql_maquinas);
                                while ($maquina = mysqli_fetch_assoc($result_maquinas)) {
                                    $selected = ($manutencao_editavel && $manutencao_editavel['codigo_maquina'] == $maquina['codigo']) ? 'selected' : '';
                                    echo "<option value='{$maquina['codigo']}' $selected>{$maquina['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cnpj_empresa" class="form-label">Empresa</label>
                            <select class="form-select" name="cnpj_empresa" required>
                                <?php
                                $sql_empresas = "SELECT cnpj, razao_social FROM fornecedores";
                                $result_empresas = mysqli_query($bancodedados, $sql_empresas);
                                while ($empresa = mysqli_fetch_assoc($result_empresas)) {
                                    $selected = ($manutencao_editavel && $manutencao_editavel['cnpj_empresa'] == $empresa['cnpj']) ? 'selected' : '';
                                    echo "<option value='{$empresa['cnpj']}' $selected>{$empresa['razao_social']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="data_manutencao" class="form-label">Data</label>
                            <input type="date" class="form-control" name="data_manutencao" value="<?php echo $manutencao_editavel ? $manutencao_editavel['data_manutencao'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao"><?php echo $manutencao_editavel ? $manutencao_editavel['descricao'] : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $manutencao_editavel ? 'Salvar Edição' : 'Registrar'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($manutencao_editavel): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('cadastroManutencaoModal'));
                modal.show();
            });
        <?php endif; ?>
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>


