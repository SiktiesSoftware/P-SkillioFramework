<?php 
//include_once __DIR__."/../../core/languages/Lang.php"; 
?>
<!DOCTYPE html>
<html lang="<?= "fr"//$_SESSION["lang"] ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Includes
    include_once __DIR__."/../../core/global/route.php";
    include_once __DIR__."/../../core/global/view.php";
    ?>

    <!-- NATIVE CSS -->
    <link rel="stylesheet" href="<?=Request::$cssLinkReturn ?>/resources/css/main.css">

    <!-- TAILWIND -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <title>Site web</title>
</head>