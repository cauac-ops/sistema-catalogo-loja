<?php require_once __DIR__ . '/../config/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= $page_title ?? 'Minha Loja' ?></title>
<meta name="description" content="<?= $page_desc ?? 'Produtos de qualidade para você.' ?>"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
<link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet"/>
</head>
<body>

<div class="mobile-nav" id="mobileNav">
  <a href="<?= BASE_URL ?>/#hero" onclick="closeMobile()">Início</a>
  <a href="<?= BASE_URL ?>/#produtos" onclick="closeMobile()">Produtos</a>
  <a href="<?= BASE_URL ?>/#sobre" onclick="closeMobile()">Sobre</a>
  <a href="<?= BASE_URL ?>/#contato" onclick="closeMobile()">Contato</a>
</div>

<header class="header" id="header">
  <a href="<?= BASE_URL ?>/" class="logo">Minha<span>Loja</span></a>
  <nav>
    <a href="<?= BASE_URL ?>/#hero">Início</a>
    <a href="<?= BASE_URL ?>/#produtos">Produtos</a>
    <a href="<?= BASE_URL ?>/#sobre">Sobre</a>
    <a href="<?= BASE_URL ?>/#contato" class="nav-cta">Contato</a>
  </nav>
  <button class="hamburger" id="hamburger" onclick="toggleMobile()">
    <span></span><span></span><span></span>
  </button>
</header>
