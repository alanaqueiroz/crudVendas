<!-- Tela de Login -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="/login" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>

            <label for="user-type">Tipo de usuário:</label>
            <select id="user-type" name="user-type">
                <option value="cliente">Cliente</option>
                <option value="vendedor">Vendedor</option>
                <option value="administrador">Administrador</option>
            </select>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
