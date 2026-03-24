# 🛍️ Sistema de Catálogo de Produtos

<p align="center">
  <strong>🛍️ Product Catalog System</strong><br>
  <em>Sistema web para exibição e gerenciamento de produtos com painel administrativo</em>
</p>

<p align="center">
  🇧🇷 Português | 🇺🇸 <a href="#-english">English</a>
</p>

> Sistema desenvolvido em PHP para gerenciamento de catálogo de produtos, com área pública para visualização dos itens e painel administrativo para controle do conteúdo.

<p align="center">
  <img src="https://img.shields.io/badge/status-em%20desenvolvimento-yellow">
  <img src="https://img.shields.io/badge/version-1.0.0-blue">
  <img src="https://img.shields.io/badge/license-MIT-green">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white">
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white">
</p>

---

## 🇧🇷 Português

## 🚀 Sobre o projeto

Sistema web desenvolvido em PHP para gerenciamento de catálogo de produtos com painel administrativo.

---

## 🚀 Tecnologias utilizadas

- **Backend:** PHP  
- **Banco de dados:** MySQL  
- **Frontend:** HTML, CSS, Bootstrap  
- **Outros:** JavaScript

---

  ## 🔗 Demonstração

👉 Acesse o sistema:  
[Acessar o site](http://cacauteste.byethost15.com/)

👤 Acesso de teste:
- Usuário: admin  
- Senha: teste

---

## 📸 Preview do sistema

### 🏠 Página inicial
![Home](assets/Tela_ini.png)

### 🔐 Tela de login
![Login](assets/Login.png)

### 📦 Cadastro de produtos
![Cadastro](assets/Tela_cad.png)

---

## 🧠 Conceitos aplicados

- CRUD completo de produtos  
- Autenticação de usuários (admin)  
- Proteção contra CSRF  
- Organização em camadas
  
---

## ✨ Funcionalidades

### 🛍️ Área pública
- Visualização de produtos cadastrados  
- Destaque de produtos na página inicial  

### ⚙️ Painel administrativo
- Cadastro, edição e exclusão de produtos (CRUD)  
- Upload de imagens dos produtos  
- Gerenciamento completo do catálogo  

### 🔐 Segurança
- Autenticação de administrador  
- Proteção contra acesso não autorizado
  
---

## ⚙️ Instalação e execução

### 1. Clone o repositório

```
git clone https://github.com/cauac-ops/sistema-catalogo-loja.git
cd sistema-catalogo-loja
```
2. Configure o ambiente
Instale um servidor local (XAMPP, Laragon ou WAMP)
Coloque o projeto na pasta htdocs (ou equivalente)

4. Banco de dados
Crie um banco no MySQL (ex: catalogo_loja)
Importe o arquivo .sql que está no projeto

5. Configuração
Edite o arquivo de conexão com o banco:
```
$host = "localhost";
$db   = "catalogo_loja";
$user = "root";
$pass = "";
```
5. Executar
Acesse no navegador:

http://localhost/sistema-catalogo-loja

---

## 📈 Melhorias futuras

- 🔎 Busca de produtos
- 📊 Dashboard
- 📦 Paginação
- 🔐 Recuperação de senha
- 🛡️ Proteção CSRF

---

## 📄 Licença

MIT License

---

## 🇺🇸 English

## 🚀 About

PHP web system for product catalog management with an admin panel.

---

## ✨ Features

- 📦 Product listing
- ⭐ Featured products
- 🔐 Admin authentication
- ⚙️ Admin panel

---

## 📄 License

MIT License
