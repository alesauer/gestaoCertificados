<!-- Footer --> 
<footer class="footer mt-auto py-3 text-white" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <div class="container ps-0">
        <?php
        // Verificar se o usuário está logado e os dados necessários estão disponíveis na sessão
        if (isset($_SESSION['usuario_nome'], $_SESSION['contador_visitas'])) {
            $usuarioNome = htmlspecialchars($_SESSION['usuario_nome']);
            $contadorVisitas = htmlspecialchars($_SESSION['contador_visitas']);
            echo "<p class='mb-0'>&copy; 2025 Simplifica Treinamentos - Sistema de Gestão e Geração de Certificados</p>";
            echo "<p class='mb-0'>Usuário Logado: <strong>$usuarioNome</strong> | Acessos: <strong>$contadorVisitas</strong></p>";
        } else {
            echo "<p class='mb-0'>&copy; 2025 Simplifica Treinamentos - Sistema de Gestão e Geração de Certificados</p>";
        }
        ?>
    </div>
</footer>

