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

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

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
        $stmt->close();
        $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $delete_stmt->bind_param('i', $order_id);
        
        if ($delete_stmt->execute()) {
            $success_message = "Pedido excluído com sucesso!";
        } else {
            $error_message = "Erro ao excluir o pedido!";
        }

        $delete_stmt->close();
    } else {
        $error_message = "Pedido não encontrado ou você não tem permissão para excluí-lo.";
    }
} else {
    $error_message = "Pedido inválido!";
}

$conn->close();

if (isset($success_message) || isset($error_message)) {
    header("Location: pedidos.php?msg=" . urlencode(isset($success_message) ? $success_message : $error_message));
    exit();
}
?>