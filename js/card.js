 // Panier Fixtech — logique côté client (localStorage + offcanvas)
// Dépendances : Bootstrap bundle (pour Offcanvas)

(function () {
  // Clé de stockage locale
  const CART_KEY = 'fixtech_cart_v1';

  // État du panier (tableau d'articles)
  let cart = loadCart();

  // --- Persistance -----------------------------------------------------------
  function loadCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch { return []; }
  }
  function saveCart() {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
  }

  // --- Opérations métier -----------------------------------------------------
  function addToCart(product) {
    const i = cart.findIndex(x => x.id === product.id);
    if (i > -1) cart[i].qty += 1;
    else cart.push({ ...product, qty: 1 });
    saveCart();
    renderCart();
  }

  function changeQty(id, delta) {
    const it = cart.find(x => x.id === id);
    if (!it) return;
    it.qty += delta;
    if (it.qty <= 0) cart = cart.filter(x => x.id !== id);
    saveCart();
    renderCart();
  }

  function removeLine(id) {
    cart = cart.filter(x => x.id !== id);
    saveCart();
    renderCart();
  }

  // --- Sélecteurs d'UI -------------------------------------------------------
  const cartCountEl = document.getElementById('cartCount'); // badge dans le header (facultatif)
  const cartLinesEl = document.getElementById('cartLines'); // conteneur des lignes
  const cartTotalEl = document.getElementById('cartTotal'); // total TTC

  // Convertit un texte de prix FR/UE vers un nombre JS
  function parseEuroToNumber(text) {
    // Ex: "1 299,90 €" -> 1299.90 ; "399€" -> 399
    const cleaned = text.replace(/\s/g, '').replace(/[€]/g, '').replace(',', '.');
    return Number(cleaned);
  }

  // Rendu complet du panier (badge + lignes + total)
  function renderCart() {
    // 1) badge compteur
    const count = cart.reduce((s, i) => s + i.qty, 0);
    if (cartCountEl) cartCountEl.textContent = count;

    // 2) lignes
    if (cartLinesEl) {
      cartLinesEl.innerHTML = '';
      if (cart.length === 0) {
        cartLinesEl.innerHTML = '<div class="text-muted">Votre panier est vide.</div>';
      } else {
        cart.forEach(item => {
          const line = document.createElement('div');
          line.className = 'd-flex align-items-center gap-3 border rounded p-2';
          line.innerHTML = `
            <img src="${item.img || 'https://via.placeholder.com/80'}" alt="${item.name}"
                 width="64" height="64" class="rounded object-fit-cover">
            <div class="flex-grow-1">
              <div class="fw-semibold">${item.name}</div>
              <div class="text-muted small">${Number(item.price).toFixed(2)} €</div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <button class="btn btn-outline-secondary btn-sm" data-action="dec" data-id="${item.id}">−</button>
              <span>${item.qty}</span>
              <button class="btn btn-outline-secondary btn-sm" data-action="inc" data-id="${item.id}">+</button>
              <button class="btn btn-outline-danger btn-sm" data-action="remove" data-id="${item.id}">🗑️</button>
            </div>
          `;
          cartLinesEl.appendChild(line);
        });
      }
    }

    // 3) total
    const total = cart.reduce((s, i) => s + Number(i.price) * i.qty, 0);
    if (cartTotalEl) cartTotalEl.textContent = `${total.toFixed(2)} €`;
  }

  // --- Écouteurs globaux -----------------------------------------------------
  // Ajout au panier depuis le bouton (icône panier) à l’intérieur des cartes
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-add-cart');
    if (!btn) return;

    // 1) Tentative: lire les data-* du bouton
    let { id, name, price, img } = btn.dataset;

    // 2) Sinon: lire sur la carte parent (data-*) ou déduire depuis le DOM
    const card = btn.closest('.product-card, .card');
    if (card) {
      id    = id    || card.dataset.id;
      name  = name  || card.dataset.name;
      price = price || card.dataset.price;
      img   = img   || card.dataset.img;

      // Valeurs de repli depuis le DOM si data-* manquants
      if (!name) {
        const t = card.querySelector('.card-title, h5');
        if (t) name = t.textContent.trim();
      }
      if (!price) {
        const priceNode = card.querySelector('.price-badge, .text-bg-primary, .product-price, span, p');
        if (priceNode) price = parseEuroToNumber(priceNode.textContent);
      }
      if (!img) {
        const im = card.querySelector('img');
        if (im) img = im.getAttribute('src');
      }
      if (!id && name) {
        // Génère un id simple à partir du nom (kebab-case)
        id = name.toLowerCase().replace(/\s+/g, '-');
      }
    }

    if (!id || !name || !price) {
      console.warn('Impossible d’identifier le produit', { id, name, price, img, card });
      return;
    }

    addToCart({ id, name, price: Number(price), img });

    // Ouvre l’offcanvas pour confirmer visuellement
    const el = document.getElementById('cartOffcanvas');
    if (window.bootstrap && el) {
      const oc = bootstrap.Offcanvas.getOrCreateInstance(el);
      oc.show();
    }
  });

  // Gestion des boutons de quantité / suppression dans l’offcanvas
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn || !btn.dataset.id) return;
    const { action, id } = btn.dataset;
    if (action === 'inc') changeQty(id, +1);
    if (action === 'dec') changeQty(id, -1);
    if (action === 'remove') removeLine(id);
  });

  // Bouton "Commander" — point d’intégration API ultérieure
  const checkoutBtn = document.getElementById('checkoutBtn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
      // Exemple d’envoi futur :
      // fetch('/api/checkout', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ cart }) })
      alert('Démo front-end — connectez votre API plus tard.');
    });
  }

  // Initialisation
  renderCart();
})();



//filter smartphone
      document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('searchSmartphone');
      const filterButtons = document.querySelectorAll('#smartphoneFilters button');
      const cards = document.querySelectorAll('.product-card');

      const priceSelect = document.getElementById('priceFilter');
      const storageSelect = document.getElementById('storageFilter');

      let currentBrandFilter = 'all';

      function applyFilters() {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const priceFilter = priceSelect ? priceSelect.value : 'all';
        const storageFilter = storageSelect ? storageSelect.value : 'all';

        cards.forEach(card => {
          const name = (card.dataset.name || card.querySelector('.card-title').textContent).toLowerCase();
          const brand = (card.dataset.brand || '').toLowerCase();
          const price = parseFloat(card.dataset.price || '0');
          const storage = (card.dataset.storage || '').toString(); // ex: "128"

          // 🔍 texte
          const matchText = name.includes(term);

          // 🏷️ marque
          const matchBrand = (currentBrandFilter === 'all' || brand === currentBrandFilter);

          // 💰 prix
          let matchPrice = true;
          if (priceFilter === 'lt300') {
            matchPrice = price < 300;
          } else if (priceFilter === '300-600') {
            matchPrice = (price >= 300 && price <= 600);
          } else if (priceFilter === 'gt600') {
            matchPrice = price > 600;
          }

          // 💾 stockage
          let matchStorage = true;
          if (storageFilter !== 'all') {
            matchStorage = (storage === storageFilter);
          }

          // Affichage final
          const col = card.closest('.col-auto');
          if (matchText && matchBrand && matchPrice && matchStorage) {
            col.style.display = '';
          } else {
            col.style.display = 'none';
          }
        });
      }

      // 🔍 recherche texte
      if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
      }

      // 🎯 filtre marque
      filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          filterButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          currentBrandFilter = btn.dataset.filter;
          applyFilters();
        });
      });

      // 💰 filtre prix
      if (priceSelect) {
        priceSelect.addEventListener('change', applyFilters);
      }

      // 💾 filtre stockage
      if (storageSelect) {
        storageSelect.addEventListener('change', applyFilters);
      }
    });



//filter ordinateur portable

    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('searchLaptop');
      const brandButtons = document.querySelectorAll('#laptopFilters button');
      const priceSelect = document.getElementById('laptopPriceFilter');
      const storageSelect = document.getElementById('laptopStorageFilter');
      const cards = document.querySelectorAll('.product-card');

      let currentBrandFilter = 'all';

      function applyFilters() {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const priceFilter = priceSelect ? priceSelect.value : 'all';
        const storageFilter = storageSelect ? storageSelect.value : 'all';

        cards.forEach(card => {
          const name = (card.dataset.name || card.querySelector('.card-title').textContent).toLowerCase();
          const brand = (card.dataset.brand || '').toLowerCase();
          const price = parseFloat(card.dataset.price || '0');
          const storage = (card.dataset.storage || '').toString();

          // 🔍 texte recherche
          const matchText = name.includes(term);

          // 🏷️ marque
          let matchBrand = true;
          if (currentBrandFilter !== 'all') {
            if (currentBrandFilter === 'autres') {
              matchBrand = !['asus', 'dell', 'hp', 'apple'].includes(brand);
            } else {
              matchBrand = (brand === currentBrandFilter);
            }
          }

          // 💰 prix
          let matchPrice = true;
          if (priceFilter === 'lt500') {
            matchPrice = price < 500;
          } else if (priceFilter === '500-800') {
            matchPrice = price >= 500 && price <= 800;
          } else if (priceFilter === 'gt800') {
            matchPrice = price > 800;
          }

          // 💾 stockage
          let matchStorage = true;
          if (storageFilter !== 'all') {
            matchStorage = (storage === storageFilter);
          }

          const col = card.closest('.col-auto');
          if (matchText && matchBrand && matchPrice && matchStorage) {
            col.style.display = '';
          } else {
            col.style.display = 'none';
          }
        });
      }

      // 🔍 recherche texte
      if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
      }

      // 🎯 filtre marque
      brandButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          brandButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          currentBrandFilter = btn.dataset.filter;
          applyFilters();
        });
      });

      // 💰 filtre prix
      if (priceSelect) {
        priceSelect.addEventListener('change', applyFilters);
      }

      // 💾 filtre stockage
      if (storageSelect) {
        storageSelect.addEventListener('change', applyFilters);
      }
    });


    // filter components 
      document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('searchComponents');
      const categoryButtons = document.querySelectorAll('#componentsFilters button');
      const priceSelect = document.getElementById('componentsPriceFilter');
      const cards = document.querySelectorAll('.product-card');

      let currentCategoryFilter = 'all';

      function applyFilters() {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const priceFilter = priceSelect ? priceSelect.value : 'all';

        cards.forEach(card => {
          const name = (card.dataset.name || card.querySelector('.card-title').textContent).toLowerCase();
          const category = (card.dataset.category || '').toLowerCase();
          const price = parseFloat(card.dataset.price || '0');

          // 🔍 texte (nom)
          const matchText = name.includes(term);

          // 🧩 type composant
          let matchCategory = true;
          if (currentCategoryFilter !== 'all') {
            matchCategory = (category === currentCategoryFilter);
          }

          // 💰 prix
          let matchPrice = true;
          if (priceFilter === 'lt50') {
            matchPrice = price < 50;
          } else if (priceFilter === '50-150') {
            matchPrice = price >= 50 && price <= 150;
          } else if (priceFilter === 'gt150') {
            matchPrice = price > 150;
          }

          const col = card.closest('.col-auto');
          if (matchText && matchCategory && matchPrice) {
            col.style.display = '';
          } else {
            col.style.display = 'none';
          }
        });
      }

      // 🔍 recherche
      if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
      }

      // 🧩 filtre type
      categoryButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          categoryButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          currentCategoryFilter = btn.dataset.filter;
          applyFilters();
        });
      });

      // 💰 filtre prix
      if (priceSelect) {
        priceSelect.addEventListener('change', applyFilters);
      }
    });



    // filter accessoires
      document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('searchAccessories');
      const categoryButtons = document.querySelectorAll('#accessoriesFilters button');
      const priceSelect = document.getElementById('accessoriesPriceFilter');
      const cards = document.querySelectorAll('.product-card');

      let currentCategoryFilter = 'all';

      function applyFilters() {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const priceFilter = priceSelect ? priceSelect.value : 'all';

        cards.forEach(card => {
          const name = (card.dataset.name || card.querySelector('.card-title').textContent).toLowerCase();
          const category = (card.dataset.category || '').toLowerCase();
          const price = parseFloat(card.dataset.price || '0');

          // 🔍 texte
          const matchText = name.includes(term);

          // 🎯 type accessoire
          let matchCategory = true;
          if (currentCategoryFilter !== 'all') {
            matchCategory = (category === currentCategoryFilter);
          }

          // 💰 prix
          let matchPrice = true;
          if (priceFilter === 'lt30') {
            matchPrice = price < 30;
          } else if (priceFilter === '30-70') {
            matchPrice = price >= 30 && price <= 70;
          } else if (priceFilter === 'gt70') {
            matchPrice = price > 70;
          }

          const col = card.closest('.col-auto');
          if (matchText && matchCategory && matchPrice) {
            col.style.display = '';
          } else {
            col.style.display = 'none';
          }
        });
      }

      // 🔍 recherche
      if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
      }

      // 🎯 filtre type
      categoryButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          categoryButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          currentCategoryFilter = btn.dataset.filter;
          applyFilters();
        });
      });

      // 💰 filtre prix
      if (priceSelect) {
        priceSelect.addEventListener('change', applyFilters);
      }
    });