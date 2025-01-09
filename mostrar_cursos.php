<?php
require_once 'lib/databases.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<?php include_once("head.php");  ?>

<body>
    <?php include_once("nav.php"); ?>

    <div class="container mt-4">
        <h2>Lista de Cursos</h2>
        <!-- Botão Adicionar Usuário -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div> <!-- Espaço à esquerda para alinhamento -->
            <a href="cursos.php" class="btn btn-primary">Adicionar Curso</a>
        </div>
        <table 
            id="cursosTable" 
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
                    <th data-field="nome_curso" data-sortable="true">Nome do Curso</th>
                    <th data-field="professor" data-sortable="true">Professor</th>
                    <th data-field="carga_horaria" data-sortable="true">Carga Horária</th>
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
                        nome_curso, 
                        professor, 
                        carga_horaria, 
                        created_at
                    FROM 
                        cursos
                    ORDER BY 
                        created_at DESC
                ";

                $result = $db->query($query);

                while ($row = $result->fetch_assoc()) {
                    $id = htmlspecialchars($row['id']);
                    echo "<tr>";
                    echo "<td>{$id}</td>";
                    echo "<td>" . htmlspecialchars($row['nome_curso']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['professor']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['carga_horaria']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td>
                        <center>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editarCursoModal' data-id='{$id}' data-nome_curso='{$row['nome_curso']}' data-professor='{$row['professor']}' data-carga_horaria='{$row['carga_horaria']}'>
                            Editar
                        </button>
                        <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#excluirCursoModal' data-id='{$id}'>
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
    <!-- Modal para Editar Curso -->
    <div class="modal fade" id="editarCursoModal" tabindex="-1" aria-labelledby="editarCursoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editarCursoForm" action="editar_curso.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarCursoModalLabel">Editar Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-nome-curso" class="form-label">Nome do Curso</label>
                            <input type="text" class="form-control" id="edit-nome-curso" name="nome_curso" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-professor" class="form-label">Professor</label>
                            <input type="text" class="form-control" id="edit-professor" name="professor" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-carga-horaria" class="form-label">Carga Horária</label>
                            <input type="text" class="form-control" id="edit-carga-horaria" name="carga_horaria" required>
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

    <!-- Modal para Excluir Curso -->
    <div class="modal fade" id="excluirCursoModal" tabindex="-1" aria-labelledby="excluirCursoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="excluirCursoForm" action="excluir_curso.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excluirCursoModalLabel">Excluir Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete-id" name="id">
                        <p>Tem certeza que deseja excluir este curso?</p>
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
        $('#editarCursoModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#edit-id').val(button.data('id'));
            $('#edit-nome-curso').val(button.data('nome_curso'));
            $('#edit-professor').val(button.data('professor'));
            $('#edit-carga-horaria').val(button.data('carga_horaria'));
        });

        // Passar dados para o modal de exclusão
        $('#excluirCursoModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#delete-id').val(button.data('id'));
        });
    </script>
</body>
</html>
