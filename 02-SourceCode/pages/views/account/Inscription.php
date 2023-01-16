<main>
INSCRIPTION.PHP
<form action="<?= Route::GetVerificationByName("verifyAuth")->GetRoute("verifySignin")->link ?>" method="POST">
    <input type="text" name="nickname" id="nickname">
    <?= isset($errors["nickname"]) ? '<p style="color:red;"/>' . $errors["nickname"] . '' : ' ' ?>
    <br>
    <input type="password" name="password" id="password">
    <?= isset($errors["password"]) ? '<p style="color:red;"/>' . $errors["password"] . '' : ' ' ?>
    <br>
    <input type="password" name="passwordConfirm" id="passwordConfirm">
    <?= isset($errors["passwordConfirm"]) ? '<p style="color:red;"/>' . $errors["passwordConfirm"] . '' : ' ' ?>

    <?= isset($errors["all"]) ? '<p style="color:red;"/>' . $errors["all"] . '' : ' ' ?>
    <input type="submit" value="Connection">
</form>
</main>