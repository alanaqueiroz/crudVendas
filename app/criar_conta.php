<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'sistema_vendas');

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Conta criada com sucesso!'); window.location.href='login.php';</script>";
    } else {
        echo "<p>Erro ao criar conta: " . $stmt->error . "</p>";
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
    <title>Criar Conta</title>
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
        .create-account-container {
            background: #800020;
            color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .create-account-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #fff;
        }
        .create-account-container form {
            display: flex;
            flex-direction: column;
        }
        .create-account-container input, .create-account-container select {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .create-account-container button {
            padding: 10px;
            background: #a31d26;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .create-account-container button:hover {
            background: #540d12;
        }
        .create-account-container p {
            color: #FFC0CB;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="create-account-container">
        <h1>Criar Conta</h1>
        <form method="POST" action="criar_conta.php">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <select name="role" required>
                <option value="vendedor">Vendedor</option>
                <option value="cliente">Cliente</option>
            </select>
            <button type="submit">Criar Conta</button>
        </form>
    </div>
</body>
</html>
