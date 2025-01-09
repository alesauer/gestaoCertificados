<?php
session_start();

if (isset($_SESSION['pdf_content']) && isset($_SESSION['pdf_filename'])) {
    $pdf_content = $_SESSION['pdf_content'];
    $filename = $_SESSION['pdf_filename'];

    // Clear the session variables
    unset($_SESSION['pdf_content']);
    unset($_SESSION['pdf_filename']);

    // Output the PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($pdf_content));
    echo $pdf_content;
    exit();
} else {
    echo "<h1>Certificado Gerado com Sucesso</h1>";
    echo "<p>O seu certificado foi gerado e está disponível para download.</p>";
    echo "<a href='principal.php'>Voltar para a página inicial</a>";
}
?>