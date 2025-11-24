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



 

//filter ordinateur portable--samrtphone--accessoires--composants pc

  

// les elements du filtre
const ordiSearch    = document.getElementById("ordiSearch");
const ordiMinPrice  = document.getElementById("ordiMinPrice");
const ordiMaxPrice  = document.getElementById("ordiMaxPrice");
const ordiResetBtn  = document.getElementById("ordiReset");

// toutes les cartes
const ordiCards = document.querySelectorAll(".product-card");

//function to apply filters
function applyOrdiFilters() {
  const search = (ordiSearch?.value || "").toLowerCase().trim();
  const min = parseFloat(ordiMinPrice?.value) || 0;
  const max = parseFloat(ordiMaxPrice?.value) || Infinity;

  ordiCards.forEach(card => {
    const name  = (card.dataset.name || "").toLowerCase();
    const price = parseFloat(card.dataset.price) || 0;

    let visible = true;

    // filitre des noms
    if (search && !name.includes(search)) {
      visible = false;
    }

    // filtre des prix
    if (price < min || price > max) {
      visible = false;
    }

    // cacher ou afficher la carte
    const col = card.closest(".col-auto");
    if (col) {
      col.style.display = visible ? "" : "none";
    }
  });
}

// event listeners pour les filtres
if (ordiSearch)   ordiSearch.addEventListener("input", applyOrdiFilters);
if (ordiMinPrice) ordiMinPrice.addEventListener("input", applyOrdiFilters);
if (ordiMaxPrice) ordiMaxPrice.addEventListener("input", applyOrdiFilters);

// event listener pour Reset
if (ordiResetBtn) {
  ordiResetBtn.addEventListener("click", () => {
    if (ordiSearch)   ordiSearch.value = "";
    if (ordiMinPrice) ordiMinPrice.value = "";
    if (ordiMaxPrice) ordiMaxPrice.value = "";
    applyOrdiFilters();
  });
}

// appliquer les filtres au chargement
applyOrdiFilters();


//     
 // js/boutique.js

//  les elements du filtre
const searchInput   = document.getElementById('searchInput');
const headerSearch  = document.getElementById('headerSearch'); // بحث الهيدر
const categorieFilter = document.getElementById('categorieFilter');
const minPriceInput = document.getElementById('minPrice');
const maxPriceInput = document.getElementById('maxPrice');
const resetBtn      = document.getElementById('resetFilters');

// toutes les cartes
const cards = document.querySelectorAll('.product-card');

// function to apply filters
function applyFilters() {
  const text = (searchInput?.value || '').trim().toLowerCase();
  const headerText = (headerSearch?.value || '').trim().toLowerCase();
  const globalText = (text || headerText); // لو واحد منهم فيه نص نستعمله

  const selectedCat = categorieFilter?.value || '';
  const minPrice = parseFloat(minPriceInput?.value) || 0;
  const maxPrice = parseFloat(maxPriceInput?.value) || Infinity;

  cards.forEach(card => {
    const name = (card.dataset.name || '').toLowerCase();
    const categorie = card.dataset.categorie || '';
    const price = parseFloat(card.dataset.price) || 0;

    let visible = true;

    // recherche par nom
    if (globalText && !name.includes(globalText)) {
      visible = false;
    }

    // recherche par categorie
    if (selectedCat && categorie !== selectedCat) {
      visible = false;
    }

    // recherche par prix
    if (price < minPrice || price > maxPrice) {
      visible = false;
    }

    // cacher ou afficher la carte
    const col = card.closest('.col-auto');
    if (col) {
      col.style.display = visible ? '' : 'none';
    }
  });
}

// event listeners pour les filtres
if (searchInput)   searchInput.addEventListener('input', applyFilters);
if (headerSearch)  headerSearch.addEventListener('input', applyFilters);
if (categorieFilter) categorieFilter.addEventListener('change', applyFilters);
if (minPriceInput) minPriceInput.addEventListener('input', applyFilters);
if (maxPriceInput) maxPriceInput.addEventListener('input', applyFilters);

if (resetBtn) {
  resetBtn.addEventListener('click', () => {
    if (searchInput)   searchInput.value = '';
    if (headerSearch)  headerSearch.value = '';
    if (categorieFilter) categorieFilter.value = '';
    if (minPriceInput) minPriceInput.value = '';
    if (maxPriceInput) maxPriceInput.value = '';
    applyFilters();
  });
}

// appliquer les filtres au chargement¦
applyFilters();
