<?php

// Inclua o arquivo de configuração do banco de dados
include 'config.php';

// Verifique se o formulário foi enviado
if($_SERVER['REQUEST_METHOD'] === 'POST') {    
    // Coletando os valores do formulário
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $hash = password_hash($senha, PASSWORD_DEFAULT);

    // Preparar e executar a consulta SQL para inserir os dados no banco de dados
    $query = $conexao->prepare("INSERT INTO usuarios (nickname, email, senha, cidade, estado) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssss", $nickname, $email, $hash, $cidade, $estado);
    $query->execute();

    // Verificar se a inserção foi bem-sucedida
    if($query) {
        // Redirecionar para a página index.php
        header("Location: index.php");
        exit(); // Certifique-se de que a execução do script é encerrada após o redirecionamento
    } else {
        $mensagem = "Erro ao inserir dados no banco de dados: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<body>

<!-- Scrollable modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal-dialog modal-dialog-scrollable">
    <form action="cadastro_usuario.php" method="POST">
        <div class="col-md-6">
            <label for="nickname" class="form-label">Nome de usuário</label>
            <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Crie um nome para seu usuário">
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-mt-3">
                <label for="senha" class="col-form-label">Senha</label>
            </div>
            <div class="col-auto">
                <input type="password" id="senha" name="senha" class="form-control" aria-describedby="passwordHelpInline">
            </div>
            <div class="col-auto">
                <span id="passwordHelpInline" class="form-text">
                Must be 8-20 characters long.
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <label for="Cidade" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="cidade" name="cidade">
        </div>
        <div class="col-md-4">
            <label for="Estado" class="form-label">Estado</label>
            <select id="Estado" class="form-select" name="estado">
                <option selected>Choose...</option>
                <option>Rio Grande do Sul</option>
                <option>Santa Catarina</option>
            </select>
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary" name="submit">Sign in</button>
        </div>
    </form>
    <!-- Exibindo a mensagem -->
    <?php if (!empty($mensagem)): ?>
        <div class="mt-3">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>
</div>
    

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
