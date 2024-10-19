<?php


    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'formulario-usuario';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Verificar conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
?>
