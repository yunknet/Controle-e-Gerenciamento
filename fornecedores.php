<?php
require 'conecta.php';

// Lógica para excluir fornecedor
if (isset($_GET['excluir'])) {
    $cnpj = $_GET['excluir'];
    $stmt = $bancodedados->prepare("DELETE FROM fornecedores WHERE cnpj = ?");
    $stmt->bind_param("s", $cnpj);
    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor excluído com sucesso!');</script>";
        echo "<script>window.location.href = 'fornecedores.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir fornecedor.');</script>";
    }
    $stmt->close();
}

// Lógica para editar fornecedor
$fornecedor_editavel = null;
if (isset($_GET['editar'])) {
    $cnpj = $_GET['editar'];
    $stmt = $bancodedados->prepare("SELECT * FROM fornecedores WHERE cnpj = ?");
    $stmt->bind_param("s", $cnpj);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fornecedor_editavel = $resultado->fetch_assoc();
    $stmt->close();
}

// Lógica para salvar edição
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_fornecedor'])) {
    $cnpj = $_POST['cnpj'];
    $razao_social = $_POST['razao_social'];
    $endereco = $_POST['endereco'];
    $telefones = $_POST['telefones'];
    $pessoa_contato = $_POST['pessoa_contato'];

    $stmt = $bancodedados->prepare("UPDATE fornecedores SET razao_social=?, endereco=?, telefones=?, pessoa_contato=? WHERE cnpj=?");
    $stmt->bind_param("sssss", $razao_social, $endereco, $telefones, $pessoa_contato, $cnpj);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor atualizado com sucesso!');</script>";
        echo "<script>window.location.href = 'fornecedores.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar fornecedor.');</script>";
    }
    $stmt->close();
}

// Lógica para cadastrar fornecedor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_fornecedor'])) {
    $cnpj = $_POST['cnpj'];
    $razao_social = $_POST['razao_social'];
    $endereco = $_POST['endereco'];
    $telefones = $_POST['telefones'];
    $pessoa_contato = $_POST['pessoa_contato'];

    $stmt = $bancodedados->prepare("INSERT INTO fornecedores (cnpj, razao_social, endereco, telefones, pessoa_contato) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $cnpj, $razao_social, $endereco, $telefones, $pessoa_contato);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor cadastrado com sucesso!');</script>";
        echo "<script>window.location.href = 'fornecedores.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar fornecedor: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores - Madeira de Lei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
<?php include 'navbar.php'; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Navbar (igual ao seu código atual) -->
    </nav>

    <div class="container mt-5">
        <h1>Fornecedores</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cadastroFornecedorModal">Cadastrar Fornecedor</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>CNPJ</th>
                    <th>Razão Social</th>
                    <th>Endereço</th>
                    <th>Telefones</th>
                    <th>Pessoa de Contato</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM fornecedores";
                $resultado = mysqli_query($bancodedados, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['cnpj']}</td>";
                        echo "<td>{$row['razao_social']}</td>";
                        echo "<td>{$row['endereco']}</td>";
                        echo "<td>{$row['telefones']}</td>";
                        echo "<td>{$row['pessoa_contato']}</td>";
                        echo "<td>
                                <a href='fornecedores.php?editar={$row['cnpj']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='fornecedores.php?excluir={$row['cnpj']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este fornecedor?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum fornecedor encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Cadastro/Edição de Fornecedor -->
    <div class="modal fade" id="cadastroFornecedorModal" tabindex="-1" aria-labelledby="cadastroFornecedorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroFornecedorModalLabel"><?php echo $fornecedor_editavel ? 'Editar Fornecedor' : 'Cadastrar Fornecedor'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="fornecedores.php">
                        <?php if ($fornecedor_editavel): ?>
                            <input type="hidden" name="editar_fornecedor" value="1">
                            <input type="hidden" name="cnpj" value="<?php echo $fornecedor_editavel['cnpj']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="cadastrar_fornecedor" value="1">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" name="cnpj" value="<?php echo $fornecedor_editavel ? $fornecedor_editavel['cnpj'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="razao_social" class="form-label">Razão Social</label>
                            <input type="text" class="form-control" name="razao_social" value="<?php echo $fornecedor_editavel ? $fornecedor_editavel['razao_social'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" name="endereco" value="<?php echo $fornecedor_editavel ? $fornecedor_editavel['endereco'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefones" class="form-label">Telefones</label>
                            <input type="text" class="form-control" name="telefones" value="<?php echo $fornecedor_editavel ? $fornecedor_editavel['telefones'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="pessoa_contato" class="form-label">Pessoa de Contato</label>
                            <input type="text" class="form-control" name="pessoa_contato" value="<?php echo $fornecedor_editavel ? $fornecedor_editavel['pessoa_contato'] : ''; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $fornecedor_editavel ? 'Salvar Edição' : 'Cadastrar'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Abre o modal automaticamente se estiver editando
        <?php if ($fornecedor_editavel): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('cadastroFornecedorModal'));
                modal.show();
            });
        <?php endif; ?>
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
