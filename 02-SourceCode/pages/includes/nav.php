<nav>
    NAV.PHP <br>
    <a href=<?= Route::GetRouteByName("home")->link ?>>home</a>  <br>
    <a href=<?= Route::GetRouteByName("contact")->link ?>>contact</a>  <br>
    <a href=<?= Route::GetMiddlewareByName("users")->GetRoute("user")->link."?id=1" ?>>user</a>  <br>
    <a href=<?= Route::GetMiddlewareByName("users")->GetRoute("users")->link ?>>users</a>  <br>
    <a href=<?= Route::GetGroupByName("Account")->GetRoute("connection")->link ?>>connection</a>  <br>
    <a href=<?= Route::GetGroupByName("Account")->GetRoute("inscription")->link ?>>inscription</a>  <br>
</nav>