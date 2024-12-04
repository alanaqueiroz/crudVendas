<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendedor') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sistema_vendas');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE role = 'cliente'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
</head>
<body>
    <h1>Lista de Clientes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
        <?php while ($client = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $client['id']; ?></td>
                <td><?php echo $client['username']; ?></td>
                <td>
                    <a href="editar_cliente.php?id=<?php echo $client['id']; ?>">Editar</a>
                    <a href="deletar_cliente.php?id=<?php echo $client['id']; ?>">Deletar</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
