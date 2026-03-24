<?php
$page_title = 'Minha Loja';
$page_desc  = 'Produtos de qualidade para voce.';
require_once __DIR__ . '/components/header.php';
$produtos = DB::get()->query("SELECT * FROM produto WHERE ativo = 1 ORDER BY destaque DESC, id DESC")->fetchAll();
$wpp = DB::get()->query("SELECT valor FROM configuracao WHERE chave = 'whatsapp'")->fetchColumn() ?: WHATSAPP;
?>

<section class="hero" id="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-content">
    <div class="hero-tag">Bem-vindo a Nossa Loja</div>
    <h1>Qualidade que voce<br><em>pode sentir</em></h1>
    <p class="hero-desc">Produtos cuidadosamente selecionados para oferecer o melhor em qualidade, design e custo-beneficio.</p>
    <div>
      <a href="#produtos" class="btn-gold">Ver Produtos</a>
      <a href="#contato" class="btn-outline">Fale Conosco</a>
    </div>
  </div>
</section>

<section id="produtos">
  <div style="text-align:center;max-width:600px;margin:0 auto">
    <span class="section-tag">Catalogo</span>
    <h2 class="section-title">Nossos <em>Produtos</em></h2>
    <div class="section-line" style="margin:0 auto 60px"></div>
  </div>
  <div class="produtos-grid">
    <?php foreach ($produtos as $p): ?>
    <div class="produto-card" id="card-<?= $p['id'] ?>">
      <div class="produto-img" onclick="abrirModal(this.closest('.produto-card'))" style="cursor:pointer">
        <?php if ($p['foto']): ?>
          <img src="<?= s(UPLOAD_URL . $p['foto']) ?>" alt="<?= s($p['nome']) ?>" loading="lazy">
        <?php else: ?>
          <div class="produto-img-placeholder"><i class="bi bi-image"></i></div>
        <?php endif; ?>
      </div>
      <div class="produto-info">
        <div class="produto-nome"><?= s($p['nome']) ?></div>
        <div class="produto-desc"><?= s($p['descricao']) ?></div>
        <div class="produto-footer">
          <div class="produto-valor"><?= moeda($p['valor']) ?></div>
          <div class="produto-acoes">
            <div class="qtd-control">
              <button class="qtd-btn" onclick="alterarQtd(<?= $p['id'] ?>, -1)">−</button>
              <span class="qtd-num" id="qtd-<?= $p['id'] ?>">0</span>
              <button class="qtd-btn" onclick="alterarQtd(<?= $p['id'] ?>, 1)">+</button>
            </div>
          </div>
        </div>
        <button class="btn-adicionar" onclick="adicionarCarrinho(<?= $p['id'] ?>, '<?= s($p['nome']) ?>', <?= $p['valor'] ?>)">
          <i class="bi bi-cart-plus"></i> Adicionar
        </button>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($produtos)): ?>
      <div style="grid-column:1/-1;text-align:center;padding:80px;color:var(--gray)">
        <i class="bi bi-box" style="font-size:48px;display:block;margin-bottom:16px;opacity:.3"></i>
        Nenhum produto cadastrado ainda.
      </div>
    <?php endif; ?>
  </div>
</section>

<section id="sobre">
  <div class="sobre-grid">
    <div class="sobre-texto">
      <span class="section-tag">Sobre Nos</span>
      <h2 class="section-title">Por que nos <em>escolher?</em></h2>
      <div class="section-line"></div>
      <p>Somos uma empresa dedicada a oferecer produtos de alta qualidade com atendimento personalizado.</p>
      <p>Com anos de experiencia no mercado, selecionamos rigorosamente cada produto para garantir que voce receba apenas o melhor.</p>
      <div class="sobre-stats">
        <div class="stat"><div class="stat-num">500+</div><div class="stat-label">Clientes Satisfeitos</div></div>
        <div class="stat"><div class="stat-num">100+</div><div class="stat-label">Produtos</div></div>
        <div class="stat"><div class="stat-num">5★</div><div class="stat-label">Avaliacao Media</div></div>
        <div class="stat"><div class="stat-num">24h</div><div class="stat-label">Suporte</div></div>
      </div>
    </div>
    <div class="sobre-visual"><i class="bi bi-shop"></i></div>
  </div>
</section>

<section id="contato">
  <div class="contato-inner">
    <span class="section-tag">Contato</span>
    <h2 class="section-title">Fale <em>Conosco</em></h2>
    <div class="section-line" style="margin:0 auto 40px"></div>
    <p>Tem alguma duvida sobre nossos produtos? Entre em contato e teremos prazer em atende-lo.</p>
    <a href="https://wa.me/<?= s($wpp) ?>" target="_blank" class="btn-gold">
      <i class="bi bi-whatsapp"></i> Falar no WhatsApp
    </a>
  </div>
</section>

<?php require_once __DIR__ . '/components/footer.php'; ?>

<script>
const WPP = '<?= s($wpp) ?>';
let carrinho = {};

function alterarQtd(id, delta) {
  const atual = parseInt(document.getElementById('qtd-' + id).textContent) || 0;
  const novo  = Math.max(0, atual + delta);
  document.getElementById('qtd-' + id).textContent = novo;
}

function adicionarCarrinho(id, nome, valor) {
  const qtd = parseInt(document.getElementById('qtd-' + id).textContent) || 0;
  if (qtd === 0) {
    document.getElementById('qtd-' + id).textContent = 1;
    carrinho[id] = { nome, valor, qtd: 1 };
  } else {
    carrinho[id] = { nome, valor, qtd };
  }
  atualizarCarrinho();
  document.getElementById('qtd-' + id).textContent = 0;

  const card = document.getElementById('card-' + id);
  card.style.borderColor = 'var(--accent)';
  setTimeout(() => card.style.borderColor = '', 1200);
}

function atualizarCarrinho() {
  const total = Object.values(carrinho).reduce((s, i) => s + i.qtd, 0);
  const valor = Object.values(carrinho).reduce((s, i) => s + i.qtd * i.valor, 0);
  const bar   = document.getElementById('carrinhoBar');

  if (total === 0) {
    bar.classList.remove('visible');
    return;
  }

  bar.classList.add('visible');
  document.getElementById('carrinhoCount').textContent = total + (total === 1 ? ' item' : ' itens');
  document.getElementById('carrinhoValor').textContent = 'R$ ' + valor.toFixed(2).replace('.', ',');

  const lista = document.getElementById('carrinhoLista');
  lista.innerHTML = Object.values(carrinho).map(i =>
    `<div class="carr-item">
      <span>${i.nome}</span>
      <span class="carr-qtd">${i.qtd}x</span>
      <span class="carr-sub">R$ ${(i.qtd * i.valor).toFixed(2).replace('.', ',')}</span>
    </div>`
  ).join('');
}

function removerTudo() {
  carrinho = {};
  atualizarCarrinho();
}

function comprarWhatsApp() {
  const itens = Object.values(carrinho);
  if (itens.length === 0) return;

  const lista = itens.map(i => `${i.qtd}x ${i.nome}`).join(', ');
  const valor = itens.reduce((s, i) => s + i.qtd * i.valor, 0);
  const msg   = `Ola! Sobre o(s) produto(s): ${lista}. Estou interessado! Valor total: R$ ${valor.toFixed(2).replace('.', ',')}`;
  window.open('https://wa.me/' + WPP + '?text=' + encodeURIComponent(msg), '_blank');
}

function abrirModal(el) {
  const nome  = el.querySelector('.produto-nome').textContent;
  const desc  = el.querySelector('.produto-desc').textContent;
  const valor = el.querySelector('.produto-valor').textContent;
  const img   = el.querySelector('.produto-img img');
  document.getElementById('modalNome').textContent  = nome;
  document.getElementById('modalValor').textContent = valor;
  document.getElementById('modalDesc').textContent  = desc;
  const mi = document.getElementById('modalImg');
  mi.innerHTML = img
    ? `<img src="${img.src}" alt="${nome}">`
    : '<i class="bi bi-image" style="font-size:64px;opacity:.1;color:var(--cream)"></i>';
  document.getElementById('modalOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
</script>
