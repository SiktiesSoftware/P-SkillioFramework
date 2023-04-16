<body>
    <header>
        HEADER.PHP
        <?php if (isset($_SESSION["user"])) { ?>
            <p><?= "Connected user : ".$_SESSION["user"]["nickname"] ?></p>
        <?php } ?>
        <?php //include("lang.php"); ?>
    </header>