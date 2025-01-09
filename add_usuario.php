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
    $nome = isset($_POST['nome']) ? limparEntrada($_POST['nome']) : '';
    $email = isset($_POST['email']) ? limparEntrada($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? limparEntrada($_POST['senha']) : '';
    $perfil = isset($_POST['perfil']) ? limparEntrada($_POST['perfil']) : '';
    $status = isset($_POST['status']) ? (int)limparEntrada($_POST['status']) : 0;

    // Validação básica dos campos
    if (empty($nome) || empty($email) || empty($senha) || empty($perfil)) {
        die("Todos os campos são obrigatórios.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }

    // Hash da senha (usando password_hash para segurança)
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    // Conectar ao banco de dados
    $db = new Database();

    // Escapar os dados para evitar SQL Injection
    $nomeEscapado = $db->escapeString($nome);
    $emailEscapado = $db->escapeString($email);
    $senhaEscapada = $db->escapeString($senhaHash);
    $perfilEscapado = $db->escapeString($perfil);

    // Inserir o usuário no banco de dados
    $query = "
        INSERT INTO usuarios (nome, email, senha, perfil, status) 
        VALUES ('$nomeEscapado', '$emailEscapado', '$senhaEscapada', '$perfilEscapado', $status)
    ";

    if ($db->query($query)) {
        // Fechar conexão com o banco
        $db->close();

        // Redirecionar com mensagem de sucesso
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Usuário Criado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <div class="alert alert-success">
                    <strong>Usuário criado com sucesso!</strong>
                    <a href="criar_usuarios.php" class="btn btn-primary btn-sm">Voltar</a>
                </div>
            </div>
        </body>
        </html>
        HTML;
    } else {
        // Exibir erro em caso de falha
        $db->close();
        die("Erro ao criar o usuário. Por favor, tente novamente.");
    }
} else {
    // Se o formulário não foi enviado via POST
    echo "<h1>Erro</h1>";
    echo "<p>O formulário não foi enviado corretamente. Por favor, volte e tente novamente.</p>";
}
?>
