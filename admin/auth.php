<?php
require_once __DIR__ . '/../config/config.php';
if (!isAdmin()) { header('Location: ' . BASE_URL . '/admin/login.php'); exit; }
