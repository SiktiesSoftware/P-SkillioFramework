<nav>
    NAV.PHP <br>
    <a href=<?= "/projects/P-SkilioFramework/02-SourceCode".Route::GetRouteByName("home")->link ?>>home</a>  <br>
    <a href=<?= "/projects/P-SkilioFramework/02-SourceCode".Route::GetRouteByName("contact")->link ?>>contact</a>  <br>
    <a href=<?= "/projects/P-SkilioFramework/02-SourceCode".Route::GetMiddlewareByName("users")->GetRoute("user")->link."?id=1" ?>>user</a>  <br>
    <a href=<?= "/projects/P-SkilioFramework/02-SourceCode".Route::GetMiddlewareByName("users")->GetRoute("users")->link ?>>users</a>  <br>
    <a href=<?= "/projects/P-SkilioFramework/02-SourceCode".Route::GetGroupByName("Account")->GetRoute("connection")->link ?>>connection</a>  <br>
    <a href=<?= "/projects/P-SkilioFramework/02-SourceCode".Route::GetGroupByName("Account")->GetRoute("inscription")->link ?>>inscription</a>  <br>
</nav>