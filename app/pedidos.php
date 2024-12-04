<?php
session_start();

// Função para validar a sessão do usuário
function validarSessao()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Função para conectar ao banco de dados
function conectarBanco()
{
    $conn = new mysqli('localhost', 'root', '', 'sistema_vendas');
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    return $conn;
}

// Função para criar um novo pedido
function criarPedido($conn, $user_id, $product_ids)
{
    // Criação do pedido
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status) VALUES (?, 'pendente')");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id; // ID do pedido recém-criado
    $stmt->close();

    // Associação dos produtos ao pedido
    $stmt = $conn->prepare("INSERT INTO order_products (order_id, product_id) VALUES (?, ?)");
    foreach ($product_ids as $product_id) {
        $stmt->bind_param('ii', $order_id, $product_id);
        $stmt->execute();
    }
    $stmt->close();

    return $order_id;
}

// Função para buscar pedidos do banco
function buscarPedidos($conn)
{
    $stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->get_result();
}

// Valida a sessão
validarSessao();

// Conecta ao banco
$conn = conectarBanco();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Verifica requisições POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_ids'])) {
    criarPedido($conn, $user_id, $_POST['product_ids']);

    // Redireciona para evitar envio duplo
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Obtém os pedidos
$pedidos = buscarPedidos($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Área Administrativa</title>
        <style>
            /* Estilos do CSS mantidos sem alterações */
        </style>
    </head>

    <body>
        <header>
            <div style="background-color: #800020; display: flex; align-items: center; justify-content: space-between; padding: 0 10px;">
                <a href="logout.php" style="background-color: black; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">Sair</a>
                <h1 style="margin: 0; flex-grow: 1; text-align: center;">Site de Vendas</h1>
                <span style="font-size: 14px; color: white;">
                    Logado como: <?php echo ($role === 'vendedor') ? 'Vendedor' : 'Cliente'; ?>
                </span>
            </div>
        </header>

        <div class="content">
            <a href="produtos.php" class="btn center-btn">Ver Produtos</a>
            <br><br>
            <h2>Pedidos</h2>
            <table>
                <tr>
                    <th>Pedido</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
                <?php while ($pedido = $pedidos->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['status']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['created_at']); ?></td>
                        <td>
                            <!-- Botão Alterar Status -->
                            <form action="editar_pedido.php" method="GET" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">
                                <button type="submit" style="background-color: #007BFF; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;">
                                    Alterar Status
                                </button>
                            </form>

                            <!-- Botão Deletar -->
                            <form action="deletar_pedido.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este pedido?');">
                                <input type="hidden" name="order_id" value="<?php echo $pedido['id']; ?>">
                                <button type="submit" style="background-color: #FF0000; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;">
                                    Deletar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </body>
</html>

<?php
// Fecha conexões e libera memória
$pedidos->close();
$conn->close();
?>