<?php
require_once 'lib/databases.php';

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar o ID do curso a ser excluído
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // Verificar se o ID é válido
    if ($id <= 0) {
        die("ID do curso inválido.");
    }

    // Conectar ao banco de dados
    $db = new Database();

    // Escapar o ID para evitar SQL Injection
    $idEscapado = $db->escapeString($id);

    // Deletar o curso do banco de dados
    $query = "DELETE FROM cursos WHERE id = $idEscapado";

    if ($db->query($query)) {
        $db->close();

        // Exibir mensagem de sucesso
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Curso Excluído</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <div class="alert alert-success">
                    <strong>Curso excluído com sucesso!</strong>
                    <a href="mostrar_cursos.php" class="btn btn-primary btn-sm">Voltar</a>
                </div>
            </div>
        </body>
        </html>
        HTML;
    } else {
        $db->close();
        // Exibir mensagem de erro
        die("Erro ao excluir o curso. Por favor, tente novamente.");
    }
} else {
    // Se o formulário não foi enviado via POST
    echo "<h1>Erro</h1>";
    echo "<p>O formulário não foi enviado corretamente. Por favor, volte e tente novamente.</p>";
}
?>
