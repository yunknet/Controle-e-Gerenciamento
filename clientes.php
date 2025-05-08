<?php
require 'conecta.php';

if (isset($_GET['excluir'])) {
    $codigo = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM clientes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    if ($stmt->execute()) {
        echo "<script>alert('Cliente excluído com sucesso!');</script>";
        echo "<script>window.location.href = 'clientes.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir cliente.');</script>";
    }
    $stmt->close();
}

$cliente_editavel = null;
if (isset($_GET['editar'])) {
    $codigo = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM clientes WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cliente_editavel = $resultado->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_cliente'])) {
    $codigo = $_POST['codigo'];
    $cnpj = $_POST['cnpj'];
    $razao_social = $_POST['razao_social'];
    $endereco_cobranca = $_POST['endereco_cobranca'];
    $endereco_correspondencia = $_POST['endereco_correspondencia'];
    $endereco_entrega = $_POST['endereco_entrega'];
    $telefones = $_POST['telefones'];
    $pessoa_contato = $_POST['pessoa_contato'];
    $ramo_atividade = $_POST['ramo_atividade'];

    $stmt = $bancodedados->prepare("UPDATE clientes SET cnpj=?, razao_social=?, endereco_cobranca=?, endereco_correspondencia=?, endereco_entrega=?, telefones=?, pessoa_contato=?, ramo_atividade=? WHERE codigo=?");
    $stmt->bind_param("ssssssssi", $cnpj, $razao_social, $endereco_cobranca, $endereco_correspondencia, $endereco_entrega, $telefones, $pessoa_contato, $ramo_atividade, $codigo);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente atualizado com sucesso!');</script>";
        echo "<script>window.location.href = 'clientes.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar cliente.');</script>";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_cliente'])) {
    $cnpj = $_POST['cnpj'];
    $razao_social = $_POST['razao_social'];
    $endereco_cobranca = $_POST['endereco_cobranca'];
    $endereco_correspondencia = $_POST['endereco_correspondencia'];
    $endereco_entrega = $_POST['endereco_entrega'];
    $telefones = $_POST['telefones'];
    $pessoa_contato = $_POST['pessoa_contato'];
    $ramo_atividade = $_POST['ramo_atividade'];
    $data_cadastro = date('Y-m-d');

    $stmt = $bancodedados->prepare("INSERT INTO clientes (cnpj, razao_social, endereco_cobranca, endereco_correspondencia, endereco_entrega, telefones, pessoa_contato, ramo_atividade, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $cnpj, $razao_social, $endereco_cobranca, $endereco_correspondencia, $endereco_entrega, $telefones, $pessoa_contato, $ramo_atividade, $data_cadastro);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente cadastrado com sucesso!');</script>";
        echo "<script>window.location.href = 'clientes.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar cliente: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    </nav>

    <div class="container mt-5">
        <h1>Clientes</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroClienteModal">Cadastrar Cliente</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Razão Social</th>
                    <th>CNPJ</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT codigo, razao_social, cnpj FROM clientes";
                $resultado = mysqli_query($bancodedados, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['codigo']}</td>";
                        echo "<td>{$row['razao_social']}</td>";
                        echo "<td>{$row['cnpj']}</td>";
                        echo "<td>
                                <a href='clientes.php?editar={$row['codigo']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='clientes.php?excluir={$row['codigo']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este cliente?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum cliente encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="cadastroClienteModal" tabindex="-1" aria-labelledby="cadastroClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroClienteModalLabel"><?php echo $cliente_editavel ? 'Editar Cliente' : 'Cadastrar Cliente'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="clientes.php">
                        <?php if ($cliente_editavel): ?>
                            <input type="hidden" name="editar_cliente" value="1">
                            <input type="hidden" name="codigo" value="<?php echo $cliente_editavel['codigo']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="cadastrar_cliente" value="1">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" name="cnpj" value="<?php echo $cliente_editavel ? $cliente_editavel['cnpj'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="razao_social" class="form-label">Razão Social</label>
                            <input type="text" class="form-control" name="razao_social" value="<?php echo $cliente_editavel ? $cliente_editavel['razao_social'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco_cobranca" class="form-label">Endereço de Cobrança</label>
                            <input type="text" class="form-control" name="endereco_cobranca" value="<?php echo $cliente_editavel ? $cliente_editavel['endereco_cobranca'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco_correspondencia" class="form-label">Endereço de Correspondência</label>
                            <input type="text" class="form-control" name="endereco_correspondencia" value="<?php echo $cliente_editavel ? $cliente_editavel['endereco_correspondencia'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="endereco_entrega" class="form-label">Endereço de Entrega</label>
                            <input type="text" class="form-control" name="endereco_entrega" value="<?php echo $cliente_editavel ? $cliente_editavel['endereco_entrega'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="telefones" class="form-label">Telefones</label>
                            <input type="text" class="form-control" name="telefones" value="<?php echo $cliente_editavel ? $cliente_editavel['telefones'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="pessoa_contato" class="form-label">Pessoa de Contato</label>
                            <input type="text" class="form-control" name="pessoa_contato" value="<?php echo $cliente_editavel ? $cliente_editavel['pessoa_contato'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="ramo_atividade" class="form-label">Ramo de Atividade</label>
                            <input type="text" class="form-control" name="ramo_atividade" value="<?php echo $cliente_editavel ? $cliente_editavel['ramo_atividade'] : ''; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $cliente_editavel ? 'Salvar Edição' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($cliente_editavel): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('cadastroClienteModal'));
                modal.show();
            });
        <?php endif; ?>
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
