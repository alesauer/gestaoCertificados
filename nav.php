<?php include_once("sessao.php"); ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="mostrar_certificados.php">
            <img src="logo.png" alt="Logo do Gerador de Certificados" height="30">
        </a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <!-- Menus do lado esquerdo -->
            <ul class="navbar-nav me-auto">
                <!-- Menu Home -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="mostrar_certificados.php">Home</a>
                </li>

                <!-- Menu Relatórios -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="dashboard.php">Relatórios</a>
                </li>

                <!-- Menu Configuração -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarUsuarios" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Configuração
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarUsuarios">
                        <li><a class="dropdown-item" href="mostrar_usuarios.php">Usuários</a></li>
                        <li><a class="dropdown-item" href="mostrar_cursos.php">Cursos</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Menu Sair alinhado à direita -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarArquivo" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sair
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarArquivo">
                        <li><a class="dropdown-item" href="sair.php"><b>Sair</b></a></li>
                        <li><a class="dropdown-item" href="sobre.php">Sobre</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
