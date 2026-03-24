<?php
require_once __DIR__ . '/../config/config.php';
if (isAdmin()) { header('Location: ' . BASE_URL . '/admin/painel.php'); exit; }

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['usuario'] ?? '');
    $s = trim($_POST['senha'] ?? '');
    if ($u && $s) {
        $st = DB::get()->prepare("SELECT * FROM admin WHERE usuario = :u LIMIT 1");
        $st->execute([':u' => $u]);
        $adm = $st->fetch();
        if ($adm && password_verify($s, $adm['senha_hash'])) {
            $_SESSION['admin_logado'] = true;
            $_SESSION['admin_user']   = $adm['usuario'];
            header('Location: ' . BASE_URL . '/admin/painel.php'); exit;
        }
    }
    $erro = 'Usuário ou senha incorretos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Admin — Login</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#0a0a0a;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.box{background:#111;border:1px solid rgba(200,169,110,.2);padding:52px 44px;width:100%;max-width:420px}
.logo{font-family:'Playfair Display',serif;font-size:26px;color:#c8a96e;text-align:center;margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#555;text-align:center;margin-bottom:44px}
label{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#777;display:block;margin-bottom:8px}
input{width:100%;padding:13px 16px;background:#1a1a1a;border:1px solid rgba(200,169,110,.2);color:#f5f0e8;font-family:'DM Sans',sans-serif;font-size:14px;outline:none;transition:border-color .2s;margin-bottom:20px}
input:focus{border-color:#c8a96e}
.btn{width:100%;padding:14px;background:#c8a96e;color:#0a0a0a;font-family:'DM Sans',sans-serif;font-size:11px;font-weight:600;letter-spacing:3px;text-transform:uppercase;border:none;cursor:pointer;transition:background .2s}
.btn:hover{background:#e8c98e}
.erro{background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.3);color:#f87171;padding:12px;font-size:13px;margin-bottom:20px;display:flex;gap:8px;align-items:center;border-radius:2px}
.back{text-align:center;margin-top:20px;font-size:12px;color:#555}
.back a{color:#c8a96e}
</style>
</head>
<body>
<div class="box">
  <div class="logo">Minha Loja</div>
  <div class="sub">Área Administrativa</div>
  <?php if ($erro): ?>
  <div class="erro"><i class="bi bi-exclamation-circle"></i><?= s($erro) ?></div>
  <?php endif; ?>
  <form method="POST">
    <label>Usuário</label>
    <input type="text" name="usuario" required autofocus placeholder="admin">
    <label>Senha</label>
    <input type="password" name="senha" required placeholder="••••••••">
    <button type="submit" class="btn"><i class="bi bi-lock"></i> Entrar</button>
  </form>
  <div class="back"><a href="<?= BASE_URL ?>/">← Voltar ao site</a></div>
</div>
</body>
</html>
