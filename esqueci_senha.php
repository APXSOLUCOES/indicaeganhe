<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verifica se o email existe no banco de dados
    $sql = "SELECT idusuarios FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Gera um token de redefinição de senha
        $token = bin2hex(random_bytes(50));
        $expira_em = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Salva o token e o tempo de expiração no banco de dados (tabela usuarios)
        $sql = "UPDATE usuarios SET reset_token = ?, reset_token_expira = ? WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sss", $token, $expira_em, $email);
        $stmt->execute();

        // Enviar email com PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // Defina o servidor SMTP do Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'falecomapx@gmail.com'; // Seu endereço de email Gmail
            $mail->Password   = 'blelgaavnudwrbgt '; // Sua senha ou senha de app (veja abaixo)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Destinatários
            $mail->setFrom('seu-email@gmail.com', 'Nome do Remetente');
            $mail->addAddress($email);  // Email do destinatário

            // Conteúdo do email
            $mail->isHTML(true);
            $mail->Subject = 'Redefinição de senha';
            $reset_link = "http://localhost/indicaeganhe/redefinir_senha.php?token=$token";
            $mail->Body    = "Clique no link para redefinir sua senha: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo 'Um email com instruções foi enviado.';
        } catch (Exception $e) {
            echo "Erro ao enviar o email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Nenhuma conta encontrada com esse email.";
    }
}
?>
