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
        <title>Página do Usuário</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="dist/css/custom-bootstrap.css">
        <link rel="stylesheet" href="css/styles.css?v=1">
    </head>

    <body>
        <header>
            <?php include('menu.php'); ?>
        </header>
        <main>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8">
                    <div class="exclusive-content">
                        <h2>Conteúdo Exclusivo</h2>
                        <p>Bem-vindo, <?php echo $logado; ?>! Este conteúdo é exclusivo para você.</p>
                        <div class="promo-card">
                            <div class="card-body">
                                <h5 class="card-title pb-2">Cadastre sua promo de indicação aqui</h5>
                                <a href="#" class="btn btn-bd-primary" data-bs-toggle="modal" data-bs-target="#promoModal">Indicar</a>
                            </div>
                        </div>

                        <div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="promoModalLabel">Cadastre sua Promo de Indicação</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <form action="cadastro_promo.php" method="POST"  enctype="multipart/form-data">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nome_promo" name="nome_promo" placeholder="Qual o Nome da Promoção?">
                                            <label for="floatingInput">Qual o Nome da Promoção?</label>
                                        </div>

                                        <div class="mb-3">
                                            <label for="link_promo" class="form-label">Qual o seu link de indicação?</label>
                                            <input type="text" class="form-control" id="link_promo" name="link_promo" placeholder="Cole aqui o link que o usuário deve copiar e indicar você" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="site_promo" class="form-label">Site da promo</label>
                                            <input type="text" class="form-control" id="site_promo" name="site_promo" placeholder="Qual o site da promoção?" required>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" textarea.style.height = "auto" placeholder="Comente os detalhes da promo e convença os usuários a usar seu link!" id="info_promo" name="info_promo" oninput="autoResize(this)"></textarea>
                                            <label for="floatingTextarea">Comente os detalhes da promo para o usuário te indicar</label>
                                        </div>

                                        <div class="input-group">
                                            <input type="file" class="form-control" id="img_promo" name= "img_promo" accept="image/*" required onchange="previewImage(event)" aria-describedby="img_promo" aria-label="Upload">
                                        </div>
                                        
                                        <div id="image-container">
                                            <img id="image-preview" class="image-preview" />
                                            <button type="button" id="remove-image" class="remove-image" onclick="removeImage()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                        
                                        <br>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-custom">Cadastrar</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="exclusive-content">
                        <h3>Suas Promos</h3>
                        <?php
                            $sql = "SELECT idpromos, nome_promo, site_promo FROM promos WHERE idusuarios = ? ORDER BY data_promo DESC LIMIT 10";
                            $stmt = $conexao->prepare($sql);
                            $stmt->bind_param("i", $id_usuario_logado);
                            $stmt->execute();
                            $resultado = $stmt->get_result();

                            if ($resultado->num_rows > 0) {
                                while($row = $resultado->fetch_assoc()) {
                                    echo '<div class="promo-card">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . $row["nome_promo"] . '</h5>';
                                    echo '<p class="card-text">' . $row["site_promo"] . '</p>';
                                    echo '<a href="promo.php?id=' . $row["idpromos"] . '" class="btn btn-bd-primary">Ver Mais</a>';
                                    echo "<a href='editar_promo.php?id=" . $row["idpromos"] . "' class='btn btn-bd-primary'>Editar</a>"; // Botão Editar
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo "Nenhum resultado encontrado.";
                            }
                        ?>

                    </div>
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
            
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function(){
                    var output = document.getElementById('image-preview');
                    output.src = reader.result;
                    output.style.display = 'block';
                    document.getElementById('remove-image').style.display = 'inline';
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            function removeImage() {
                var fileInput = document.getElementById('img_promo');
                fileInput.value = ''; // Clear the file input
                var output = document.getElementById('image-preview');
                output.src = '';
                output.style.display = 'none';
                document.getElementById('remove-image').style.display = 'none';
            }

        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
