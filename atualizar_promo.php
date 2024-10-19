<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id_promo = $_POST['id'];
    $nome_promo = $_POST['nome_promo'];
    $link_promo = $_POST['link_promo'];
    $site_promo = $_POST['site_promo'];
    $info_promo = $_POST['info_promo'];

    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['img_promo']) && $_FILES['img_promo']['error'] == UPLOAD_ERR_OK) {
        $img_promo = $_FILES['img_promo'];
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($img_promo['name']);
        
        if (move_uploaded_file($img_promo['tmp_name'], $upload_file)) {
            // Se o upload da imagem for bem-sucedido, use o novo caminho da imagem
            $img_promo_path = $upload_file;
        } else {
            echo "Erro ao fazer o upload da imagem.";
            exit();
        }
    } else {
        // Se não houver nova imagem, manter a imagem existente
        $img_promo_path = $_POST['img_promo_atual'];
    }

    // Atualizando os dados da promoção no banco de dados
    $query = "UPDATE promos SET nome_promo = ?, link_promo = ?, site_promo = ?, info_promo = ?, img_promo = ? WHERE idpromos = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("sssssi", $nome_promo, $link_promo, $site_promo, $info_promo, $img_promo_path, $id_promo);

    if ($stmt->execute()) {
        // Redireciona para a página do usuário após atualizar
        header("Location: pagina_usuario.php?editado=1");
    } else {
        echo "Erro ao atualizar a promoção.";
    }
} else {
    echo "Dados incompletos.";
}
?>
