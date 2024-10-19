<?php

session_start(); // Adicionando o início da sessão

// Inclua o arquivo de configuração do banco de dados
include 'config.php';

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando os valores do formulário
    $nome_promo = $_POST['nome_promo'];
    $link_promo = $_POST['link_promo'];
    $site_promo = $_POST['site_promo'];
    $info_promo = $_POST['info_promo'];
    $idusuarios = $_SESSION['idusuarios']; // Obtém o ID do usuário logado

    // Verifica se a imagem foi enviada e é válida
    if (isset($_FILES['img_promo']) && $_FILES['img_promo']['error'] == UPLOAD_ERR_OK) {
        $img_promo = $_FILES['img_promo'];
        $upload_dir = 'uploads/'; // Diretório onde as imagens serão salvas
        $upload_file = $upload_dir . basename($img_promo['name']);

        // Verifica se o diretório existe, se não cria
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Verifica se o arquivo foi realmente carregado
        if (move_uploaded_file($img_promo['tmp_name'], $upload_file)) {
            // Preparar e executar a consulta SQL para inserir os dados no banco de dados
            $query = $conexao->prepare("INSERT INTO promos (nome_promo, link_promo, site_promo, info_promo, img_promo, idusuarios) VALUES (?, ?, ?, ?, ?, ?)");
            $query->bind_param("sssssi", $nome_promo, $link_promo, $site_promo, $info_promo, $upload_file, $idusuarios);
            $query->execute();

            // Verificar se a inserção foi bem-sucedida
            if ($query) {
                // Redirecionar para a página index.php
                header("Location: index.php");
                exit(); // Certifique-se de que a execução do script é encerrada após o redirecionamento
            } else {
                $mensagem = "Erro ao inserir dados no cadastro: " . $conexao->error;
            }
        } else {
            $mensagem = "Erro ao fazer o upload da imagem.";
        }
    } else {
        $mensagem = "Nenhuma imagem foi enviada ou ocorreu um erro no upload.";
    }
}
?>