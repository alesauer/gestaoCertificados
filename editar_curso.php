<?php
require_once 'lib/databases.php';

// Função para limpar e validar os dados de entrada
function limparEntrada($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar e limpar os dados enviados
    $id = isset($_POST['id']) ? (int)limparEntrada($_POST['id']) : 0;
    $nome_curso = isset($_POST['nome_curso']) ? limparEntrada($_POST['nome_curso']) : '';
    $professor = isset($_POST['professor']) ? limparEntrada($_POST['professor']) : '';
    $carga_horaria = isset($_POST['carga_horaria']) ? limparEntrada($_POST['carga_horaria']) : '';

    // Validação básica dos campos
    if ($id <= 0 || empty($nome_curso) || empty($professor) || empty($carga_horaria)) {
        die("Todos os campos obrigatórios devem ser preenchidos.");
    }

    // Conectar ao banco de dados
    $db = new Database();

    // Escapar os dados para evitar SQL Injection
    $idEscapado = $db->escapeString($id);
    $nomeCursoEscapado = $db->escapeString($nome_curso);
    $professorEscapado = $db->escapeString($professor);
    $cargaHorariaEscapada = $db->escapeString($carga_horaria);

    // Atualizar os dados do curso
    $query = "
        UPDATE cursos
        SET 
            nome_curso = '$nomeCursoEscapado',
            professor = '$professorEscapado',
            carga_horaria = '$cargaHorariaEscapada'
        WHERE id = $idEscapado
    ";

    if ($db->query($query)) {
        $db->close();
        // Exibir mensagem de sucesso
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Curso Atualizado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <div class="alert alert-success">
                    <strong>Curso atualizado com sucesso!</strong>
                    <a href="mostrar_cursos.php" class="btn btn-primary btn-sm">Voltar</a>
                </div>
            </div>
        </body>
        </html>
        HTML;
    } else {
        $db->close();
        // Exibir mensagem de erro
        die("Erro ao atualizar o curso. Por favor, tente novamente.");
    }
} else {
    // Se o formulário não foi enviado via POST
    echo "<h1>Erro</h1>";
    echo "<p>O formulário não foi enviado corretamente. Por favor, volte e tente novamente.</p>";
}
?>
