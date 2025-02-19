<nav>
    NAV.PHP <br>
    <a href=<?= Route::GetRouteByName("home")->GetLink() ?>>home</a>  <br>
    <a href=<?= Route::GetRouteByName("contact")->GetLink() ?>>contact</a>  <br>
    <a href=<?= Route::GetMiddlewareByName("users")->GetRoute("user")->GetLink()."?id=1" ?>>user</a>  <br>
    <a href=<?= Route::GetMiddlewareByName("users")->GetRoute("users")->GetLink() ?>>users</a>  <br>
    <a href=<?= Route::GetGroupByName("Account")->GetRoute("connection")->GetLink() ?>>connection</a>  <br>
    <a href=<?= Route::GetGroupByName("Account")->GetRoute("inscription")->GetLink() ?>>inscription</a>  <br>
    <a href=<?= Route::GetGroupByName("Account")->GetRoute("disconnect")->GetLink() ?>>disconnect</a>  <br>
</nav>