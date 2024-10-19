<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Depuração: Exibir email e senha recebidos
    echo "Email recebido: $email<br>";
    echo "Senha recebida: $senha<br>";

    $sql = "SELECT idusuarios, email, senha FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Depuração: Exibir número de linhas encontradas
        echo "Número de linhas encontradas: " . $result->num_rows . "<br>";

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Depuração: Exibir os dados do usuário encontrados
            echo "Usuário encontrado: ID - " . $row['idusuarios'] . ", Email - " . $row['email'] . "<br>";
            if (password_verify($senha, $row['senha'])) {
                $_SESSION['idusuarios'] = $row['idusuarios'];
                $_SESSION['email'] = $row['email'];

                // Depuração: Exibir confirmação de sessão configurada
                echo "Sessão configurada com sucesso.<br>";
                echo "Sessão idusuarios: " . $_SESSION['idusuarios'] . "<br>";
                echo "Sessão email: " . $_SESSION['email'] . "<br>";

                // Redirecionar após login bem-sucedido
                header('Location: index.php');
                exit();
            } else {
                echo "Senha incorreta.<br>";
            }
        } else {
            echo "Nenhum usuário encontrado com esse email.<br>";
        }
    } else {
        echo "Erro na preparação da consulta: " . $conexao->error . "<br>";
    }
}

// Verificar se é uma requisição para redefinir a senha
$email_enviado = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email_esqueci'])) {
    $email = $_POST['email_esqueci'];

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

        // Salva o token no banco de dados
        $sql = "UPDATE usuarios SET reset_token = ?, reset_token_expira = ? WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sss", $token, $expira_em, $email);
        $stmt->execute();

        // Simulação do envio de email para redefinição
        $reset_link = "http://localhost/indicaeganhe/redefinir_senha.php?token=$token";
        // mail($email, "Redefinição de senha", "Clique no link para redefinir sua senha: $reset_link");
        
        // Definimos a variável de controle
        $email_enviado = true;
    } else {
        $email_enviado = false;
    }
}
?>

<!doctype html>
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
    <header>
        <!-- MENU -->
        <?php include('menu.php'); ?>
    </header>
    
    <main>
        <div class="container mt-5">
            <div class="col-md-6">
                <div class="row left-content-md-center">
                    <div class="col col-lg-8">
                        <p class="d-inline-flex gap-1">
                            <!-- Botão Login está ativo por padrão -->
                            <button type="button" id="login-btn" class="btn btn-bd-primary active" aria-pressed="true"><i class="bi bi-clock me-2"></i>Login</button>
                            <button type="button" id="register-btn" class="btn btn-bd-primary" aria-pressed="false"><i class="bi bi-trophy me-2"></i>Cadastre-se</button>
                        </p>
                    </div>
            
                    <div class="promo-card pb-3">
                        <div class="card-body">
                            <div class="row position-relative pb-2">
                                <div id="content-area" class="card-body">
                                    <!-- Área de Login (exibida por padrão) -->
                                    <div id="login-form" class="sm-4">
                                        <h5 class="card-title pb-3 text-center">Bem vindo! Faça seu Login</h5>
                                        <form action="login.php" method="POST">
                                            <div class="input-group mb-3">
                                                <input type="text" name="email" placeholder="Email" class="form-control" required>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="password" name="senha" placeholder="Senha" class="form-control" required>
                                            </div>
                                            <div class="d-grid gap-2 d-md-block">
                                                <button type="submit" class="btn btn-bd-primary">Entrar</button>
                                                <!-- Botão que abre o modal de esqueci a senha -->
                                                <button class="btn btn-bd-primary" type="button" data-bs-toggle="modal" data-bs-target="#esqueciSenhaModal">Esqueci a senha</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Modal de Esqueci a Senha -->
                                    <div class="modal fade" id="esqueciSenhaModal" tabindex="-1" aria-labelledby="esqueciSenhaModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="esqueciSenhaModalLabel">Recuperar Senha</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="login.php" method="POST">
                                                        <div class="mb-3">
                                                            <label for="emailEsqueciSenha" class="form-label">Digite seu email para redefinir a senha:</label>
                                                            <input type="email" name="email_esqueci" class="form-control" id="emailEsqueciSenha" placeholder="Email" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-bd-primary">Enviar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Confirmação de Envio de Email -->
                                    <div class="modal fade" id="emailEnviadoModal" tabindex="-1" aria-labelledby="emailEnviadoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="emailEnviadoModalLabel">Email Enviado</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if ($email_enviado === true): ?>
                                                        Um email com instruções para redefinir sua senha foi enviado. Verifique sua caixa de entrada.
                                                    <?php elseif ($email_enviado === false): ?>
                                                        Não encontramos nenhum usuário com esse email. Tente novamente.
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Área de Cadastro (escondida por padrão) -->
                                    <div id="register-form" style="display: none;">
                                        <h5 class="card-title pb-3 text-center">Crie sua conta</h5>
                                        <form action="cadastro_usuario.php" method="POST">
                                            <div class="mb-3">
                                                <label for="nickname" class="form-label">Nome de usuário</label>
                                                <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Crie um nome para seu usuário" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="senha" class="form-label">Senha</label>
                                                <input type="password" id="senha" name="senha" class="form-control" aria-describedby="passwordHelpInline" required>
                                                <div id="passwordHelpInline" class="form-text">
                                                    A senha deve ter entre 8 e 20 caracteres.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="cidade" class="form-label">Cidade</label>
                                                <input type="text" class="form-control" id="cidade" name="cidade" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="estado" class="form-label">Estado</label>
                                                <select id="estado" class="form-select" name="estado" required>
                                                    <option selected disabled>Escolha...</option>
                                                    <option>Rio Grande do Sul</option>
                                                    <option>Santa Catarina</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-bd-primary">Cadastrar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <!-- Rodapé -->
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <!-- Script de alternância de formulários -->
    <script>
        class FormToggle {
            constructor(loginBtnId, registerBtnId, loginFormId, registerFormId) {
                this.loginBtn = document.getElementById(loginBtnId);
                this.registerBtn = document.getElementById(registerBtnId);
                this.loginForm = document.getElementById(loginFormId);
                this.registerForm = document.getElementById(registerFormId);

                this.showLoginForm();
                this.attachEventListeners();
            }

            showLoginForm() {
                this.loginForm.style.display = 'block';
                this.registerForm.style.display = 'none';
                this.loginBtn.classList.add('active');
                this.registerBtn.classList.remove('active');
            }

            showRegisterForm() {
                this.loginForm.style.display = 'none';
                this.registerForm.style.display = 'block';
                this.registerBtn.classList.add('active');
                this.loginBtn.classList.remove('active');
            }

            attachEventListeners() {
                this.loginBtn.addEventListener('click', () => this.showLoginForm());
                this.registerBtn.addEventListener('click', () => this.showRegisterForm());
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            new FormToggle('login-btn', 'register-btn', 'login-form', 'register-form');
        });

        // Exibir modal de confirmação de envio de email se $email_enviado for true ou false
        <?php if (!is_null($email_enviado)) { ?>
            var myModal = new bootstrap.Modal(document.getElementById('emailEnviadoModal'));
            myModal.show();
        <?php } ?>
    </script>
</body>
</html>
