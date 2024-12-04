<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sistema_vendas');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'cliente') {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $order_id, $user_id);
} else {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param('i', $order_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();

    if ($order['status'] == 'pendente') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_status = $_POST['status'];
            $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $update_stmt->bind_param('si', $new_status, $order_id);
            if ($update_stmt->execute()) {
                $success_message = "Pedido alterado com sucesso!";
            } else {
                $error_message = "Erro ao alterar pedido!";
            }
            $update_stmt->close();
        }
    } else {
        $error_message = "O pedido so pode ser alterado se estiver PENDENTE";
    }
} else {
    $error_message = "O pedido so pode ser alterado se estiver PENDENTE";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
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
        .container {
            background: #800020;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
        }
        label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
            color: white;
        }
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background: #007BFF;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            margin-top: 10px;
            font-size: 14px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Pedido</h1>
        <?php if (isset($success_message)) { ?>
            <p class="message success"><?php echo $success_message; ?></p>
        <?php } elseif (isset($error_message)) { ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php } ?>
        
        <?php if (isset($order) && $order['status'] == 'pendente') { ?>
            <form method="POST" action="editar_pedido.php?id=<?php echo htmlspecialchars($order['id']); ?>">
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="pendente" <?php if ($order['status'] == 'pendente') echo 'selected'; ?>>Pendente</option>
                    <option value="pago" <?php if ($order['status'] == 'pago') echo 'selected'; ?>>Pago</option>
                    <option value="finalizado" <?php if ($order['status'] == 'finalizado') echo 'selected'; ?>>Finalizado</option>
                </select>
                <button type="submit">Alterar Status</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
