<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sistema_vendas');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            background-color: #FA8072;
            color: white;
            padding: 10px 20px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
        }
        .content {
            margin: 20px;
            width: 90%;
            max-width: 1200px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .content h2 {
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            background-color: #a31d26;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s; 
        }
        .btn:hover {
            background-color: #540d12;
        }
        .center-btn {
            display: inline-block;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f9;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-link {
            color: #007BFF;
            text-decoration: none;
        }
        .action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <div style="background-color: #800020; display: flex; align-items: center; justify-content: space-between; padding: 0 10px;">
        <a href="logout.php" style="background-color: black; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">Sair</a>
        <h1 style="margin: 0; flex-grow: 1; text-align: center;">Painel de Vendas</h1>
    </div>
</header>

<div class="content">
    <a href="vendas.php" class="btn center-btn">Voltar para Painel de Vendas</a>
    <br><br>
    <h2>Área Administrativa</h2>
        <table>
        <tr>
            <th>Compra</th>
            <th>Status</th>
            <th>Data</th>
            <?php if ($role == 'vendedor' || $role == 'cliente') { ?>
                <th>Ação</th>
            <?php } ?>
        </tr>
        <?php while ($order = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($order['id']); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                <?php if ($role == 'vendedor' || $role == 'cliente') { ?>
                    <td>
                        <a href="editar_pedido.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="action-link">Editar Pedido</a>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
