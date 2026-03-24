<footer>
  <div class="footer-logo">Minha<span style="color:var(--cream)">Loja</span></div>
  <p>© <?= date('Y') ?> Minha Loja — Todos os direitos reservados</p>
  <a href="<?= BASE_URL ?>/admin/login.php" class="admin-link">Admin</a>
</footer>

<div class="carrinho-bar" id="carrinhoBar">
  <div class="carrinho-top" onclick="toggleListaCarrinho()">
    <div class="carrinho-info">
      <div class="carr-icon"><i class="bi bi-cart"></i></div>
      <div>
        <div class="carr-count" id="carrinhoCount">0 itens</div>
        <div class="carr-valor" id="carrinhoValor">R$ 0,00</div>
      </div>
    </div>
    <div class="carrinho-acoes" onclick="event.stopPropagation()">
      <button class="btn-limpar" onclick="removerTudo()"><i class="bi bi-trash"></i> Limpar</button>
      <button class="btn-comprar" onclick="comprarWhatsApp()"><i class="bi bi-whatsapp"></i> Comprar pelo WhatsApp</button>
    </div>
  </div>
  <div class="carrinho-lista" id="carrinhoLista"></div>
</div>

<div class="modal-overlay" id="modalOverlay" onclick="fecharModal(event)">
  <div class="modal-box">
    <button class="modal-close" onclick="fecharModalBtn()"><i class="bi bi-x-lg"></i></button>
    <div class="modal-img" id="modalImg"><i class="bi bi-image" style="font-size:64px;opacity:.1"></i></div>
    <div class="modal-body">
      <div class="modal-nome" id="modalNome"></div>
      <div class="modal-valor" id="modalValor"></div>
      <div class="modal-divider"></div>
      <div class="modal-desc" id="modalDesc"></div>
    </div>
  </div>
</div>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('header').classList.toggle('scrolled', scrollY > 60);
});

function toggleMobile() {
  document.getElementById('mobileNav').classList.toggle('open');
  document.getElementById('hamburger').classList.toggle('open');
}
function closeMobile() {
  document.getElementById('mobileNav').classList.remove('open');
  document.getElementById('hamburger').classList.remove('open');
}

function toggleListaCarrinho() {
  document.getElementById('carrinhoLista').classList.toggle('open');
}

function fecharModal(e) {
  if (e.target === document.getElementById('modalOverlay')) fecharModalBtn();
}
function fecharModalBtn() {
  document.getElementById('modalOverlay').classList.remove('open');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') fecharModalBtn(); });
</script>
</body>
</html>
