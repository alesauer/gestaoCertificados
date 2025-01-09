<?php
session_start(); // Iniciar a sessão

// Verificar se o usuário está autenticado
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    // Redirecionar para a página de login
    header("Location: login.php");
    exit();
}

// Controle de acesso por perfil
// Exemplo: Restringir acesso a usuários que não são "admin" em determinadas páginas
$currentFile = basename($_SERVER['PHP_SELF']); // Obter o nome do arquivo atual

// Definir restrições para cada perfil
$permissoes = [
    'admin' => [], // Admin tem acesso a todas as páginas
    'usuario' => ['dashboard.php', 'mostrar_certificados.php'], // Usuários comuns têm acesso restrito
];

// Verificar o perfil do usuário atual
$perfil = $_SESSION['usuario_perfil'] ?? 'usuario'; // Padrão: usuário comum

// Verificar se a página atual é permitida para o perfil
if (isset($permissoes[$perfil]) && !empty($permissoes[$perfil])) {
    if (!in_array($currentFile, $permissoes[$perfil])) {
        // Exibir mensagem de acesso negado ou redirecionar
        header("Location: acesso_negado.php");
        exit();
    }
}
?>
