<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'sistema_vendas');

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: vendas.php");
            exit();
        } else {
            $error = "Senha incorreta!";
        }
    } else {
        $error = "Usuário não encontrado!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #FA8072;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .login-container {
        background: #800020;
        color: #fff;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
        max-width: 400px;
    }
    .login-container h1 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #fff;
    }
    .login-container form {
        display: flex;
        flex-direction: column;
    }
    .login-container input {
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    .login-container button {
        padding: 10px;
        background: #a31d26;
        border: none;
        color: #fff;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .login-container button:hover {
        background: #540d12;
    }
    .login-container p {
        color: #FFC0CB;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </div>
</body>
</html>
