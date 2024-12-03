<<<<<<< HEAD
# hcosta-testephp
=======
# Teste PHP Trainee/Junior Hcosta 

## Sobre o ambiente da aplicação: 

O desafio consiste em implementar uma aplicação Web utilizando o framework PHP
Laravel, e um banco de dados relacional SQLite, MySQL ou Postgres. 

## Avisos antes de começar 

- Crie um repositório no seu GitHub.
- Criação do ambiente com Docker.

## Objetivo:

Criar um sistema de vendas.
Temos 2 tipos de usuários, os Clientes e Vendedores. 

- Tela de login
- CRUD de Clientes
  - Criar, alterar, deletar e listar 
- CRUD de Pedido:
  - Criar, alterar, deletar e listar

## Requisitos:

- Na tela de vendas os clientes poderão ver apenas as suas compras e o status do seu
pedido (Pendente, pago, finalizado).
- Na tela dos usuários administrativos ele poderá ver todas as compras e alterar os
status.
- Na tela de vendas os clientes só poderão alterar seu pedido enquanto o pedido
estiver como pendente.
- Deve ser filtrável e ordenável por qualquer campo, e possuir paginação de 20 itens.
- Os produtos do pedido serão consumidos de um serviço externo (API REST) com o
preço do dia.

Repositório da API de produtos
https://github.com/costadouglashc/service.pecas
Url de consulta de item 127.0.0.1:8000/getItem?produto=3
Url de consulta de itens 127.0.0.1:8000/getItens

## O que será avaliado e valorizamos

- Documentação
- Código limpo e organizado
- Conhecimento de padrões e conceitos (Design Patterns, SOLID, Clean Code)
- Modelagem de Dados
- Tratamento de erros
- Cuidado com itens de segurança
- Arquitetura
- Lógica
>>>>>>> 11965da (empurrando projeto)
