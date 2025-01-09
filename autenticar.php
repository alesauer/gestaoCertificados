<?php
require_once 'lib/databases.php';
session_start(); // Iniciar sessão para armazenar dados do usuário logado

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
    $usuario = isset($_POST['usuario']) ? limparEntrada($_POST['usuario']) : '';
    $senha = isset($_POST['senha']) ? limparEntrada($_POST['senha']) : '';

    // Validação básica dos campos
    if (empty($usuario) || empty($senha)) {
        die("Usuário ou senha não podem estar vazios.");
    }

    // Conectar ao banco de dados
    $db = new Database();

    // Escapar os dados para evitar SQL Injection
    $usuarioEscapado = $db->escapeString($usuario);
    $senhaHash = hash('sha256', $senha); // Gerar o hash SHA256 da senha fornecida

    // Query para buscar o usuário (testa por nome ou email)
    $query = "
        SELECT id, nome, email, senha, perfil, status, contador_visitas 
        FROM usuarios 
        WHERE (email = '$usuarioEscapado' OR nome = '$usuarioEscapado') 
        AND senha = '$senhaHash'
        LIMIT 1
    ";

    $result = $db->query($query);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificar se o usuário está ativo
        if ($user['status'] != 1) {
            $db->close();
            die("Usuário inativo. Entre em contato com o administrador.");
        }

        // Atualizar contador de visitas e último acesso
        $userId = $user['id'];
        $queryUpdate = "
            UPDATE usuarios 
            SET contador_visitas = contador_visitas + 1, 
                ultimo_acesso = NOW() 
            WHERE id = $userId
        ";
        $db->query($queryUpdate);

        // Autenticação bem-sucedida
        $_SESSION['usuario_id'] = $userId;
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_perfil'] = $user['perfil'];
        $_SESSION['contador_visitas'] = $user['contador_visitas'];

        $db->close();
        header("Location: mostrar_certificados.php"); // Redirecionar para a página principal
        exit();
    } else {
        $db->close();
        die("Usuário ou senha inválidos.");
    }
} else {
    // Se o formulário não foi enviado via POST
    echo "<h1>Erro</h1>";
    echo "<p>O formulário não foi enviado corretamente. Por favor, volte e tente novamente.</p>";
}
?>
