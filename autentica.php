<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include 'config.php';
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Usando prepared statements para prevenir SQL Injection
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        // Usuário não encontrado
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php?msg=erro');
        exit();
    } else {
        $user = $result->fetch_assoc();
        // Verificar a senha hash
        if (password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            $_SESSION['email'] = $email;
            header('Location: index.php');
            exit();
        } else {
            // Senha incorreta
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: login.php?msg=erro');
            exit();
        }
    }
} else {
    // Campos não preenchidos
    header('Location: login.php?msg=campos_vazios');
    exit();
}
?>
