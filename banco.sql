CREATE DATABASE sistema_vendas;

USE sistema_vendas;

-- Tabela de Usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('cliente', 'vendedor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Pedidos
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('pendente', 'pago', 'finalizado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabela de Produtos (opcional apenas como historico se quiser salvar produtos localmente)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Tabela de Produtos (EXTERNO: criar dentro da API REST)
CREATE TABLE produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    categoria VARCHAR(255),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Produtos dos Pedidos
CREATE TABLE order_products (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Inserir Usuários
INSERT INTO users (username, password, role) 
VALUES 
    ('cliente', 'senha123', 'cliente'),
    ('vendedor', 'senha123', 'vendedor');

-- Inserir Produtos (caso mantenha localmente)
INSERT INTO products (name, price) VALUES 
    ('Produto 1', 10.50),
    ('Produto 2', 20.00),
    ('Produto 3', 15.75),
    ('Produto 4', 30.99),
    ('Produto 5', 8.25),
    ('Produto 6', 12.60),
    ('Produto 7', 25.00),
    ('Produto 8', 5.99),
    ('Produto 9', 18.49),
    ('Produto 10', 22.90),
	('Produto 11', 45.99),
	('Produto 12', 32.50),
	('Produto 13', 12.99),
	('Produto 14', 60.00),
	('Produto 15', 25.75),
	('Produto 16', 40.30),
	('Produto 17', 15.49),
	('Produto 18', 22.20),
	('Produto 19', 18.75),
	('Produto 20', 35.10);

-- Inserção de Pedidos
INSERT INTO orders (user_id, status) VALUES 
    (1, 'pendente'),
    (2, 'pendente');

-- Inserção de Produtos nos Pedidos
INSERT INTO order_products (order_id, product_id, quantity) VALUES 
    (1, 1, 2), -- Pedido 1 contém 2 unidades do Produto 1
    (1, 3, 1), -- Pedido 1 contém 1 unidade do Produto 3
    (2, 4, 5), -- Pedido 2 contém 5 unidades do Produto 4
    (2, 10, 3); -- Pedido 2 contém 3 unidades do Produto 10