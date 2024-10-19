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
    <title>Editar Promoção</title>
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

                    <form action="atualizar_promo.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $promo['idpromos']; ?>">

                        <div class="mb-3">
                            <label for="nome_promo" class="form-label">Nome da Promoção</label>
                            <input type="text" class="form-control" name="nome_promo" value="<?php echo $promo['nome_promo']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="link_promo" class="form-label">Link da Promoção</label>
                            <input type="text" class="form-control" name="link_promo" value="<?php echo $promo['link_promo']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="site_promo" class="form-label">Site da Promoção</label>
                            <input type="text" class="form-control" name="site_promo" value="<?php echo $promo['site_promo']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="info_promo" class="form-label">Informações da Promoção</label>
                            <textarea class="form-control" name="info_promo" rows="4" required><?php echo $promo['info_promo']; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="img_promo" class="form-label">Imagem da Promoção</label>
                            <input type="file" class="form-control" name="img_promo">
                            <?php 
                            if (!empty($promo["img_promo"])) {
                                $img_path = 'uploads/' . basename($promo["img_promo"]);
                                if (file_exists($img_path)) {
                                    echo '<img src="' . htmlspecialchars($img_path) . '" class="img-fluid rounded mt-2" alt="' . htmlspecialchars($promo["nome_promo"]) . '" width="150px">';
                                } else {
                                    echo '<img src="uploads/sem_foto.png" class="img-fluid rounded mt-2" alt="Imagem padrão" width="150px">';
                                }
                            } else {
                                echo '<img src="uploads/sem_foto.png" class="img-fluid rounded mt-2" alt="Imagem padrão" width="150px">';
                            }
                            ?>
                            <input type="hidden" name="img_promo_atual" value="<?php echo $promo['img_promo']; ?>">
                        </div>

                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

</body>
</html>




