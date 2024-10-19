<?php
include 'config.php';

// Verifique se uma sessão já foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifique se o usuário está logado
$loggedIn = isset($_SESSION['email']);
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/anhe(1).png" class="img-fluid rounded-top" alt="Logo site" height="40" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

            <form class="search-container d-flex mx-lg-auto my-2 my-lg-0" style="min-width: 300px;" role="search" action="search.php" method="GET">
                <input class="form-control" type="search" placeholder="Buscar Promoções" aria-label="Search" name="query">
                <button class="btn search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <ul class="navbar-nav ms-auto">
                <?php if ($loggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pagina_usuario.php">
                            <i class="bi bi-person-fill-gear"></i> Minhas Promos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
