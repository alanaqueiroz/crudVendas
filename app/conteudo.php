<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Pega informações do usuário logado
$user_id = $_SESSION['user_id'];
$user_tipo = $_SESSION['user_tipo'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Conteúdo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .login-container, .conteudo-container {
            width: 300px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .erro-login {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .conteudo-container {
            text-align: center;
        }

        .conteudo-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .conteudo-container a:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="conteudo-container">
        <h2>Bem-vindo à Página de Conteúdo!</h2>
        <p>Você está logado com sucesso.</p>
        <p>Aqui você pode colocar qualquer conteúdo exclusivo para usuários autenticados.</p>
        <a href="logout.php">Sair</a>
    </div>
</body>
</html>