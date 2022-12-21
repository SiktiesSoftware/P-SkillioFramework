<main>
    USERS.PHP
    <?php
        foreach ($users as $key => $user) 
        {
            echo Component::Set("User", ["user" => $user]);
        }
    ?>
</main>