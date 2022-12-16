<main>
    USERS.PHP
    <?php
        foreach ($users as $key => $user) 
        {
            echo $user->nickname;
            echo "<br>";
        }
    ?>
</main>