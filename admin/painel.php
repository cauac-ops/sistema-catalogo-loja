<?php
require_once __DIR__ . '/auth.php';
$db  = DB::get();
$msg = ''; $tipo = '';

if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $prod = $db->prepare("SELECT foto FROM produto WHERE id = :id")->execute([':id' => $_GET['del']]) ? $db->query("SELECT foto FROM produto WHERE id = " . (int)$_GET['del'])->fetchColumn() : null;
    if ($prod && file_exists(UPLOAD_DIR . $prod)) unlink(UPLOAD_DIR . $prod);
    $db->prepare("DELETE FROM produto WHERE id = :id")->execute([':id' => (int)$_GET['del']]);
    header('Location: ' . BASE_URL . '/admin/painel.php?ok=Produto+exclu%C3%ADdo'); exit;
}

if (isset($_POST['salvar_config'])) {
    $wpp = preg_replace('/[^0-9]/', '', $_POST['whatsapp'] ?? '');
    $db->prepare("INSERT INTO configuracao (chave,valor) VALUES ('whatsapp',:v) ON DUPLICATE KEY UPDATE valor=:v")
       ->execute([':v' => $wpp]);
    header('Location: ' . BASE_URL . '/admin/painel.php?ok=Configuracoes+salvas'); exit;
}

if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $db->prepare("UPDATE produto SET ativo = NOT ativo WHERE id = :id")->execute([':id' => (int)$_GET['toggle']]);
    header('Location: ' . BASE_URL . '/admin/painel.php?ok=Status+alterado'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'] ?? null;
    $nome     = s($_POST['nome'] ?? '');
    $desc     = s($_POST['descricao'] ?? '');
    $valor    = (float)($_POST['valor'] ?? 0);
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $foto     = $id ? ($db->prepare("SELECT foto FROM produto WHERE id = :i") ? null : null) : null;

    if ($id) {
        $st = $db->prepare("SELECT foto FROM produto WHERE id = :i");
        $st->execute([':i' => (int)$id]);
        $foto = $st->fetchColumn();
    }

    if (!empty($_FILES['foto']['tmp_name'])) {
        $ext   = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allow = ['jpg','jpeg','png','gif','webp'];
        if (in_array($ext, $allow)) {
            if ($foto && file_exists(UPLOAD_DIR . $foto)) unlink(UPLOAD_DIR . $foto);
            $foto = uniqid('prod_') . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], UPLOAD_DIR . $foto);
        }
    }

    if ($id) {
        $db->prepare("UPDATE produto SET nome=:n,descricao=:d,valor=:v,destaque=:de,foto=:f WHERE id=:i")
           ->execute([':n'=>$nome,':d'=>$desc,':v'=>$valor,':de'=>$destaque,':f'=>$foto,':i'=>(int)$id]);
        header('Location: ' . BASE_URL . '/admin/painel.php?ok=Produto+atualizado'); exit;
    } else {
        $db->prepare("INSERT INTO produto (nome,descricao,valor,destaque,foto) VALUES (:n,:d,:v,:de,:f)")
           ->execute([':n'=>$nome,':d'=>$desc,':v'=>$valor,':de'=>$destaque,':f'=>$foto]);
        header('Location: ' . BASE_URL . '/admin/painel.php?ok=Produto+cadastrado'); exit;
    }
}

$produtos = $db->query("SELECT * FROM produto ORDER BY id DESC")->fetchAll();
$wpp_cfg = $db->query("SELECT valor FROM configuracao WHERE chave='whatsapp'")->fetchColumn() ?: '';
$edit = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $e = $db->prepare("SELECT * FROM produto WHERE id = :i");
    $e->execute([':i' => (int)$_GET['edit']]);
    $edit = $e->fetch();
}
if (!empty($_GET['ok'])) { $msg = htmlspecialchars($_GET['ok']); $tipo = 'success'; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Painel Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
<style>
:root{--gold:#c8a96e;--dark:#0a0a0a;--dark2:#111;--dark3:#1a1a1a;--border:rgba(200,169,110,.2)}
body{font-family:'DM Sans',sans-serif;background:#0d0d0d;color:#f5f0e8;min-height:100vh}
.sidebar{position:fixed;top:0;left:0;bottom:0;width:220px;background:var(--dark2);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:28px 0}
.s-logo{font-family:'Playfair Display',serif;font-size:20px;color:var(--gold);padding:0 24px;margin-bottom:36px;letter-spacing:1px}
.s-link{display:flex;align-items:center;gap:10px;padding:11px 24px;font-size:13px;color:rgba(245,240,232,.5);text-decoration:none;transition:background .15s,color .15s}
.s-link:hover,.s-link.active{background:rgba(200,169,110,.08);color:var(--gold)}
.s-link i{width:18px;font-size:15px}
.s-section{font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#444;padding:16px 24px 6px}
.s-footer{margin-top:auto;padding:20px 24px;border-top:1px solid var(--border)}
.s-user{font-size:12px;color:#555;margin-bottom:12px}
.s-logout{font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#555;text-decoration:none;transition:color .2s}
.s-logout:hover{color:#f87171}
.main{margin-left:220px;padding:32px}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:32px}
.topbar h1{font-family:'Playfair Display',serif;font-size:24px;color:#f5f0e8}
.card-dark{background:var(--dark2);border:1px solid var(--border);border-radius:8px}
.table{color:#f5f0e8;font-size:13.5px}
.table thead th{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#555;border-bottom:1px solid var(--border);font-weight:600}
.table td,.table th{border-color:var(--border);vertical-align:middle}
.table tbody tr:hover{background:rgba(200,169,110,.04)}
.btn-novo{background:var(--gold);color:var(--dark);border:none;padding:10px 24px;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;cursor:pointer;transition:background .2s}
.btn-novo:hover{background:#e8c98e}
.modal-content{background:#111;border:1px solid var(--border);color:#f5f0e8}
.modal-header{background:var(--gold);color:#0a0a0a;border:none}
.modal-footer{border-top:1px solid var(--border)}
.form-control,.form-select{background:#1a1a1a;border:1px solid var(--border);color:#f5f0e8}
.form-control:focus,.form-select:focus{background:#1a1a1a;border-color:var(--gold);color:#f5f0e8;box-shadow:none}
.form-label{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:#777}
.prod-foto-prev{width:80px;height:60px;object-fit:cover;border:1px solid var(--border)}
@media(max-width:768px){.sidebar{display:none}.main{margin-left:0}}
</style>
</head>
<body>

<nav class="sidebar">
  <div class="s-logo">Minha<span style="color:#f5f0e8">Loja</span></div>
  <div class="s-section">Menu</div>
  <a href="painel.php" class="s-link active"><i class="bi bi-box-seam"></i>Produtos</a>
  <a href="<?= BASE_URL ?>/" class="s-link" target="_blank"><i class="bi bi-eye"></i>Ver Site</a>
  <div class="s-footer">
    <div class="s-user"><i class="bi bi-person-circle me-1"></i><?= s($_SESSION['admin_user']) ?></div>
    <a href="logout.php" class="s-logout"><i class="bi bi-box-arrow-left me-1"></i>Sair</a>
  </div>
</nav>

<div class="main">
  <div class="topbar">
    <h1>Painel Admin</h1>
    <button class="btn-novo" id="btnNovo" onclick="abrirNovo()"><i class="bi bi-plus me-1"></i>Novo Produto</button>
  </div>

  <?php if ($msg): ?>
  <div class="alert alert-success alert-dismissible mb-4" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#4ade80;border-radius:6px">
    <i class="bi bi-check-circle me-2"></i><?= $msg ?>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <div class="card-dark">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead><tr><th>Foto</th><th>Nome</th><th>Valor</th><th>Destaque</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($produtos as $p): ?>
        <tr>
          <td>
            <?php if ($p['foto']): ?>
              <img src="<?= s(UPLOAD_URL . $p['foto']) ?>" class="prod-foto-prev">
            <?php else: ?>
              <div style="width:80px;height:60px;background:#1a1a1a;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;opacity:.4"><i class="bi bi-image"></i></div>
            <?php endif; ?>
          </td>
          <td><strong><?= s($p['nome']) ?></strong><br><small style="color:#555"><?= mb_substr(s($p['descricao']),0,50) ?>...</small></td>
          <td style="color:var(--gold);font-weight:600"><?= moeda($p['valor']) ?></td>
          <td><?= $p['destaque'] ? '<span class="badge" style="background:var(--gold);color:#000">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?></td>
          <td>
            <a href="?toggle=<?= $p['id'] ?>" onclick="return confirm('Alterar status?')" style="text-decoration:none">
              <?= $p['ativo'] ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>' ?>
            </a>
          </td>
          <td>
            <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning py-0 px-2 me-1"><i class="bi bi-pencil"></i></a>
            <a href="?del=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="return confirm('Excluir produto?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($produtos)): ?>
          <tr><td colspan="6" class="text-center py-5" style="color:#555">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="modalProd" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2"></i><span id="modalTitle">Novo Produto</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id" id="formId">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Nome do Produto *</label>
              <input type="text" name="nome" id="formNome" class="form-control" required placeholder="Ex: Camiseta Premium">
            </div>
            <div class="col-md-4">
              <label class="form-label">Valor (R$) *</label>
              <input type="number" step="0.01" min="0" name="valor" id="formValor" class="form-control" required placeholder="0.00">
            </div>
            <div class="col-12">
              <label class="form-label">Descrição</label>
              <textarea name="descricao" id="formDesc" class="form-control" rows="3" placeholder="Descreva o produto..."></textarea>
            </div>
            <div class="col-md-8">
              <label class="form-label">Foto do Produto</label>
              <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
              <div class="mt-2" id="fotoPreview"></div>
            </div>
            <div class="col-md-4 d-flex align-items-end pb-1">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="destaque" id="formDestaque">
                <label class="form-check-label fw-semibold" for="formDestaque" style="font-size:13px">Marcar como Destaque</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn-novo"><i class="bi bi-check-circle me-1"></i>Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const modal = new bootstrap.Modal(document.getElementById('modalProd'));

function abrirNovo() {
  document.getElementById('modalTitle').textContent = 'Novo Produto';
  document.getElementById('formId').value    = '';
  document.getElementById('formNome').value  = '';
  document.getElementById('formValor').value = '';
  document.getElementById('formDesc').value  = '';
  document.getElementById('formDestaque').checked = false;
  document.getElementById('fotoPreview').innerHTML = '';
  modal.show();
}

function previewFoto(input) {
  const prev = document.getElementById('fotoPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => prev.innerHTML = `<img src="${e.target.result}" style="height:80px;border:1px solid var(--border)">`;
    reader.readAsDataURL(input.files[0]);
  }
}

<?php if ($edit): ?>
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('modalTitle').textContent = 'Editar Produto';
  document.getElementById('formId').value    = '<?= $edit['id'] ?>';
  document.getElementById('formNome').value  = '<?= s($edit['nome']) ?>';
  document.getElementById('formValor').value = '<?= $edit['valor'] ?>';
  document.getElementById('formDesc').value  = '<?= s($edit['descricao']) ?>';
  document.getElementById('formDestaque').checked = <?= $edit['destaque'] ? 'true' : 'false' ?>;
  <?php if ($edit['foto']): ?>
  document.getElementById('fotoPreview').innerHTML = '<img src="<?= s(UPLOAD_URL . $edit['foto']) ?>" style="height:80px;border:1px solid var(--border)">';
  <?php endif; ?>
  modal.show();
});
<?php endif; ?>
</script>
</body>
</html>
