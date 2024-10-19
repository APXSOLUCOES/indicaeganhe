<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Busca - Indica e Ganhe!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">

            <?php
            include 'config.php';

            // Verifique se uma sessão já foi iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Obtenha a consulta de busca
            $query = isset($_GET['query']) ? $conexao->real_escape_string($_GET['query']) : '';

            // Consulte o banco de dados para promoções que correspondam à busca
            $sql = "SELECT * FROM promos WHERE nome_promo LIKE '%$query%' OR site_promo LIKE '%$query%'";
            $resultado = $conexao->query($sql);

            // Verifique se há resultados
            if ($resultado->num_rows > 0) {
                // Exibir os dados dentro do loop while
                while($row = $resultado->fetch_assoc()) {
                    echo '<div class="promo-card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["nome_promo"] . '</h5>';
                    echo '<p class="card-text">' . $row["site_promo"] . '</p>';
                    echo '<a href="#" class="btn btn-primary">Ver Mais</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "Nenhum resultado encontrado.";
            }
            ?>
        </div>
        <div class="col-md-4">
            <h2>Categorias</h2>
            <ul class="list-group">
                <li class="list-group-item">Eletrônicos</li>
                <li class="list-group-item">Moda</li>
                <li class="list-group-item">Casa e Cozinha</li>
                <!-- Adicione mais categorias conforme necessário -->
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
