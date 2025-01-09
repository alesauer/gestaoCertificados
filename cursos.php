<!DOCTYPE html>
<html lang="pt-br">
<?php include_once("head.php");  ?>
<body>
    <?php include_once("nav.php"); ?>
 
    <!-- Main Content -->
    <div class="container mt-4">
        <h2>Cadastro de Cursos</h2>
        
        <form id="cursosForm" action="cadastrar_cursos.php" method="POST" enctype="multipart/form-data" novalidate>


            <div class="mb-3">
                <label for="nomeCurso" class="form-label">Nome do Curso</label>
                <input type="text" class="form-control" id="nomeCurso" name="nomeCurso" required>
                <div class="invalid-feedback">
                    Por favor, insira o nome do curso.
                </div>
            </div>
            
            <div class="mb-3">
                <label for="professorCurso" class="form-label">Professor do Curso</label>
                <input type="text" class="form-control" id="professorCurso" name="professorCurso" required>
                <div class="invalid-feedback">
                    Por favor, insira o nome do professor.
                </div>
            </div>
            
            <div class="mb-3">
                <label for="cargaHoraria" class="form-label">Carga Horária</label>
                <input type="text" class="form-control" id="cargaHoraria" name="cargaHoraria" required>
                <div class="invalid-feedback">
                    Por favor, insira a carga horária.
                </div>
            </div>
            
            <div class="mb-3">
                <label for="templateFrente" class="form-label">Template da Frente</label>
                <input type="file" class="form-control" id="templateFrente" name="templateFrente" required>
                <div class="invalid-feedback">
                    Por favor, faça o upload do template da frente.
                </div>
            </div>

            <div class="mb-3">
                <label for="templateVerso" class="form-label">Template do Verso</label>
                <input type="file" class="form-control" id="templateVerso" name="templateVerso" required>
                <div class="invalid-feedback">
                    Por favor, faça o upload do template do verso.
                </div>
            </div>
            
              <!-- Buttons -->
              <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary" id="cadastrarCurso">Cadastrar Curso</button>
                <a href="mostrar_certificados.php" class="btn btn-secondary">Cancelar</a>
            </div>


        </form>
    </div>
    <?php include_once("footer.php"); ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        const form = document.getElementById('cursosForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    </script>
</body>
</html>
