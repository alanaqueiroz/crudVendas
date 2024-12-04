
## TESTEPHP-HCOSTA

## Descrição

Este projeto é a resolução do desafio do processo seletivo para a vaga de programador PHP/Laravel na empresa HCOSTA. 

Consiste na implementação uma aplicação Web utilizando o framework PHP Laravel, e o banco de dados relacional MySQL, desenvolvendo em um ambiente Docker. 

- Data de Início: `02/12/2024`
- Data de Conclusão: `04/12/2024`

Desafio: [Arquivo-TestePHP](github.com/alanaqueiroz/testephp-hcosta/README/Arquivo-TestePHP)

---

## Estrutura do Projeto

Aqui está a estrutura de pastas e a explicação de suas funções:

```
/testephp-hcosta
├── .docker/ (Configuração banco MySQL)
├── app/
│   ├── service.pecas (API REST Laravel)
│   ├── Services/ 
│   │   └── ProductService.php (Configuração Url de consulta de item)
│   ├── cliente.php (Tela CRUD para Editar e Deletar)
│   ├── editar_pedido.php (Tela para edição do status)
│   ├── login.php (Tela de login)
│   ├── logout.php (Deslogar)
│   ├── user_adm.php (Exibição de pedidos para edição de status)
│   ├── vendas.php (Exibição dos pedidos do cliente)
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   ├── php/
│   │   └── Dockerfile
│   ├── docker-compose.yml
├── README/ (Arquivos da documentação)
├── READ.ME (Documentação)
├── .env (Acesso ao banco)
├── docker-compose.yml (Containers)
```

### Detalhes dos Arquivos

1. **docker-compose.yml**  
   Arquivo principal para gerenciar os contêineres. Deve incluir:
   - Um serviço para PHP
   - Um serviço para Nginx
   - Um serviço para MySQL
   - Um serviço para phpmyadmin

2. **Dockerfile** (dentro de `docker/php/`)  
   Define o ambiente do PHP. Adicione extensões como `pdo_mysql`, `mbstring`, etc.

3. **default.conf** (dentro de `docker/nginx/`)  
   Configuração do Nginx para servir a aplicação Laravel.

---

## REGISTRO

Aqui vou deixar um registro das etapas realizei durante o desenvolvimento do projeto

### 1. Configuração do Nginx

Criação do arquivo `docker/nginx/default.conf` certificando que as páginas de início index eram o `login.php` (Meu arquivo inicial)

```nginx
server {
    listen 80;
    index login.php login.html;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /login.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass app:9000;
        fastcgi_index login.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

---

## Requisitos

Para rodar o projeto, certifique-se de ter os seguintes softwares instalados em sua máquina:

1. **Docker**  
   Instale o Docker de acordo com seu sistema operacional:  
   - [Docker Desktop](https://www.docker.com/products/docker-desktop) (Windows/Mac)  
   - [Docker Engine](https://docs.docker.com/engine/install/) (Linux)

2. **Docker Compose**  
   Certifique-se de que o Docker Compose está instalado (normalmente incluído no Docker Desktop).  
   Verifique a instalação executando:
   ```bash
   docker-compose --version
   ```

3. **Composer** (opcional para criar o projeto Laravel localmente antes de usar o Docker)  
   Instale o Composer seguindo as instruções no [site oficial](https://getcomposer.org/).

---

## Acesso ao projeto

Para rodar o projeto, segui os passos a seguir:

### 1. Clone o Repositório

Clonar meu projeto:
```bash
git clone https://github.com/alanaqueiroz/testephp-hcosta.git
cd testephp-hcosta
```
### 2. Suba os Contêineres

Execute o seguinte comando na raiz do projeto:
```bash
docker-compose up -d
```

### 3. Crie o banco de dados

No diretório principal desse projeto, há um arquivo SQL com o nome `sistema_vendas.sql`, nele deixei os comandos necessarios para criar o banco. Rode-os em um gerenciador de banco de dados da sua preferência. 

Utilizei o [MySQL-Front](https://mysql-front.software.informer.com/download/).

Caso opte por gerar o banco pelo MyPHPAdmin, ele fica disponível `localhost:8888`, conforme a porta configurada do arquivo `docker-composer.yml`.

### 4. Rodando o Projeto

- Servidor Local: Você pode visualizar o projeto em um servidor local, por exemplo o [XAMPP](https://www.apachefriends.org/download.html), colocando a pasta do projeto dentro da pasta `htdocs` e acessar a url `http://localhost/testephp-hcosta/app/login.php`. 

---

## Solução de Problemas

1. **Erro de permissões no diretório `storage` ou `bootstrap/cache`**  
   Dentro do contêiner PHP, execute:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Problemas ao instalar pacotes com Composer**  
   Certifique-se de estar no contêiner PHP:
   ```bash
   docker exec -it laravel-app bash
   composer install
   ```
   
   Caso veja que o composer não está sendo instalado, verifique se o diretório php esta configurado nas Variáveis do Sistema. Se estiver e mesmo assim continuar, verifique se a versão do Laravel é compatível com seu PHP via terminal:
   ```bash
   php -v
   ```