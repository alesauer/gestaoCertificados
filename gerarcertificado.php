<?php
require_once 'lib/databases.php';
require('fpdf/fpdf.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$db = new Database();

class PDF extends FPDF {
    private $template_frente;
    private $template_verso;

    function setTemplates($frente, $verso) {
        $this->template_frente = $frente;
        $this->template_verso = $verso;
    }

    function Header() {
        if ($this->PageNo() == 1) {
            $this->Image($this->template_frente, 0, 0, 297, 210);
        } else {
            $this->Image($this->template_verso, 0, 0, 297, 210);
        }
        $this->SetFont('Arial', 'B', 16);
        $this->SetY(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
    }
}

function limparEntrada($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curso_id = isset($_POST['curso']) ? (int)$_POST['curso'] : 0;
    $nome_aluno = isset($_POST['nomeAluno']) ? limparEntrada($_POST['nomeAluno']) : '';
    $cpf = isset($_POST['cpf']) ? limparEntrada($_POST['cpf']) : '';
    $numeracao = isset($_POST['numeracao']) ? limparEntrada($_POST['numeracao']) : '';
    $data_emissao = isset($_POST['dataEmissao']) ? limparEntrada($_POST['dataEmissao']) : '';

    // Validação básica
    if ($curso_id <= 0 || empty($nome_aluno) || empty($cpf) || empty($numeracao) || empty($data_emissao)) {
        die("Todos os campos são obrigatórios.");
    }

    // Validação do CPF (você pode adicionar uma função mais robusta aqui)
    if (strlen($cpf) !== 14) {
        die("CPF inválido.");
    }

    // Buscar informações do curso
    $query_curso = "SELECT nome_curso, professor, carga_horaria, template_path_frente, template_path_verso 
                    FROM cursos WHERE id = $curso_id";
    $result_curso = $db->query($query_curso);
    $curso_info = $result_curso->fetch_assoc();

    // Inserir dados no banco
    $query = "INSERT INTO certificados_gerados (curso_id, nome_aluno, cpf, numeracao, data_emissao) 
              VALUES ($curso_id, '" . $db->escapeString($nome_aluno) . "', 
              '" . $db->escapeString($cpf) . "', '" . $db->escapeString($numeracao) . "', 
              '" . $db->escapeString($data_emissao) . "')";

    $result = $db->query($query);

    if ($result) {
        #$certificado_id = $db->conn->insert_id;
        $certificado_id = $db->getLastInsertId();


        // Cria uma instância da classe PDF
        $pdf = new PDF('L', 'mm', 'A4');
        $pdf->setTemplates($curso_info['template_path_frente'], $curso_info['template_path_verso']);
        
        // Página da frente
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 28);
        $pdf->SetXY(30, 100);
        $pdf->Cell(0, 20, utf8_decode(strtoupper($nome_aluno)), 0, 1, 'L');

      
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(30, 0); // X para o lado esquerdo e Y ajustado acima do limite inferior seguro
        $pdf->Cell(40, 150, utf8_decode("Data de Emissão: " . $data_emissao), 0, 0, 'L');



        // Página do verso
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(30, 50);
        $pdf->Cell(0, 10, utf8_decode("Professor: " . $curso_info['professor']), 0, 1, 'L');
        $pdf->SetX(30);
        $pdf->Cell(0, 10, utf8_decode("Numeração: " . $numeracao), 0, 1, 'L');
        $pdf->SetX(30);
        $pdf->Cell(0, 10, utf8_decode("CPF do Aluno: " . $cpf), 0, 1, 'L');



        // Salva o PDF em memória
        $pdf_content = $pdf->Output('S');

        // Armazena o PDF na sessão
        $_SESSION['pdf_content'] = $pdf_content;
        $_SESSION['pdf_filename'] = $nome_aluno . ".pdf";

        // Redireciona para a página de sucesso
        header("Location: sucesso.php");
        exit();
    } else {
        echo "Erro ao gerar o certificado. Por favor, tente novamente.";
    }
} else {
    echo "Método de requisição inválido.";
}

$db->close();
?>