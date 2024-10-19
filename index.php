<?php
    session_start();

// BANCO DE DADOS

    // Inclua o arquivo de configuração do banco de dados
    include 'config.php';

    if (isset($_SESSION['email'])) {
        // Pegando o ID do usuário com base no email
        $email = $_SESSION['email'];
        $query = $conexao->prepare("SELECT idusuarios FROM usuarios WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['idusuarios'] = $row['idusuarios'];
        } else {
            echo "Erro ao obter o ID do usuário.";
            exit();
        }

        $logado = $_SESSION['email'];
        $id_usuario_logado = $_SESSION['idusuarios'];
    } else {
        $logado = false;
    }
?>

<!-- HTML -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indica e Ganhe!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dist/css/custom-bootstrap.css">
    <link rel="stylesheet" href="css/styles.css?v=1">
</head>

<body>

<!-- MENU -->

<?php include 'menu.php'; ?>

<!-- CONTEÚDO PRINCIPAL -->

<div class="container mt-5">
    <div class="col-md-8">
        <div class="row left-content-md-center">
            <div class="col col-lg-8">
                <p class="d-inline-flex gap-1">
                    <button type="button" class="btn btn-bd-primary" data-bs-toggle="button" aria-pressed="true"><i class="bi bi-clock me-2"></i>Mais Recentes</button>
                    <button type="button" class="btn btn-bd-primary" data-bs-toggle="button" aria-pressed="false"><i class="bi bi-trophy me-2"></i>Promos Destaques</button>
                </p>
            </div>
        </div>

        <div class="row justify-content-center align-items-center">
        <?php
        // Consulta SQL para selecionar as últimas promos cadastradas
        $sql = "SELECT p.idpromos, p.nome_promo, p.site_promo, p.img_promo, u.nickname, p.data_promo FROM promos p JOIN usuarios u ON p.idusuarios = u.idusuarios ORDER BY p.data_promo DESC LIMIT 20";

        $resultado = $conexao->query($sql);

        if ($resultado->num_rows > 0) {
            // Exibir os dados dentro do loop while
            while ($row = $resultado->fetch_assoc()) {
                // Verifique se 'idpromos' está definido no resultado da consulta
                if (!isset($row["idpromos"])) {
                    echo '<div class="alert alert-danger" role="alert">Erro: A coluna "idpromos" não foi encontrada no resultado da consulta.</div>';
                    continue;
                }
                // Formatar a data no formato desejado (Ex: DD/MM/AAAA)
                $data_promo_formatada = date("d/m/Y", strtotime($row["data_promo"]));
                // Definir a URL "Ver Mais"
                $verMaisUrl = 'promo.php?id=' . htmlspecialchars($row["idpromos"]);
               
                // Card Id Promo"
                echo '<div class="promo-card pb-3">';
                    echo '<div class="card-body">';
                        echo '<div class="d-flex pb-2 justify-content-between">';
                            echo '<div class="col-10">';
                            // Usuário criador
                            echo '<div class="fw-light text-fraco fs-6">Oferta postada por: <span class="fw-medium">' . htmlspecialchars($row["nickname"]) . '</span> em ' . $data_promo_formatada . '</div>'; 
                            echo '</div>';
                            echo '<div class="col-2 text-end">';
                            // Botão de Compartilhamento
                                echo '<button class="btn shareButton" data-url="' . $verMaisUrl . '">';
                                    echo '<i class="bi bi-share-fill" style="font-size: 1rem;"></i>';
                                echo '</button>';
                            echo '</div>';
                        echo '</div>';
                        
                        echo '<div class="row">';
                            // Coluna 1 Imagem
                            echo '<div class="col-3">';
                            if (!empty($row["img_promo"])) {
                                $img_path = 'uploads/' . basename($row["img_promo"]); // basename garante que apenas o nome do arquivo é utilizado
                                // Verificar se o arquivo existe
                                if (file_exists($img_path)) {
                                    echo '<img src="' . htmlspecialchars($img_path) . '" class="img-fluid rounded float-start" alt="' . htmlspecialchars($row["nome_promo"]) . '">';
                                } else {
                                    echo '<img src="uploads/sem_foto.png" class="img-fluid rounded float-start" alt="Imagem padrão">';
                                }
                            } else {
                                echo '<img src="uploads/sem_foto.png" class="img-fluid rounded float-start" alt="Imagem padrão">';
                            }
                            echo '</div>';
                            
                            // Coluna 2 Conteúdo
                            echo '<div class="col-9">';
                                echo '<div class="row">';
                                    echo '<div class="col">';
                                        // Nome Promo
                                        echo '<h5 class="card-title">' . htmlspecialchars($row["nome_promo"]) . '</h5>';
                                    echo '</div>';
                                echo '</div>';
                                echo '<div class="row">';
                                    echo '<div class="col pt-2">';
                                        // Site Promo
                                        echo '<p class="card-text">' . htmlspecialchars($row["site_promo"]);
                                    echo '</div>';
                                echo '</div>';
                                echo '<div class="row">';
                                    echo '<div class="col mt-2">';
                                        // botão "Ver Mais"
                                        $verMaisUrl = 'promo.php?id=' . htmlspecialchars($row["idpromos"]);
                                        echo '<a href="' . $verMaisUrl . '" class="btn btn-bd-primary"><i class="bi bi-caret-right me-2"></i>Ver Oferta</a>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo "Nenhum resultado encontrado.";
        }
        ?>
        </div>
        
        <!-- Adicione mais promoções conforme necessário -->
    </div>
    

   
</div>

    <!-- RODAPÉ -->



<!-- SCRIPT -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js">

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js">

</script>

<!-- SCRIPT DE COMPATILHAMENTO -->
<script>
    document.querySelectorAll('.shareButton').forEach(button => {
        button.addEventListener('click', async function() {
            const urlToShare = this.getAttribute('data-url'); // Obtém o URL do botão "Ver Mais" correspondente

            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'Confira esta promoção!',
                        text: 'Veja mais detalhes sobre esta promoção:',
                        url: urlToShare
                    });
                    console.log('Link compartilhado com sucesso');
                } catch (err) {
                    console.error('Erro ao compartilhar:', err);
                }
            } else {
                alert('API de compartilhamento não suportada neste navegador');
            }
        });
    });
</script>

<!-- SCRIPT DE BOTÕES ESTILO RADIO -->
<script>
    // Seleciona todos os botões dentro do grupo
    const buttons = document.querySelectorAll('.btn-bd-primary');

    // Função para ativar um botão e desativar os outros
    function activateButton(button) {
        buttons.forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-pressed', 'false');
        });
        button.classList.add('active');
        button.setAttribute('aria-pressed', 'true');
    }

    // Ativa o primeiro botão por padrão
    activateButton(buttons[0]);

    // Adiciona um evento de clique a cada botão
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            activateButton(this);
        });
    });
</script>

</body>
</html>