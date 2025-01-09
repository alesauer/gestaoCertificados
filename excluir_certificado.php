<?php
require_once 'lib/databases.php';

// Inicializar variáveis para mensagens
$mensagem = '';
$tipoMensagem = ''; // sucesso ou erro

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar o ID do certificado a ser excluído
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // Verificar se o ID é válido
    if ($id <= 0) {
        $mensagem = "ID do certificado inválido.";
        $tipoMensagem = 'erro';
    } else {
        // Conectar ao banco de dados
        $db = new Database();

        // Escapar o ID para evitar SQL Injection
        $idEscapado = $db->escapeString($id);

        // Deletar o certificado do banco de dados
        $query = "DELETE FROM certificados_gerados WHERE id = $idEscapado";

        if ($db->query($query)) {
            $mensagem = "Certificado excluído com sucesso!";
            $tipoMensagem = 'sucesso';
        } else {
            $mensagem = "Erro ao excluir o certificado. Por favor, tente novamente.";
            $tipoMensagem = 'erro';
        }

        $db->close();
    }
} else {
    $mensagem = "O formulário não foi enviado corretamente.";
    $tipoMensagem = 'erro';
}

// Exibir o modal com a mensagem de retorno
echo <<<HTML
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Certificado</title>
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
