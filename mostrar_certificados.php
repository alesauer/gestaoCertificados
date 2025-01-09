<?php
require_once 'lib/databases.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php include_once("head.php");  ?>

<body>
    <?php include_once("nav.php"); ?>

    <div class="container mt-4">
        <h2>Gestão de Certificados de Cursos</h2>
        <!-- Botão Gerar Certificado -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div> <!-- Espaço à esquerda para manter alinhamento -->
            <a href="principal.php" class="btn btn-primary">Gerar Certificado</a>
        </div>
        <table 
            id="certificadosTable" 
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
                    <th data-field="nome_aluno" data-sortable="true">Nome do Aluno</th>
                    <th data-field="cpf" data-sortable="true">CPF</th>
                    <th data-field="curso" data-sortable="true">Curso</th>
                    <th data-field="data_emissao" data-sortable="true">Data de Emissão</th>
                    <th data-field="numeracao" data-sortable="true">Numeração</th>
                    <th data-field="acoes">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $db = new Database();
                $query = "
                    SELECT 
                        cg.id, 
                        cg.nome_aluno, 
                        cg.cpf, 
                        c.nome_curso AS curso, 
                        cg.data_emissao, 
                        cg.numeracao
                    FROM 
                        certificados_gerados cg
                    JOIN 
                        cursos c ON cg.curso_id = c.id
                    ORDER BY 
                        cg.data_emissao DESC
                ";

                $result = $db->query($query);

                while ($row = $result->fetch_assoc()) {
                    $id = htmlspecialchars($row['id']);
                    echo "<tr>";
                    echo "<td>{$id}</td>";
                    echo "<td>" . htmlspecialchars($row['nome_aluno']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['curso']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_emissao']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['numeracao']) . "</td>";
                    echo "<td>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editarCertificadoModal' data-id='{$id}' data-nome_aluno='{$row['nome_aluno']}' data-cpf='{$row['cpf']}' data-curso='{$row['curso']}' data-data_emissao='{$row['data_emissao']}' data-numeracao='{$row['numeracao']}'>
                            Editar
                        </button>
                        <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#excluirCertificadoModal' data-id='{$id}'>
                            Excluir
                        </button>
                        <button class='btn btn-sm btn-info' data-bs-toggle='modal' data-bs-target='#reemitirCertificadoModal' data-id='{$id}'>
                            Reemitir
                        </button>
                    </td>";
                    echo "</tr>";
                }

                $db->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modais -->
    <!-- Modal para Editar Certificado -->
    <div class="modal fade" id="editarCertificadoModal" tabindex="-1" aria-labelledby="editarCertificadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editarCertificadoForm" action="editar_certificado.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarCertificadoModalLabel">Editar Certificado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-nome-aluno" class="form-label">Nome do Aluno</label>
                            <input type="text" class="form-control" id="edit-nome-aluno" name="nome_aluno" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="edit-cpf" name="cpf" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-curso" class="form-label">Curso</label>
                            <input type="text" class="form-control" id="edit-curso" name="curso" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-data-emissao" class="form-label">Data de Emissão</label>
                            <input type="date" class="form-control" id="edit-data-emissao" name="data_emissao" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-numeracao" class="form-label">Numeração</label>
                            <input type="text" class="form-control" id="edit-numeracao" name="numeracao" required>
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

    <!-- Modal para Excluir Certificado -->
    <div class="modal fade" id="excluirCertificadoModal" tabindex="-1" aria-labelledby="excluirCertificadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="excluirCertificadoForm" action="excluir_certificado.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excluirCertificadoModalLabel">Excluir Certificado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete-id" name="id">
                        <p>Tem certeza que deseja excluir este certificado?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Reemitir Certificado -->
    <div class="modal fade" id="reemitirCertificadoModal" tabindex="-1" aria-labelledby="reemitirCertificadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="reemitirCertificadoForm" action="reemitir_certificado.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reemitirCertificadoModalLabel">Reemitir Certificado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="reemitir-id" name="id">
                        <p>Tem certeza que deseja reemitir este certificado?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">Reemitir</button>
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
        $('#editarCertificadoModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#edit-id').val(button.data('id'));
            $('#edit-nome-aluno').val(button.data('nome_aluno'));
            $('#edit-cpf').val(button.data('cpf'));
            $('#edit-curso').val(button.data('curso'));
            $('#edit-data-emissao').val(button.data('data_emissao'));
            $('#edit-numeracao').val(button.data('numeracao'));
        });

        // Passar dados para o modal de exclusão
        $('#excluirCertificadoModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#delete-id').val(button.data('id'));
        });

        // Passar dados para o modal de reemissão
        $('#reemitirCertificadoModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            $('#reemitir-id').val(button.data('id'));
        });
    </script>



</body>
</html>
