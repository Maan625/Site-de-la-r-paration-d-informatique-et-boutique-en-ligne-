 <?php
    // nouveau_compte.php
    session_start();
    require 'config_compte.php';


    // مصفوفات للأخطاء/الرسائل
    $registerErrors = [];
    $loginErrors    = [];
    $registerSuccess = null;

    // معالجة الفورم
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $formType = $_POST['form_type'] ?? '';

        /* ========== إنشاء حساب جديد ========== */
        if ($formType === 'register') {
            $nom      = trim($_POST['nom'] ?? '');
            $prenom   = trim($_POST['prenom'] ?? '');
            $telephone = trim($_POST['telephone'] ?? ''); // حاليًا لن نخزّنه إن لم يكن في الجدول
            $email    = trim($_POST['email'] ?? '');
            $pass     = $_POST['mot_de_passe'] ?? '';
            $pass2    = $_POST['mot_de_passe2'] ?? '';

            // التحقق من الحقول
            if ($nom === '')        $registerErrors[] = "Le nom est obligatoire.";
            if ($prenom === '')     $registerErrors[] = "Le prénom est obligatoire.";
            if ($email === '')      $registerErrors[] = "L'email est obligatoire.";
            if ($pass === '')       $registerErrors[] = "Le mot de passe est obligatoire.";
            if ($pass !== $pass2)   $registerErrors[] = "Les mots de passe ne correspondent pas.";

            // إن لم يكن هناك أخطاء، تأكد أن الإيميل غير مكرر
            if (!$registerErrors) {
                $stmt = $pdo->prepare("SELECT id_client FROM clients WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $registerErrors[] = "Cet email est déjà utilisé.";
                }
            }

            // إن لم يكن هناك أخطاء: حفظ المستخدم
            if (!$registerErrors) {
                $hash = password_hash($pass, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                INSERT INTO clients (nom, prenom, email, mot_de_passe, date_inscription)
                VALUES (?, ?, ?, ?, NOW())
            ");
                $stmt->execute([$nom, $prenom, $email, $hash]);

                $registerSuccess = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
            }
        }

        /* ========== تسجيل الدخول ========== */
        if ($formType === 'login') {
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['mot_de_passe'] ?? '';

            if ($email === '' || $pass === '') {
                $loginErrors[] = "Email et mot de passe sont obligatoires.";
            } else {
                $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
                $stmt->execute([$email]);
                $client = $stmt->fetch();

                if ($client && password_verify($pass, $client['mot_de_passe'])) {
                    // نجاح الدخول
                    $_SESSION['client_id']     = $client['id_client'];
                    $_SESSION['client_nom']    = $client['nom'];
                    $_SESSION['client_prenom'] = $client['prenom'];
                    $_SESSION['client_initial'] = strtoupper(substr($client['prenom'], 0, 1));

                    header("Location: Boutique.php"); // يمكنك تغييره لـ index.php إذا أحببت
                    exit;
                } else {
                    $loginErrors[] = "Email ou mot de passe incorrect.";
                }
            }
        }
    }
    ?>
 <!DOCTYPE html>
 <html lang="fr">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

         <div id="input_search" class="input_search input-group w-50">
             <input type="text" class="form-control" placeholder="Rechercher un produit" aria-label="Search">
             <button class="btn btn-outline-secondary" type="button" id="button-addon1"><i
                     class="bi bi-search"></i></button>
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
                 <!-- دائرة فيها أول حرف من اسم المستخدم -->
               
                  <a href="profile_client.php" class="text-decoration-none">
            <div class="user-circle d-flex align-items-center justify-content-center me-2">
                <?= htmlspecialchars($_SESSION['client_initial']) ?>
            </div>
        </a>
             <?php else: ?>
                 <!-- لم يسجل الدخول بعد -->
                 <a href="nouveau compt.php">
                     <i id="icon_person" class="bi bi-person-fill"></i>
                 </a>
             <?php endif; ?>
         </div>

     </header>

     <main class="main_compte">
         <button onclick="changerMode()" id="modeButton">🌙</button>

         <aside>
             <h1 class="créer_compte text-center">Créez votre compte</h1>
             <p class="para_compte  text-center fw-bold">
                 Veuillez remplir le formulaire ci-dessous pour créer votre compte
             </p>
             <p>Vous avez deja un compte? <input id="checkbox" type="checkbox"> </p>

             <!-- رسائل إنشاء الحساب -->
             <?php if ($registerSuccess): ?>
                 <div class="alert alert-success">
                     <?= htmlspecialchars($registerSuccess) ?>
                 </div>
             <?php endif; ?>

             <?php if ($registerErrors): ?>
                 <div class="alert alert-danger">
                     <ul class="mb-0">
                         <?php foreach ($registerErrors as $e): ?>
                             <li><?= htmlspecialchars($e) ?></li>
                         <?php endforeach; ?>
                     </ul>
                 </div>
             <?php endif; ?>

             <!-- فورم إنشاء حساب -->
             <form class="form_contact" method="post">
                 <input type="hidden" name="form_type" value="register">

                 <label for="nom">Nom</label><br>
                 <input class="w-75" type="text" id="nom" name="nom"><br>

                 <label for="prenom">Prénom</label><br>
                 <input class="w-75" type="text" id="prenom" name="prenom"><br>

                 <label for="telephone">Téléphone</label> <br>
                 <input class="w-75" type="text" id="telephone" name="telephone"><br>

                 <label for="email">Email</label><br>
                 <input class="w-75" type="email" id="email" name="email" required><br>

                 <label for="mot_de_passe">Mot de passe</label><br>
                 <input class="w-75" type="password" id="mot_de_passe" name="mot_de_passe" required><br>

                 <label for="mot_de_passe2">Confirmer mot de passe</label><br>
                 <input class="w-75" type="password" id="mot_de_passe2" name="mot_de_passe2" required><br><br>

                 <label class="lable_checkbox">
                     <input type="checkbox" required>
                     J'accepte les conditions d'utilisation
                 </label>

                 <button class="btn_inscrir text-center d-flex align-center mt-3" type="submit">
                     S'inscrire
                 </button>
             </form>

             <hr class="my-4">

             <!-- رسائل تسجيل الدخول -->
             <?php if ($loginErrors): ?>
                 <div class="alert alert-danger">
                     <ul class="mb-0">
                         <?php foreach ($loginErrors as $e): ?>
                             <li><?= htmlspecialchars($e) ?></li>
                         <?php endforeach; ?>
                     </ul>
                 </div>
             <?php endif; ?>

             <!-- فورم تسجيل الدخول -->
             <form class="form_contact_deja" method="post">
                 <input type="hidden" name="form_type" value="login">

                 <label for="login_email">Email</label><br>
                 <input class="w-75" type="email" id="login_email" name="email" required><br>

                 <label for="login_pass">Mot de passe</label><br>
                 <input class="w-75" type="password" id="login_pass" name="mot_de_passe" required><br>

                 <label class="lable_checkbox">
                     <input type="checkbox" required>
                     J'accepte les conditions d'utilisation
                 </label>

                 <button class="btn_inscrir text-center d-flex align-center mt-3" type="submit">
                     Se connecter
                 </button>
             </form>

         </aside>

         <article>
             <div class="image_article"></div>
         </article>
     </main>

     <footer class="footer mt-1">
         <div class="div_footer ">
             <div class="logo_social d-flex ">
                 <img src="images/Rectangle 2.png" alt="">
                 <div class="icon_réseaux_sociale">
                     <a href=""> <i class="bi bi-facebook"></i></a>
                     <a href=""> <i class="bi bi-instagram"></i></a>
                     <a href="https://wa.me/33612345678?text=Bonjour!" target="_blank"> <i
                             class="bi bi-whatsapp"></i></a>
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
                     <li class="line"><a href="#">Boutique en ligne</a></li>
                     <li class="line"><a href="#">Contact</a></li>
                 </ul>
             </div>
         </div>
         <p class="text-light text-center mt-3">Copyright© 2025 - 2026 - Tous droits reservé </p>
     </footer>

     <script src="js/main.js"></script>
 </body>

 </html>