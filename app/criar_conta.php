<?php
    // Função para criar a conexão com o banco de dados
    function criarConexao() {
        $conn = new mysqli('localhost', 'root', '', 'sistema_vendas');
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        return $conn;
    }

    // Função para criar uma nova conta
    function criarConta($username, $password, $role) {
        $conn = criarConexao();

        // Preparar consulta SQL
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $password, $role);

        // Executar consulta e tratar sucesso ou erro
        if ($stmt->execute()) {
            echo "<script>alert('Conta criada com sucesso!'); window.location.href='login.php';</script>";
        } else {
            echo "<p>Erro ao criar conta: " . $stmt->error . "</p>";
        }

        // Fechar a conexão
        $stmt->close();
        $conn->close();
    }

    // Verifica se a requisição foi feita via POST e chama a função de criação de conta
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        criarConta($username, $password, $role);
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
            .create-account-container label {
                font-size: 14px;
                margin-bottom: 5px;
                color: #fff;
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
                <label for="username">Usuário</label>
                <input type="text" name="username" id="username" placeholder="Usuário" required>

                <label for="password">Senha</label>
                <input type="password" name="password" id="password" placeholder="Senha" required>

                <label for="role">Cargo</label>
                <select name="role" id="role" required>
                    <option value="vendedor">Vendedor</option>
                    <option value="cliente">Cliente</option>
                </select>

                <button type="submit">Criar Conta</button>
            </form>
        </div>
    </body>
</html>