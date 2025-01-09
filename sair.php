<?php
session_start(); // Iniciar ou continuar a sessão

// Limpar todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header("Location: login.php");
exit();
?>
