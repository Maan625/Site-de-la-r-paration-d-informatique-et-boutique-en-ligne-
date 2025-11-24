<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
 

session_start();

// استدعاء اتصال قاعدة البيانات (نفس ملف الكونفيغ الذي تستعمله في الحساب أو البوتيك)
require 'config_compte.php'; // أو config.php إن كان عندك ملف عام

$errors = [];
$successMessage = null;

// عند إرسال الفورم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // جلب القيم
    $prenom    = trim($_POST['prenom'] ?? '');
    $nom       = trim($_POST['nom'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $objet     = trim($_POST['objet'] ?? '');
    $message   = trim($_POST['message'] ?? '');

    // التحقق من الحقول
    if ($prenom === '')   $errors[] = "Le prénom est obligatoire.";
    if ($nom === '')      $errors[] = "Le nom est obligatoire.";
    if ($email === '')    $errors[] = "L'email est obligatoire.";
    if ($objet === '')    $errors[] = "L'objet est obligatoire.";
    if ($message === '')  $errors[] = "Le message est obligatoire.";

    // إذا لا توجد أخطاء → حفظ في قاعدة البيانات
    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO messages_contact (prenom, nom, email, telephone, objet, message)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$prenom, $nom, $email, $telephone, $objet, $message]);

        $successMessage = "Votre message a bien été envoyé. Merci de nous avoir contactés.";
        // لتفريغ الحقول بعد الإرسال
        $_POST = [];

        $to      = "mz777778@gmail.com"; // غيّره إلى بريدك
        $subject = "Nouveau message de contact : " . $objet;
        $body    = "De : $prenom $nom\nEmail : $email\nTéléphone : $telephone\n\nMessage :\n$message";
        $headers = "From: noreply@votresite.com\r\nReply-To: $email\r\n";

        @mail($to, $subject, $body, $headers);
    }

}
$mail = new PHPMailer(true);

try {
    // إعدادات SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mz777778@gmail.com';   // بريدك
    $mail->Password   = 'gskk akqf ibzz nmgv';      // كلمة المرور (App Password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // المرسل
    $mail->setFrom($email, $prenom . " " . $nom);

    // المستلِم
    $mail->addAddress('mz777778@gmail.com'); // بريدك

    // المحتوى
    $mail->isHTML(true);
    $mail->Subject = "Nouveau message de contact : $objet";
    $mail->Body = "
        <h3>Nouveau message de contact</h3>
        <p><strong>Prénom:</strong> $prenom</p>
        <p><strong>Nom:</strong> $nom</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Téléphone:</strong> $telephone</p>
        <p><strong>Objet:</strong> $objet</p>
        <p><strong>Message:</strong><br>$message</p>
    ";

    $mail->send();

} catch (Exception $e) {
 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="scss/main.css">
    <title>Document</title>
</head>

<body>
    <header class="header_reparation_informatique text-center ">
        <nav id="nav_list" class="navbar navbar-dark  ">
            <div class="container-fluid ">
                <button id="button_list" class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent "
                    aria-expanded="false" aria-label="Toggle navigation ">
                    <span id="span_list" class="navbar-toggler-icon  "></span>
                </button>
            </div>
        </nav>
        <div class="collapse " id="navbarToggleExternalContent">
            <div class="bg-dark p-4 " id="div_link">
                <a href="index.html"> <img src="images/Rectangle 2.png" alt="logo" class="logo w-50 "></a>
                <div class="div_group_link">

                    <a class="link_navber" href="index.html"><button class="btn_link">Accueil</button></a>
                    <a class="link_navber" href="reparation_informatique.html"><button class="btn_link">Réparation
                            informatique</button></a>
                    <a class="link_navber" href="reparation_telephone.html"><button class="btn_link">Réparation
                            téléphone</button></a>
                    <a class="link_navber" href="reparation_console_de_jeux.htm"><button class="btn_link">Réparation
                            console de
                            jeux</button></a>
                    <a class="link_navber" href="Micro_soudure.html"><button class="btn_link">Micro-soudure</button></a>
                    <a class="link_navber" href="Boutique.php"><button class="btn_link">Boutique en ligne</button></a>
                    <a class="link_navber" href="contact.html"><button class="btn_link">Contact </button></a>


                </div>
                <div class="icon_réseaux_sociale">
                    <a href=""> <i class="bi bi-facebook"></i></a>
                    <a href=""> <i class="bi bi-instagram"></i></a>
                    <a href="https://wa.me/33612345678?text=Bonjour!" target="_blank"> <i
                            class="bi bi-whatsapp"></i></a>

                </div>
            </div>
        </div>

        <a href="index.html"><img src="images/ChatGPT Image 13 oct. 2025, 20_55_41.png" alt="logo" class="logo__1 "></a>

        <h1 id="titre_Reparation">Contact</h1>
        <a class="phone btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
            href="tel:+33612345676"><i id="telehpne" class="bi bi-telephone-fill"></i></a>

        <a class="whatsapp btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
            href="https://wa.me/33612345678?text=Bonjour!" target="_blank">
            <i class="bi bi-whatsapp"></i>
        </a>
        <button onclick="changerMode()" id="modeButton">🌙</button>


    </header>
    <main class="main_réparation">

   
            <section class="formulaire justify-content-center mb-3 ">
                <h2 class="titre_formulaire text-center  mb-5"> Formulaire de contact</h2>

                <!-- رسائل النجاح -->
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success w-75 mx-auto">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>

                <!-- رسائل الأخطاء -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger w-75 mx-auto">
                        <ul class="mb-0">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form class="form" action="contact.php" method="post">
                    <section class="n_p_e_t">
                        <div class="nom_prenom">
                            <div>
                                <label for="prenom">Prénom</label><br>
                                <input class="w-50" type="text" name="prenom" id="prenom"
                                    value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                            </div>
                            <br><br>
                            <div>
                                <label for="nom">Nom</label><br>
                                <input class="w-50" type="text" name="nom" id="nom"
                                    value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="email_telephone mt-4">
                            <div>
                                <label for="email">Email</label><br>
                                <input class="w-50" type="email" name="email" id="email"
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <br><br>
                            <div>
                                <label for="telephone">Telephone</label><br>
                                <input class="w-50" type="tel" name="telephone" id="telephone"
                                    value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                            </div>
                        </div>
                    </section>
                    <section class="opjet_textarea">
                        <label class="ms-2" for="objet">Objet</label><br>
                        <input class="w-100 ms-2" type="text" name="objet" id="objet"
                            value="<?= htmlspecialchars($_POST['objet'] ?? '') ?>">
                        <br><br>
                        <label class="ms-2" for="message">Message</label><br>
                        <textarea class="w-100 ms-2 texterea" name="message" id="message" cols="30" rows="10"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </section>
                    <div class="w-100 text-center">
                        <button class="button_header mb-5 mt-5" type="submit">Envoyer</button>
                    </div>
                </form>


            </section>

            <!-- section  -->

            <!-- section maps , téléphone et email -->
            <section class="contact ">
                <div class="row justify-content-md-between  ">
                    <div class="col-md-3 mb-2  text-center">
                        <img src="images/contacte/map.png" alt="image_map">
                        <a class="text-decoration-none" href="https://maps.app.goo.gl/pULLpi12J8Z6PDHFA">
                            <p class="para_map text-light me-2 ">10 Rue de la Gare <br> 42000 Saint-Etienne</p>
                        </a>
                    </div>
                    <div class="col-md-3 mb-2 text-center">
                        <img src="images/contacte/telephon.png" alt=" image_telephone">
                        <a class="text-decoration-none" href="tel:04 77 57 17 38">
                            <p class=" para_numero text-light">04 77 57 17 38</p>
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <img src="images/contacte/email.png" alt="image_email">
                        <a class="text-decoration-none" href="mailto:Example@gmail">
                            <p class=" para_email text-light">Example@gmail.com</p>
                        </a>
                    </div>
                </div>

            </section>
            <section class="trouver mt-0">
                <div class="row">
                    <div class="col-md-6">
                        <p class="para_trouver">Assistance informatique et téléphonie dans la région de ..............</p>
                        <h2 class="titre_trouver">Où nous trouver pour la réparation informatique ?</h2>
                        <p class="fw-bolder text-dark fs-5 "><em class="fw-normal text-dark">Nous sommes situé au</em> 10
                            Rue de la Gare, 42000 Saint-Etienne</p>

                    </div>
                    <div class="col-md-6">
                        <iframe id="ifram_trouver"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d179216.746425662!2d4.201655030632378!3d45.424226428781054!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f5abff0dcfe415%3A0x631b2db87635756!2sSaint-%C3%89tienne!5e0!3m2!1sfr!2sfr!4v1761753511150!5m2!1sfr!2sfr"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </section>
    </main>
    <footer class="footer mt-0">
        <div class="div_footer ">
            <div class="logo_social d-flex ">
                <a href="index.html"><img src="images/Rectangle 2.png" alt=""></a>
                <div class="icon_réseaux_sociale">
                    <a href=""> <i class="bi bi-facebook"></i></a>
                    <a href=""> <i class="bi bi-instagram"></i></a>
                    <a href="https://wa.me/33612345678?text=Bonjour!" target="_blank"> <i
                            class="bi bi-whatsapp"></i></a>

                </div>
            </div>
            <div class="contact_map text-light ">
                <!-- <h2>Contactez-nous</h2> -->
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
                <!-- <h2 class="titre_plan text-light">plan du site</h2> -->
                <ul class="plan_du_site">
                    <li class="line"><a href="index.html">Accueil</a></li>
                    <li class="line"><a href="reparation_informatique.html">Réparation informatique</a></li>
                    <li class="line"><a href="reparation_telephone.html">Réparation telephone</a></li>
                    <li class="line"><a href="reparation_console_de_jeux.htm">Réparation console de jeux</a></li>
                    <li class="line"><a href="Micro_soudure.html">Micro-soudure</a></li>
                    <li class="line"><a href="Boutique.html">Boutique en ligne</a></li>
                    <li class="line"><a href="contact.html">Contact</a></li>
                </ul>
            </div>
        </div>
        <p class="text-light text-center mt-3">Copyright© 2025 - 2026 - Tous droits reservé </p>
    </footer>
    <script src="js/main.js"></script>
</body>

</html>