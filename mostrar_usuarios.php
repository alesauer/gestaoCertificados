<?php
require_once 'lib/databases.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php include_once("head.php");  ?>

<body>
    <?php include_once("nav.php"); ?>

    <div class="container mt-4">
        <h2>Lista de Usuários</h2>
        <!-- Botão Adicionar Usuário -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div> <!-- Espaço à esquerda para alinhamento -->
            <a href="criar_usuarios.php" class="btn btn-primary">Adicionar Usuário</a>
        </div>
        <table 
            id="usuariosTable" 
            class="table table-striped"
            data-toggle="table"
            data-search="true"
            data-pagination="true"
            data-page-list="[5, 10, 25, 50, 100]"
            data-sortable="true"
            data-show-columns="true">
            <thead>
                <tr>
                    <th data-field="id" data-sortable="true">#</th>
                    <th data-field="nome" data-sortable="true">Nome</th>
                    <th data-field="email" data-sortable="true">E-mail</th>
                    <th data-field="perfil" data-sortable="true">Perfil</th>
                    <th data-field="status" data-sortable="true">Status</th>
                    <th data-field="created_at" data-sortable="true">Data de Criação</th>
                    <th data-field="acoes">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $db = new Database();
                $query = "
                    SELECT 
                        id, 
                        nome, 
                        email, 
                        perfil, 
                        status, 
                        created_at
                    FROM 
                        usuarios
                    ORDER BY 
                        created_at DESC
                ";

                $result = $db->query($query);

                while ($row = $result->fetch_assoc()) {
                    $id = htmlspecialchars($row['id']);
                    echo "<tr>";
                    echo "<td>{$id}</td>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars(ucfirst($row['perfil'])) . "</td>";
                    echo "<td>" . ($row['status'] == 1 ? 'Ativo' : 'Inativo') . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td>
                        <center>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editarUsuarioModal' data-id='{$id}' data-nome='{$row['nome']}' data-email='{$row['email']}' data-perfil='{$row['perfil']}' data-status='{$row['status']}'>
                            Editar
                        </button>
                        <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#excluirUsuarioModal' data-id='{$id}'>
                            Excluir
                        </button>
                        </center>
                    </td>";
                    echo "</tr>";
                }

                $db->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modais -->
    <!-- Modal para Editar Usuário -->
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editarUsuarioForm" action="editar_usuario.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="edit-nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-perfil" class="form-label">Perfil</label>
                            <select class="form-select" id="edit-perfil" name="perfil" required>
                                <option value="admin">Administrador</option>
                                <option value="usuario">Usuário</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-status" class="form-label">Status</label>
                            <select class="form-select" id="edit-status" name="status" required>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Excluir Usuário -->
    <div class="modal fade" id="excluirUsuarioModal" tabindex="-1" aria-labelledby="excluirUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="excluirUsuarioForm" action="excluir_usuario.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excluirUsuarioModalLabel">Excluir Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete-id" name="id">
                        <p>Tem certeza que deseja excluir este usuário?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once("footer.php"); ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Table JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.js"></script>

    <script>
        // Passar dados para o modal de edição
        $('#editarUsuarioModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#edit-id').val(button.data('id'));
            $('#edit-nome').val(button.data('nome'));
            $('#edit-email').val(button.data('email'));
            $('#edit-perfil').val(button.data('perfil'));
            $('#edit-status').val(button.data('status'));
        });

        // Passar dados para o modal de exclusão
        $('#excluirUsuarioModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#delete-id').val(button.data('id'));
        });
    </script>
</body>
</html>
