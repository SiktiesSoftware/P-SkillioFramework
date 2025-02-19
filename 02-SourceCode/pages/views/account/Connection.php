<main>
CONNECTION.PHP
    <form action="<?= Route::GetVerificationByName("verifyAuth")->GetRoute("verifyLogin")->GetLink() ?>" method="POST">
        <input type="text" name="nickname" id="nickname">
        <?= isset($errors["nickname"]) ? '<p style="color:red;"/>' . $errors["nickname"] . '' : ' ' ?>
        <br>
        <input type="password" name="password" id="password">
        <?= isset($errors["password"]) ? '<p style="color:red;"/>' . $errors["password"] . '' : ' ' ?>
        <?= isset($errors["all"]) ? '<p style="color:red;"/>' . $errors["all"] . '' : ' ' ?>
        <input type="submit" value="Connection">
    </form>
</main>