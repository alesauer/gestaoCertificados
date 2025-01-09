<?php
require_once 'lib/databases.php';

// Função para limpar e validar os dados de entrada
function limparEntrada($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Inicializar variáveis para mensagens
$mensagem = '';
$tipoMensagem = ''; // sucesso ou erro

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar e limpar os dados enviados
    $id = isset($_POST['id']) ? (int)limparEntrada($_POST['id']) : 0;
    $nome_aluno = isset($_POST['nome_aluno']) ? limparEntrada($_POST['nome_aluno']) : '';
    $cpf = isset($_POST['cpf']) ? limparEntrada($_POST['cpf']) : '';
    $curso = isset($_POST['curso']) ? limparEntrada($_POST['curso']) : '';
    $data_emissao = isset($_POST['data_emissao']) ? limparEntrada($_POST['data_emissao']) : '';
    $numeracao = isset($_POST['numeracao']) ? limparEntrada($_POST['numeracao']) : '';

    // Validação básica dos campos
    if ($id <= 0 || empty($nome_aluno) || empty($cpf) || empty($curso) || empty($data_emissao) || empty($numeracao)) {
        $mensagem = "Todos os campos obrigatórios devem ser preenchidos.";
        $tipoMensagem = 'erro';
    } elseif (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
        $mensagem = "O CPF informado é inválido.";
        $tipoMensagem = 'erro';
    } else {
        // Conectar ao banco de dados
        $db = new Database();

        // Escapar os dados para evitar SQL Injection
        $idEscapado = $db->escapeString($id);
        $nomeAlunoEscapado = $db->escapeString($nome_aluno);
        $cpfEscapado = $db->escapeString($cpf);
        $cursoEscapado = $db->escapeString($curso);
        $dataEmissaoEscapada = $db->escapeString($data_emissao);
        $numeracaoEscapada = $db->escapeString($numeracao);

        // Atualizar os dados do certificado
        $query = "
            UPDATE certificados_gerados
            SET 
                nome_aluno = '$nomeAlunoEscapado',
                cpf = '$cpfEscapado',
                curso_id = (
                    SELECT id FROM cursos WHERE nome_curso = '$cursoEscapado' LIMIT 1
                ),
                data_emissao = '$dataEmissaoEscapada',
                numeracao = '$numeracaoEscapada'
            WHERE id = $idEscapado
        ";

        if ($db->query($query)) {
            $mensagem = "Certificado atualizado com sucesso!";
            $tipoMensagem = 'sucesso';
        } else {
            $mensagem = "Erro ao atualizar o certificado. Por favor, tente novamente.";
            $tipoMensagem = 'erro';
        }

        $db->close();
    }
} else {
    $mensagem = "O formulário não foi enviado corretamente.";
    $tipoMensagem = 'erro';
}

// Retornar a mensagem ao modal de origem usando JavaScript
echo <<<HTML
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de Certificado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para exibir mensagem no modal
        window.onload = function() {
            const mensagem = "$mensagem";
            const tipo = "$tipoMensagem";

            // Criar e configurar o modal de resposta
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = 'respostaModal';
            modal.setAttribute('tabindex', '-1');
            modal.setAttribute('aria-labelledby', 'respostaModalLabel');
            modal.setAttribute('aria-hidden', 'true');
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header ${tipo === 'sucesso' ? 'bg-success' : 'bg-danger'} text-white">
                            <h5 class="modal-title" id="respostaModalLabel">${tipo === 'sucesso' ? 'Sucesso!' : 'Erro!'}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>${mensagem}</p>
                        </div>
                        <div class="modal-footer">
                            <a href="mostrar_certificados.php" class="btn btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Exibir o modal automaticamente
            const respostaModal = new bootstrap.Modal(document.getElementById('respostaModal'));
            respostaModal.show();
        }
    </script>
</head>
<body>
</body>
</html>
HTML;
?>
