<?php
session_start();
require 'config_compte.php';

// لو المستخدم غير مسجّل، نعيده لصفحة الدخول
if (!isset($_SESSION['client_id'])) {
    header("Location: nouveau_compte.php");
    exit;
}

$idClient = $_SESSION['client_id'];

// نجلب بياناته من قاعدة البيانات
$stmt = $pdo->prepare("
    SELECT nom, prenom, email, date_inscription
    FROM clients
    WHERE id_client = ?
");
$stmt->execute([$idClient]);
$client = $stmt->fetch();

if (!$client) {
    // لو الحساب غير موجود (مثلا تم حذفه) نعمل تسجيل خروج احتياطي
    header("Location: logout.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil - Fixtech</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="scss/main.css">
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
                <a class="link_navber" href="reparation_informatique.html"><button class="btn_link">Réparation informatique</button></a>
                <a class="link_navber" href="reparation_telephone.html"><button class="btn_link">Réparation téléphone</button></a>
                <a class="link_navber" href="reparation_console_de_jeux.html"><button class="btn_link">Réparation console de jeux</button></a>
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

    <div id="input_search" class="input_search input-group w-50">
        <input type="text" class="form-control" placeholder="Rechercher un produit" aria-label="Search">
        <button class="btn btn-outline-secondary" type="button" id="button-addon1">
            <i class="bi bi-search"></i>
        </button>
    </div>

    <div class="ps-5 pe-5 d-flex gap-4">
        <button id="openCartBtn" class="btn position-relative" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas"
                aria-controls="cartOffcanvas" title="Ouvrir le panier">
            <i id="icon_fill" class="bi bi-cart-fill"></i>
            <span id="cartCount"
                  class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">0</span>
        </button>

        <?php if (isset($_SESSION['client_initial'])): ?>
            <a href="profil_client.php" class="text-decoration-none">
                <div class="user-circle d-flex align-items-center justify-content-center me-2">
                    <?= htmlspecialchars($_SESSION['client_initial']) ?>
                </div>
            </a>
            <form action="logout.php" method="post" class="d-inline">
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Déconnexion
                </button>
            </form>
        <?php else: ?>
            <a href="nouveau_compte.php">
                <i id="icon_person" class="bi bi-person-fill"></i>
            </a>
        <?php endif; ?>
    </div>
</header>

<main class="container my-5">
    <h1 class="mb-4">Mon profil</h1>

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h5 class="card-title mb-3">
                Bonjour,
                <?= htmlspecialchars($client['prenom']) . ' ' . htmlspecialchars($client['nom']) ?>
            </h5>

            <p class="mb-1"><strong>Nom :</strong> <?= htmlspecialchars($client['nom']) ?></p>
            <p class="mb-1"><strong>Prénom :</strong> <?= htmlspecialchars($client['prenom']) ?></p>
            <p class="mb-1"><strong>Email :</strong> <?= htmlspecialchars($client['email']) ?></p>
            <p class="mb-1">
                <strong>Date d'inscription :</strong>
                <?= htmlspecialchars($client['date_inscription']) ?>
            </p>

            <hr>

            <a href="Boutique.php" class="btn btn-primary">Retour à la boutique</a>
        </div>
    </div>
</main>

<footer class="footer mt-5">
    <!-- يمكنك نسخ نفس الفوتر من الصفحات الأخرى -->
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
            <div>
                <a class="text-decoration-none text-light" href="https://maps.app.goo.gl/pULLpi12J8Z6PDHFA"><i
                        class="bi bi-geo-alt-fill"> 10 Rue de la Gare <br> 42000 Saint-Etienne</i></a><br><br>
                <a class="text-decoration-none text-light" href="mailto:g7k6o@example.com"><i
                        class="bi bi-envelope-fill"> g7k6o@example.com</i></a><br><br>
                <a class="text-decoration-none text-light" href="tel:04 77 57 17 38"><i
                        class="bi bi-telephone-fill"> 04 77 57 17 38</i></a>
            </div>
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

<script src="js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>
