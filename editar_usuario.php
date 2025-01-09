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
    $nome = isset($_POST['nome']) ? limparEntrada($_POST['nome']) : '';
    $email = isset($_POST['email']) ? limparEntrada($_POST['email']) : '';
    $perfil = isset($_POST['perfil']) ? limparEntrada($_POST['perfil']) : '';
    $status = isset($_POST['status']) ? (int)limparEntrada($_POST['status']) : 0;
    $novaSenha = isset($_POST['novaSenha']) ? limparEntrada($_POST['novaSenha']) : '';

    // Validação básica dos campos
    if ($id <= 0 || empty($nome) || empty($email) || empty($perfil)) {
        die("Todos os campos obrigatórios devem ser preenchidos.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }

    // Conectar ao banco de dados
    $db = new Database();

    // Escapar os dados para evitar SQL Injection
    $idEscapado = $db->escapeString($id);
    $nomeEscapado = $db->escapeString($nome);
    $emailEscapado = $db->escapeString($email);
    $perfilEscapado = $db->escapeString($perfil);
    $statusEscapado = $db->escapeString($status);

    // Montar a query de atualização
    $query = "UPDATE usuarios 
              SET nome = '$nomeEscapado', 
                  email = '$emailEscapado', 
                  perfil = '$perfilEscapado', 
                  status = $statusEscapado";

    // Verificar se uma nova senha foi fornecida
    if (!empty($novaSenha)) {
        $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT); // Hash seguro da senha
        $senhaEscapada = $db->escapeString($senhaHash);
        $query .= ", senha = '$senhaEscapada'";
    }

    $query .= " WHERE id = $idEscapado";

    // Executar a query de atualização
    if ($db->query($query)) {
        $db->close();
        // Exibir mensagem de sucesso
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Usuário Atualizado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <div class="alert alert-success">
                    <strong>Usuário atualizado com sucesso!</strong>
                    <a href="mostrar_usuarios.php" class="btn btn-primary btn-sm">Voltar</a>
                </div>
            </div>
        </body>
        </html>
        HTML;
    } else {
        $db->close();
        // Exibir mensagem de erro
        die("Erro ao atualizar o usuário. Por favor, tente novamente.");
    }
} else {
    // Se o formulário não foi enviado via POST
    echo "<h1>Erro</h1>";
    echo "<p>O formulário não foi enviado corretamente. Por favor, volte e tente novamente.</p>";
}
?>
