<?php
// Incluir o arquivo de configuração do banco de dados
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para obter os detalhes da promoção com base no ID
    $sql = "SELECT * FROM promos WHERE idpromos = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $promo = $result->fetch_assoc();
    } else {
        echo "Promoção não encontrada.";
        exit();
    }
} else {
    echo "ID da promoção não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Promoção</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dist/css/custom-bootstrap.css">
    <link rel="stylesheet" href="css/styles.css?v=1">

</head>
<body>

    <?php include 'menu.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="promo-card col-md-8">
                <div class="card-body">
                    <a class="icon-link d-flex justify-content-end align-items-center" href="#" onclick="history.back();">
                        <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
                    </a>

                    <div
                        class="row-cols-auto justify-content-start align-items-center g-2">
                        <p class="card-text"><?php echo $promo['site_promo']; ?></>

                    </div>
                    


                    <div class="row align-items-center text-center">
                        <div class="col text-center align-items-center p-3">
                            <?php 
                            if (!empty($promo["img_promo"])) {
                                $img_path = 'uploads/' . basename($promo["img_promo"]); // basename garante que apenas o nome do arquivo é utilizado
                                // Verificar se o arquivo existe
                                if (file_exists($img_path)) {
                                    echo '<img src="' . htmlspecialchars($img_path) . '" class="img-fluid rounded float-start" alt="' . htmlspecialchars($promo["nome_promo"]) . '">';
                                } else {
                                    echo '<img src="uploads/sem_foto.png" class="img-fluid rounded float-start" alt="Imagem padrão">';
                                }
                            } else {
                                echo '<img src="uploads/sem_foto.png" class="img-fluid rounded float-start" alt="Imagem padrão">';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="card-title mb-2 p-2"><?php echo $promo['nome_promo']; ?></h5>
                        <p class="fw-light"><?php echo $promo['info_promo']; ?></p>
                    </div>

                    <div class="mb-4">
                        <label for="promoLink" class="form-label fs-6">Link da Promoção</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="promoLink" value="<?php echo $promo['link_promo']; ?>" readonly>
                            <button class="btn btn-outline" type="button" id="copyButton" data-clipboard-target="#promoLink">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>

                    <a href="<?php echo $promo['link_promo']; ?>" class="btn btn-bd-primary">Ir para a Promoção</a>

                    <!-- Inclua a biblioteca Clipboard.js -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

                    <script>
                        // Inicialize o Clipboard.js
                        new ClipboardJS('#copyButton');

                        // Adicione um evento para informar o usuário que o link foi copiado
                        document.getElementById('copyButton').addEventListener('click', function() {
                            alert('Link copiado para a área de transferência!');
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>



</body>

</html>

