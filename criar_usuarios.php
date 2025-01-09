<?php include_once("nav.php"); ?>

<!DOCTYPE html>
<html lang="pt-br">
<?php include_once("head.php");  ?>
<body>
    <div class="container mt-4">
        <h2>Criar Usuário</h2>
        <form id="criarUsuarioForm" action="add_usuario.php" method="POST" novalidate>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
                <div class="invalid-feedback">
                    Por favor, insira o nome.
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">
                    Por favor, insira um e-mail válido.
                </div>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
                <div class="invalid-feedback">
                    Por favor, insira uma senha.
                </div>
            </div>
            <div class="mb-3">
                <label for="perfil" class="form-label">Perfil</label>
                <select class="form-select" id="perfil" name="perfil" required>
                    <option value="">Selecione um perfil</option>
                    <option value="admin">Administrador</option>
                    <option value="usuario">Usuário</option>
                </select>
                <div class="invalid-feedback">
                    Por favor, selecione um perfil.
                </div>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">Selecione o status</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
                <div class="invalid-feedback">
                    Por favor, selecione o status.
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Criar Usuário</button>
                <a href="mostrar_certificados.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    <?php include_once("footer.php"); ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        const form = document.getElementById('criarUsuarioForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    </script>
</body>
</html>
