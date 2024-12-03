<?php
session_start();

$usuario_correto = "admin";
$senha_correta = "senha123";

$username = $_POST['username'];
$password = $_POST['password'];

if ($username == $usuario_correto && $password == $senha_correta) {
    $_SESSION['logged_in'] = true;
    header('Location: conteudo.php');
    exit();
} else {
    $_SESSION['erro_login'] = "Usuário ou senha incorretos.";
    header('Location: index.php');
    exit();
}
?> 