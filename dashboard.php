<?php
include_once("sessao.php"); // Garantir que o usuário esteja autenticado
require_once 'lib/databases.php';

// Conectar ao banco de dados
$db = new Database();

// Obter estatísticas do sistema
$totalCertificados = $db->query("SELECT COUNT(*) AS total FROM certificados_gerados")->fetch_assoc()['total'];
$totalCursos = $db->query("SELECT COUNT(*) AS total FROM cursos")->fetch_assoc()['total'];
$totalUsuarios = $db->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$totalAtivos = $db->query("SELECT COUNT(*) AS total FROM usuarios WHERE status = 1")->fetch_assoc()['total'];

// Consultar certificados gerados para exibição na tabela
$queryCertificados = "
    SELECT c.id, c.nome_aluno, c.cpf, cr.nome_curso, c.data_emissao, c.numeracao
    FROM certificados_gerados c
    INNER JOIN cursos cr ON c.curso_id = cr.id
";
$resultCertificados = $db->query($queryCertificados);

// Fechar a conexão
$db->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php include_once("head.php");  ?>
</head>
<body>
    <?php include_once("nav.php"); ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Dashboard</h1>
        <div class="row g-4">
            <!-- Estatísticas -->
            <div class="col-md-3">
                <div class="card card-stat text-center text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Certificados</h5>
                        <p class="card-text display-6"><?php echo $totalCertificados; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat text-center text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Cursos</h5>
                        <p class="card-text display-6"><?php echo $totalCursos; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat text-center text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Usuários</h5>
                        <p class="card-text display-6"><?php echo $totalUsuarios; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat text-center text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Usuários Ativos</h5>
                        <p class="card-text display-6"><?php echo $totalAtivos; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Certificados -->
        <div class="mt-5">
            <table 
                class="table table-striped table-hover"
                data-toggle="table"
                data-search="true"
                data-pagination="true"
                data-page-list="[5, 10, 20, 50]"
                data-sortable="true"
                data-locale="pt-BR">
                <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nome_aluno" data-sortable="true">Aluno</th>
                        <th data-field="cpf" data-sortable="true">CPF</th>
                        <th data-field="curso" data-sortable="true">Curso</th>
                        <th data-field="data_emissao" data-sortable="true">Data de Emissão</th>
                        <th data-field="numeracao" data-sortable="true">Numeração</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultCertificados->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome_aluno']; ?></td>
                            <td><?php echo $row['cpf']; ?></td>
                            <td><?php echo $row['nome_curso']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['data_emissao'])); ?></td>
                            <td><?php echo $row['numeracao']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include_once("footer.php"); ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Table JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.js"></script>
</body>
</html>
