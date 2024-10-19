<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $nova_senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nova_senha === $confirma_senha) {
        // Verifica o token na tabela usuarios
        $sql = "SELECT email, reset_token_expira FROM usuarios WHERE reset_token = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verifica se o token ainda é válido
            if (strtotime($row['reset_token_expira']) > time()) {
                // Atualiza a senha no banco de dados
                $email = $row['email'];
                $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);

                $sql = "UPDATE usuarios SET senha = ?, reset_token = NULL, reset_token_expira = NULL WHERE email = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ss", $hash_senha, $email);
                $stmt->execute();

                echo "Sua senha foi redefinida com sucesso!";
            } else {
                echo "Este link expirou.";
            }
        } else {
            echo "Token inválido.";
        }
    } else {
        echo "As senhas não coincidem.";
    }
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "Token não fornecido.";
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
</head>
<body>
    <form action="redefinir_senha.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label for="senha">Nova senha:</label>
        <input type="password" name="senha" required>
        <label for="confirma_senha">Confirme a senha:</label>
        <input type="password" name="confirma_senha" required>
        <button type="submit">Redefinir Senha</button>
    </form>
</body>
</html>
