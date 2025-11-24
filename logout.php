<?php
session_start();

// حذف كل متغيرات السيشن
session_unset();

// تدمير السيشن بالكامل
session_destroy();

// إعادة التوجيه لصفحة البوتيك (أو الصفحة الرئيسية التي تريدها)
header("Location: Boutique.php");
// header("Location: index.html"); // لو تريد صفحة أخرى
exit;
