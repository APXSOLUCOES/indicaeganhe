<?php
session_start();
if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit();
}

// Inclua o arquivo de configuração do banco de dados
include 'config.php';

// Pegando o ID do usuário com base no email
$email = $_SESSION['email'];
$query = $conexao->prepare("SELECT idusuarios FROM usuarios WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['idusuarios'] = $row['idusuarios'];
} else {
    echo "Erro ao obter o ID do usuário.";
    exit();
}

$logado = $_SESSION['email'];
$id_usuario_logado = $_SESSION['idusuarios'];
?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Bem Vindo</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>
        <header>
            <?php include('menu.php'); ?>
        </header>
        <main>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8">
                <h3>Últimas Promos Cadastradas</h3>
                    <?php
                        // Consulta SQL para selecionar as últimas promos cadastradas
                        $sql = "SELECT nome_promo, site_promo FROM promos ORDER BY data_promo DESC LIMIT 20"; // Ajuste o LIMIT conforme necessário
                        $resultado = $conexao->query($sql);

                        if ($resultado->num_rows > 0) {
                            // Exibir os dados dentro do loop while
                            while($row = $resultado->fetch_assoc()) {
                                echo '<div class="promo-card">';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">' . $row["nome_promo"] . '</h5>';
                                echo '<p class="card-text">' . $row["site_promo"] . '</p>';
                                echo '<a href="#" class="botton">Ver Mais</a>';
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
                    </ul>
                </div>
            </div>
        </div>
        </main>
        <footer></footer>

        <script>
            function autoResize(textarea) {
                textarea.style.height = 'auto'; // Reset height to auto to calculate the new height
                textarea.style.height = (textarea.scrollHeight) + 'px'; // Set the height to the scrollHeight
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
