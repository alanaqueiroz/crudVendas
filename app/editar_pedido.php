<?php
    session_start();

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }

    // Função para inicializar a conexão com o banco
    function getDatabaseConnection() {
        $conn = new mysqli('localhost', 'root', '', 'sistema_vendas');
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        return $conn;
    }

    // Função para buscar o pedido com base no papel do usuário
    function fetchOrder($conn, $order_id, $user_id, $role) {
        if ($role == 'cliente') {
            // Verifica se o pedido pertence ao cliente
            $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
            $stmt->bind_param('ii', $order_id, $user_id);
        } else {
            $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->bind_param('i', $order_id);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    // Função para atualizar o status do pedido
    function updateOrderStatus($conn, $order_id, $new_status) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_status, $order_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    $conn = getDatabaseConnection();
    $order_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Buscar o pedido do banco de dados
    $result = fetchOrder($conn, $order_id, $user_id, $role);
    $order = $result->fetch_assoc();

    if ($order) {
        // Verificar se o status do pedido é "pendente"
        if ($order['status'] == 'pendente') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_status = $_POST['status'];
                if (updateOrderStatus($conn, $order_id, $new_status)) {
                    $success_message = "Pedido alterado com sucesso!";
                    // Redireciona para a página pedidos.php após sucesso
                    header("Location: pedidos.php");
                    exit();
                } else {
                    $error_message = "Erro ao alterar pedido!";
                }
            }
        } else {
            $error_message = "O pedido só pode ser alterado se estiver PENDENTE.";
        }
    } else {
        // Caso o pedido não seja encontrado, a mensagem de erro será mostrada
        $error_message = "Pedido não encontrado.";
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Alterar Status</title>
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
                <p class="message success"><?php echo htmlspecialchars($success_message); ?></p>
            <?php } elseif (isset($error_message)) { ?>
                <p class="message error"><?php echo htmlspecialchars($error_message); ?></p>
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
