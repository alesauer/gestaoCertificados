<?php
// cadastrar_cursos.php

// Incluir a classe Database
require_once 'lib/databases.php';

// Iniciar a sessão (caso necessário para futuras funcionalidades)
session_start();

// Função para limpar e validar os dados de entrada
function limparEntrada($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar e limpar os dados recebidos
    $nomeCurso = isset($_POST['nomeCurso']) ? limparEntrada($_POST['nomeCurso']) : '';
    $professorCurso = isset($_POST['professorCurso']) ? limparEntrada($_POST['professorCurso']) : '';
    $cargaHoraria = isset($_POST['cargaHoraria']) ? limparEntrada($_POST['cargaHoraria']) : '';
    
    // Informações sobre os arquivos enviados
    $templateFrenteTmp = isset($_FILES['templateFrente']['tmp_name']) ? $_FILES['templateFrente']['tmp_name'] : '';
    $templateVersoTmp = isset($_FILES['templateVerso']['tmp_name']) ? $_FILES['templateVerso']['tmp_name'] : '';

    // Validação básica
    if (empty($nomeCurso) || empty($professorCurso) || empty($cargaHoraria) || empty($templateFrenteTmp) || empty($templateVersoTmp)) {
        die("Todos os campos são obrigatórios.");
    }

    // Caminhos para salvar os arquivos no servidor
    $uploadDir = 'templates/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Criar o diretório se não existir
    }

    // Nomes específicos para os arquivos
    $templateFrentePath = $uploadDir . strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $nomeCurso)) . '_frente.jpg';
    $templateVersoPath = $uploadDir . strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $nomeCurso)) . '_verso.jpg';

    // Mover os arquivos para o diretório de templates
    if (!move_uploaded_file($templateFrenteTmp, $templateFrentePath)) {
        die("Erro ao fazer upload do template da frente. Verifique se o arquivo é válido e tente novamente.");
    }

    if (!move_uploaded_file($templateVersoTmp, $templateVersoPath)) {
        die("Erro ao fazer upload do template do verso. Verifique se o arquivo é válido e tente novamente.");
    }

    // Instanciar o banco de dados
    $db = new Database();

    // Escapar os dados para evitar SQL Injection
    $nomeCursoEscapado = $db->escapeString($nomeCurso);
    $professorCursoEscapado = $db->escapeString($professorCurso);
    $cargaHorariaEscapada = $db->escapeString($cargaHoraria);
    $templateFrentePathEscapado = $db->escapeString($templateFrentePath);
    $templateVersoPathEscapado = $db->escapeString($templateVersoPath);

    // Inserir os dados no banco de dados
    $query = "INSERT INTO cursos (nome_curso, professor, carga_horaria, template_path_frente, template_path_verso)
              VALUES ('$nomeCursoEscapado', '$professorCursoEscapado', '$cargaHorariaEscapada', 
                      '$templateFrentePathEscapado', '$templateVersoPathEscapado')";

    if ($db->query($query)) {
        // Fechar a conexão com o banco de dados
        $db->close();

        // Exibir mensagem de sucesso em modal
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Curso Cadastrado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Exibir modal automaticamente ao carregar a página
                window.onload = function() {
                    const modal = new bootstrap.Modal(document.getElementById('sucessoModal'));
                    modal.show();
                };
            </script>
        </head>
        <body>
            <!-- Modal de Sucesso -->
            <div class="modal fade" id="sucessoModal" tabindex="-1" aria-labelledby="sucessoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="sucessoModalLabel">Curso Cadastrado com Sucesso!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nome do Curso:</strong> $nomeCurso</p>
                            <p><strong>Professor do Curso:</strong> $professorCurso</p>
                            <p><strong>Carga Horária:</strong> $cargaHoraria</p>
                            <p><strong>Template da Frente:</strong> $templateFrentePath</p>
                            <p><strong>Template do Verso:</strong> $templateVersoPath</p>
                        </div>
                        <div class="modal-footer">
                            <a href="mostrar_cursos.php" class="btn btn-primary">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;
    } else {
        die("Erro ao cadastrar o curso no banco de dados.");
    }

} else {
    // Se o formulário não foi enviado via POST, exibir uma mensagem de erro
    echo "<h1>Erro</h1>";
    echo "<p>O formulário não foi enviado corretamente. Por favor, volte e tente novamente.</p>";
}
?>
