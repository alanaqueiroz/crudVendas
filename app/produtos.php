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

    // Pegando os parâmetros de filtro e ordenação da URL
    $filter_name = isset($_GET['name']) ? $_GET['name'] : '';
    $filter_price = isset($_GET['price']) ? $_GET['price'] : '';
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id'; // Ordenar por ID por padrão
    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; // Ordem crescente por padrão
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual
    $limit = 20; // Limite de 20 produtos por página
    $offset = ($page - 1) * $limit; // Offset para paginação

    // Verifica se o filtro de preço foi informado e ajusta para pegar apenas a parte inteira
    if (!empty($filter_price)) {
        $filter_price = str_replace(',', '', $filter_price);
        $filter_price = floor(floatval($filter_price));
        $min_price = (int)$filter_price;
        $max_price = $min_price + 1;
    } else {
        $min_price = 0;
        $max_price = PHP_INT_MAX;
    }
    
    // Agora, sua consulta SQL usará $min_price e $max_price corretamente
    $query = "SELECT * FROM products WHERE name LIKE ? AND price >= ? AND price < ? ORDER BY $sort_by $sort_order LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    
    // Validar consulta preparada
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    
    // Adicionar caracteres '%' ao filtro para SQL
    $sql_filter_name = "%$filter_name%";
    
    // Vincular parâmetros na consulta
    $stmt->bind_param("ssiii", $sql_filter_name, $min_price, $max_price, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    

    // Contando o total de produtos para a paginação
    $count_query = "SELECT COUNT(*) FROM products WHERE name LIKE ? AND price >= ? AND price < ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("ssi", $filter_name, $min_price, $max_price);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_items = $count_result->fetch_row()[0];
    $total_pages = ceil($total_items / $limit); // Calculando o número total de páginas
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produtos</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #d4d4d4;
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
                <h1 style="margin: 0; flex-grow: 1; text-align: center;">Site de Vendas</h1>
                <span style="font-size: 14px; color: white;">
                    Logado como: <?php echo ($role == 'vendedor') ? 'Vendedor' : 'Cliente'; ?>
                </span>
            </div>
        </header>

        <div class="content">
            <a href="pedidos.php" class="btn center-btn">Ver Pedidos</a>
            <br><br>
            <h2>Produtos à Venda</h2>

            <!-- Filtros -->
            <form method="get" action="">
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($filter_name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nome do produto">
                <label for="price">Preço:</label>
                <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($filter_price, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Preço do produto">
                <button type="submit" class="btn">Filtrar</button>
            </form>

            <form method="post" action="pedidos.php">
            <table>
            <tr>
                <th><a href="?name=<?php echo urlencode($filter_name); ?>&price=<?php echo urlencode($filter_price); ?>&sort_by=id&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">ID</a></th>
                <th><a href="?name=<?php echo urlencode($filter_name); ?>&price=<?php echo urlencode($filter_price); ?>&sort_by=name&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Nome</a></th>
                <th><a href="?name=<?php echo urlencode($filter_name); ?>&price=<?php echo urlencode($filter_price); ?>&sort_by=price&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Preço</a></th>
                <th>Selecionar</th> <!-- Adiciona um título para a coluna de checkboxes -->
            </tr>
            <?php while ($product = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo "R$ " . number_format($product['price'], 2, ',', '.'); ?></td>
                    <td><input type="checkbox" name="product_ids[]" value="<?php echo htmlspecialchars($product['id']); ?>"></td>
                </tr>
            <?php } ?>
        </table>
                <button type="submit" class="btn center-btn">Fazer Pedido</button>
            </form>

            <!-- Paginação -->
            <div class="pagination">
                <?php if ($page > 1) { ?>
                    <a href="?name=<?php echo urlencode($filter_name); ?>&price=<?php echo urlencode($filter_price); ?>&page=<?php echo $page - 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">Anterior</a>
                <?php } ?>
                Página <?php echo $page; ?> de <?php echo $total_pages; ?>
                <?php if ($page < $total_pages) { ?>
                    <a href="?name=<?php echo urlencode($filter_name); ?>&price=<?php echo urlencode($filter_price); ?>&page=<?php echo $page + 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">Próximo</a>
                <?php } ?>
            </div>
        </div>
    </body>
</html>

<?php
$stmt->close();
$count_stmt->close();
$conn->close();
?>