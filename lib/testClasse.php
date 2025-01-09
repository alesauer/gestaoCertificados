<?php
require_once 'databases.php';

$db = new Database();

// Executar uma query
$result = $db->query("SELECT * FROM cursos");

// Processar os resultados
while ($row = $result->fetch_assoc()) {
    echo $row['nome_curso'] . "<br>";
}

// Fechar a conexão quando terminar
$db->close();

?>