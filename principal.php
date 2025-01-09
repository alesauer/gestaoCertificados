<?php
require_once 'lib/databases.php';
$db = new Database();
$courses = $db->query("SELECT id, nome_curso FROM cursos ORDER BY nome_curso");
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php include_once("head.php");  ?>

<body>

<?php include_once("nav.php"); ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2>Selecione o Curso que deseja gerar o certificado</h2>
        
        <form id="certificadoForm" action="gerarcertificado.php" method="POST" novalidate>
            <!-- Refactored course selection -->
            <div class="mb-3">
                <label for="curso" class="form-label">Selecione o curso:</label>
                <select class="form-select" id="curso" name="curso" required>
                    <option value="">Selecione um curso</option>
                    <?php
                    while ($row = $courses->fetch_assoc()) {
                        echo "<option value=\"" . $row['id'] . "\">" . htmlspecialchars($row['nome_curso']) . "</option>";
                    }
                    ?>
                    <option value="">Gerar para todos</option>
                </select>
                <div class="invalid-feedback">
                    Por favor, selecione um curso.
                </div>
            </div>
            
            <!-- Text fields -->
            <div class="mb-3">
                <label for="nomeAluno" class="form-label">Nome do aluno</label>
                <input type="text" class="form-control" id="nomeAluno" name="nomeAluno" required>
                <div class="invalid-feedback">
                    Por favor, insira o nome do aluno.
                </div>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" required>
                <div class="invalid-feedback">
                    Por favor, insira um CPF válido.
                </div>
            </div>
            <div class="mb-3">
                <label for="numeracao" class="form-label">Numeração</label>
                <input type="text" class="form-control" id="numeracao" name="numeracao" required>
                <div class="invalid-feedback">
                    Por favor, insira a numeração.
                </div>
            </div>
            <div class="mb-3">
                <label for="dataEmissao" class="form-label">Data de emissão</label>
                <input type="date" class="form-control" id="dataEmissao" name="dataEmissao" required>
                <div class="invalid-feedback">
                    Por favor, selecione a data de emissão.
                </div>
            </div>
            
         <!-- Buttons -->
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary" id="gerarCertificado">Gerar Certificado</button>
    <a href="mostrar_certificados.php" class="btn btn-secondary">Cancelar</a>
</div>

        </form>
    </div>

    <?php include_once("footer.php"); ?>
 
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set current date as default for dataEmissao
        document.getElementById('dataEmissao').valueAsDate = new Date();

        // CPF validation function
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g,'');
            if(cpf == '') return false;
            // Elimina CPFs invalidos conhecidos	
            if (cpf.length != 11 || 
                cpf == "00000000000" || 
                cpf == "11111111111" || 
                cpf == "22222222222" || 
                cpf == "33333333333" || 
                cpf == "44444444444" || 
                cpf == "55555555555" || 
                cpf == "66666666666" || 
                cpf == "77777777777" || 
                cpf == "88888888888" || 
                cpf == "99999999999")
                    return false;		
            // Valida 1o digito	
            add = 0;	
            for (i=0; i < 9; i ++)		
                add += parseInt(cpf.charAt(i)) * (10 - i);	
                rev = 11 - (add % 11);	
                if (rev == 10 || rev == 11)		
                    rev = 0;	
                if (rev != parseInt(cpf.charAt(9)))		
                    return false;		
            // Valida 2o digito	
            add = 0;	
            for (i = 0; i < 10; i ++)		
                add += parseInt(cpf.charAt(i)) * (11 - i);	
            rev = 11 - (add % 11);	
            if (rev == 10 || rev == 11)	
                rev = 0;	
            if (rev != parseInt(cpf.charAt(10)))
                return false;		
            return true;   
        }

        // CPF input mask
        function mascararCPF(cpf) {
            return cpf
                .replace(/\D/g, '')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})/, '$1-$2')
                .replace(/(-\d{2})\d+?$/, '$1');
        }

        // Add event listener to CPF input
        const cpfInput = document.getElementById('cpf');
        cpfInput.addEventListener('input', function(e) {
            let cpf = e.target.value;
            e.target.value = mascararCPF(cpf);
            
            if(cpf.length === 14) {
                if(validarCPF(cpf)) {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                } else {
                    e.target.classList.remove('is-valid');
                    e.target.classList.add('is-invalid');
                }
            } else {
                e.target.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Add event listener to form submission
        const form = document.getElementById('certificadoForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission
            
            // Remove all existing validation classes
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
            
            let isValid = true;

            // Validate all required fields
            form.querySelectorAll('[required]').forEach(el => {
                if (!el.value) {
                    el.classList.add('is-invalid');
                    isValid = false;
                } else {
                    el.classList.add('is-valid');
                }
            });

            // Special validation for CPF
            const cpf = cpfInput.value;
            if (!validarCPF(cpf)) {
                cpfInput.classList.remove('is-valid');
                cpfInput.classList.add('is-invalid');
                isValid = false;
            }

            if (isValid) {
                form.submit(); // Submit the form if all validations pass
            }
        });
    </script>
</body>
</html>
