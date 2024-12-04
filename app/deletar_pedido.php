<?php
    session_start();

    // Verifica se o usuário está autenticado
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        redirecionarParaLogin();
    }

    // Conexão com o banco de dados
    $conn = conectarBancoDeDados();

    // Recupera os dados da sessão
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];

        // Processa a exclusão do pedido
        $message = processarExclusaoPedido($conn, $order_id, $user_id, $role);
    } else {
        $message = "Pedido inválido!";
    }

    $conn->close();

    // Redireciona com a mensagem de sucesso ou erro
    redirecionarComMensagem($message);

    // Funções auxiliares
    function conectarBancoDeDados() {
        $conn = new mysqli('localhost', 'root', '', 'sistema_vendas');
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        return $conn;
    }

    function redirecionarParaLogin() {
        header("Location: login.php");
        exit();
    }

    function redirecionarComMensagem($message) {
        header("Location: pedidos.php?msg=" . urlencode($message));
        exit();
    }

    function processarExclusaoPedido($conn, $order_id, $user_id, $role) {
        // Prepara a consulta de verificação
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
            // Pedido encontrado, tenta excluir
            return excluirPedido($conn, $order_id);
        } else {
            return "Pedido não encontrado ou você não tem permissão para excluí-lo.";
        }
    }

    function excluirPedido($conn, $order_id) {
        $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $delete_stmt->bind_param('i', $order_id);
        
        if ($delete_stmt->execute()) {
            return "Pedido excluído com sucesso!";
        } else {
            return "Erro ao excluir o pedido!";
        }
    }
?>