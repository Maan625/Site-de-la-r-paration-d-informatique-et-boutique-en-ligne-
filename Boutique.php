 <?php
  session_start();
  require 'config.php';
  try {
    $pdo = new PDO(
      "mysql:host=$db_host;dbname=$db_name;charset=utf8",
      $db_user,
      $db_pass,
      $options
    );
  } catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
  }

  $sql = "SELECT * FROM produits WHERE 1";
  $params = [];


  // 🔎 1) recherche pour tout les produits  q
  $search = isset($_GET['q']) ? trim($_GET['q']) : '';

  if ($search !== '') {
    //)
    $sql .= " AND (nom LIKE ? OR categorie LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
  }

  // recherche pour les produits par categorie)
  if (!empty($_GET['categorie'])) {
    $sql .= " AND categorie = ?";
    $params[] = $_GET['categorie'];
  }

  // recherche pour les produits par prix
  if (!empty($_GET['prix_min'])) {
    $sql .= " AND prix >= ?";
    $params[] = $_GET['prix_min'];
  }

  if (!empty($_GET['prix_max'])) {
    $sql .= " AND prix <= ?";
    $params[] = $_GET['prix_max'];
  }


  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

 <!DOCTYPE html>
 <html lang="fr">

 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Bootstrap Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
     crossorigin="anonymous"></script>


   <link rel="stylesheet" href="scss/main.css">
   <title>Boutique Fixtech</title>
 </head>

 <body>
   <header class="header_boutique">
     <nav id="nav_list" class="navbar navbar-dark">
       <div class="container-fluid">
         <button id="button_list" class="navbar-toggler" type="button" data-bs-toggle="collapse"
           data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent"
           aria-expanded="false" aria-label="Toggle navigation">
           <span id="span_list" class="navbar-toggler-icon"></span>
         </button>
       </div>
     </nav>

     <div class="collapse" id="navbarToggleExternalContent">
       <div class="bg-dark p-4" id="div_link">
         <a href="index.html">
           <img src="images/Rectangle 2.png" alt="logo" class="logo w-50">
         </a>
         <div class="div_group_link">
           <a class="link_navber" href="index.html"><button class="btn_link">Accueil</button></a>
           <a class="link_navber" href="reparation_informatique.html"><button class="btn_link">Réparation
               informatique</button></a>
           <a class="link_navber" href="reparation_telephone.html"><button class="btn_link">Réparation
               téléphone</button></a>
           <a class="link_navber" href="reparation_console_de_jeux.html"><button class="btn_link">Réparation
               console de jeux</button></a>
           <a class="link_navber" href="Micro_soudure.html"><button class="btn_link">Micro-soudure</button></a>
           <a class="link_navber" href="Boutique.php"><button class="btn_link">Boutique en ligne</button></a>
           <a class="link_navber" href="contact.html"><button class="btn_link">Contact</button></a>
         </div>
         <div class="icon_réseaux_sociale">
           <a href="#"><i class="bi bi-facebook"></i></a>
           <a href="#"><i class="bi bi-instagram"></i></a>
           <a href="https://wa.me/33612345678?text=Bonjour!" target="_blank"><i class="bi bi-whatsapp"></i></a>
         </div>
       </div>
     </div>

     <!-- Recherche header pour la boutique -->
     <form id="input_search" class="input_search input-group w-50" action="boutique.php" method="get">
       <input type="text"
         name="q"
         class="form-control"
         placeholder="Rechercher un produit dans toute la boutique"
         aria-label="Search">
       <button class="btn btn-outline-secondary" type="submit" id="button-addon1">
         <i class="bi bi-search"></i>
       </button>
     </form>


     <!-- Panier -->
     <div class="ps-5 pe-5 d-flex gap-4">
       <button id="openCartBtn" class="btn position-relative" type="button"
         data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas"
         aria-controls="cartOffcanvas" title="Ouvrir le panier">
         <i id="icon_fill" class="bi bi-cart-fill"></i>
         <span id="cartCount"
           class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">0</span>
       </button>

       <?php if (isset($_SESSION['client_initial'])): ?>
         <!-- لو المستخدم مسجّل الدخول نعرض دائرة فيها أول حرف + زر Déconnexion -->

         <!-- رابط لصفحة البروفايل -->
         <a href="profile_client.php" class="text-decoration-none">
           <div class="user-circle d-flex align-items-center justify-content-center me-2">
             <?= htmlspecialchars($_SESSION['client_initial']) ?>
           </div>
         </a>

         <!-- زر تسجيل الخروج -->
         <form action="logout.php" method="post" class="d-inline">
           <button type="submit" class="btn btn-outline-dark btn-sm">
             Déconnexion
           </button>
         </form>

       <?php else: ?>
         <!-- لو ما في تسجيل دخول نعرض أيقونة الشخص -->
         <a href="nouveau comptr.php">
           <i id="icon_person" class="bi bi-person-fill"></i>
         </a>
       <?php endif; ?>
     </div>


   </header>

   <!-- main -->
   <main class="main_boutique text-center">
     <button onclick="changerMode()" id="modeButton">🌙</button>

     <h1 id="titre_boutique" class="text-center my-4">Notre Boutique</h1>

     <!-- liens vers sous-pages -->
     <section id="liens_boutique">
       <div class="row justify-content-between align-items-center text-sm-center">
         <div class="col-12 col-sm-6 col-lg-3 mb-2 mt-2">
           <a class="line_boutique" href="smartphone.php">Smartphone</a>
         </div>
         <div class="col-12 col-sm-6 col-lg-3 mb-2 mt-2">
           <a class="line_boutique" href="ordinateur_portable.php">Ordinateur portable</a>
         </div>
         <div class="col-12 col-sm-6 col-lg-3 mb-2 mt-2">
           <a class="line_boutique" href="Composants _pc.php">Composants PC</a>
         </div>
         <div class="col-12 col-sm-6 col-lg-3 mb-2 mt-2">
           <a class="line_boutique" href="Accessoires.php">Accessoires</a>
         </div>
       </div>
     </section>

     <!-- présentation -->
     <section class="présentation_boutique p-4">
       <p class="para_presentation mx-auto fw-medium ">
         Bienvenue chez Fixtech, votre partenaire de confiance pour la réparation et l’équipement informatique.
         Découvrez notre boutique en ligne : accessoires, pièces détachées, ordinateurs reconditionnés et
         composants PC — tout ce qu’il vous faut pour booster vos appareils au meilleur prix.
       </p>
       <h2 class="titre_presentation mx-auto">Chez Fixtech, qualité, rapidité et satisfaction sont nos priorités.</h2>
     </section>


     <!-- 🔍 FILTRES Boutique -->
     <section class="container mb-4" id="filters">
       <div class="row g-3 justify-content-center">
         <div class="col-md-4">
           <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom...">
         </div>
         <div class="col-md-3">
           <input type="number" id="minPrice" class="form-control" placeholder="Prix min">
         </div>
         <div class="col-md-3">
           <input type="number" id="maxPrice" class="form-control" placeholder="Prix max">
         </div>
         <div class="col-md-2">
           <button class="btn btn-outline-primary w-100" id="resetFilters">Reset</button>
         </div>
       </div>
     </section>


     <!-- produits -->
     <section class="produits text-bg-primary">
       <section class="produits_phares text-center">
         <h2 class="titre_produits_phares">Tous les produits</h2>

         <div class="row mx-auto justify-content-center gap-4">

           <?php foreach ($produits as $p): ?>
             <div class="col-auto">
               <div class="card product-card"
                 style="width: 18rem;"
                 data-id="<?= htmlspecialchars($p['id_produit']) ?>"
                 data-name="<?= htmlspecialchars($p['nom']) ?>"
                 data-price="<?= htmlspecialchars($p['prix']) ?>"
                 data-categorie="<?= htmlspecialchars($p['categorie']) ?>">

                 <img style="height: 200px;" src="<?= htmlspecialchars($p['image_url']) ?>"
                   class="card-img-top"
                   alt="<?= htmlspecialchars($p['nom']) ?>">

                 <div class="card-body">
                   <div class="d-flex justify-content-center">
                     <h5 class="card-title fw-bold text-primary">
                       <?= htmlspecialchars($p['nom']) ?>
                     </h5>

                     <button class="btn btn-link p-0 btn-add-cart position-absolute top-0 end-0 mt-2"
                       type="button" title="Ajouter au panier">
                       <i class="bi bi-cart-fill icon_panier "></i>
                     </button>
                   </div>

                   <p class="card-text">
                     <?= htmlspecialchars($p['description']) ?>
                   </p>

                   <div class="d-flex justify-content-between align-items-center">
                     <span class="text-bg-primary p-2 rounded-2 price-badge">
                       <?= htmlspecialchars($p['prix']) ?>€
                     </span>
                     <a href="#" class="btn btn-outline-primary">Voir plus</a>
                   </div>
                 </div>
               </div>
             </div>
           <?php endforeach; ?>

         </div>
       </section>
     </section>
   </main>

   <!-- footer -->
   <footer class="footer mt-1">
     <div class="div_footer ">
       <div class="logo_social d-flex ">
         <img src="images/Rectangle 2.png" alt="">
         <div class="icon_réseaux_sociale">
           <a href=""> <i class="bi bi-facebook"></i></a>
           <a href=""> <i class="bi bi-instagram"></i></a>
           <a href="https://wa.me/33612345678?text=Bonjour!" target="_blank"> <i class="bi bi-whatsapp"></i></a>
         </div>
       </div>
       <div class="contact_map text-light ">
         <a class="text-decoration-none text-light" href="https://maps.app.goo.gl/pULLpi12J8Z6PDHFA"><i
             class="bi bi-geo-alt-fill"> 10 Rue de la Gare <br> 42000 Saint-Etienne</i></a><br><br>
         <a class="text-decoration-none text-light" href="mailto:g7k6o@example.com"><i class="bi bi-envelope-fill">
             g7k6o@example.com</i></a><br><br>
         <a class="text-decoration-none text-light" href="tel:04 77 57 17 38"><i class="bi bi-telephone-fill"> 04 77 57
             17 38</i></a>
       </div>
       <div class="plan_de_site ">
         <ul class="plan_du_site">
           <li class="line"><a href="index.html">Accueil</a></li>
           <li class="line"><a href="reparation_informatique.html">Réparation informatique</a></li>
           <li class="line"><a href="reparation_telephone.html">Réparation telephone</a></li>
           <li class="line"><a href="reparation_console_de_jeux.htm">Réparation console de jeux</a></li>
           <li class="line"><a href="#">Micro-soudure</a></li>
           <li class="line"><a href="Boutique.php">Boutique en ligne</a></li>
           <li class="line"><a href="contact.html">Contact</a></li>
         </ul>
       </div>
     </div>
     <p class="text-light text-center mt-3">Copyright© 2025 - 2026 - Tous droits reservé </p>
   </footer>

   <!-- Offcanvas Cart -->
   <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartLabel">
     <div class="offcanvas-header">
       <h5 class="offcanvas-title" id="cartLabel">Panier</h5>
       <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
     </div>
     <div class="offcanvas-body d-flex flex-column">
       <div id="cartLines" class="vstack gap-3"></div>
       <div class="mt-auto pt-3 border-top">
         <div class="d-flex justify-content-between align-items-center mb-3">
           <span class="fw-semibold">Total</span>
           <strong id="cartTotal">0.00 €</strong>
         </div>
         <button id="checkoutBtn" class="btn btn-dark w-100">Commander</button>
       </div>
     </div>
   </div>


   <script src="js/card.js" defer></script>
   <script src="js/main.js"></script>

 </body>

 </html>