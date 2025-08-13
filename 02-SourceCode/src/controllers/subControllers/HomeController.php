<?php
namespace skillio\controllers\subControllers;

use skillio\core\languages\Lang;
use skillio\core\routing\Controller;
use skillio\core\routing\Request;
use skillio\core\routing\Response;
use skillio\core\utils\content\View;

class HomeController extends Controller
{
    public function about(Request $request)
    {
        $validatedData = $request->validateWithErrors(fields: [
            "lang" => "required|string|min:2|max:3",
            "name" => "required|string|min:2|max:50|date",
        ]);
        
        echo "<pre>";
        var_dump($validatedData);
        echo "</pre>";

        $lang = $request->getQueryParams()->get(key: "lang", default: "en");
        $language = Lang::getLanguage(lang: $lang);

        $translations = $language->get(key: "about");
        
        return new Response(
            content: View::set(
                name: "about",
                data: [
                    "title" => $translations["title"],
                    "description" => $translations["description"],
                ]
            )->title("About Us"
            )->js(name: "about"
            )->js(name: "test"
            )->css(name: "about"),
            statusCode: 200,
            headers: ["Content-Type" => "text/html"]
        );
    }

    public function book(Request $request)
    {
        $params = $request->getQueryParams();

        return new Response(
            content: "<h1>This is the book page</h1> <hr> User ID: " . ($params['id'] ?? 'unknown') . ", Book ID: " . ($params['bookId'] ?? 'unknown'),
            statusCode: 200,
            headers: ["Content-Type" => "text/html"]
        );
    }

    public function information()
    {
        return new Response(
            content: json_encode(['message' => 'Information page', 'status' => 'success']),
            statusCode: 200,
        );
    }

    public function dashboard()
    {
        return new Response(
            content: "<h1>Dashboard</h1> <hr> Welcome to the dashboard. Here you can manage your application settings.",
            statusCode: 200,
            headers: ["Content-Type" => "text/html"]
        );
    }

    public function settings()
    {
        return new Response(
            content: "<h1>Settings Page</h1> <hr> This page allows you to change your application settings.",
            statusCode: 200,
            headers: ["Content-Type" => "text/html"]
        );
    }

    public function contact()
    {
        return new Response(
            content: "<h1>Contact Page</h1> <hr> This page contains contact information for the application.",
            statusCode: 200,
            headers: ["Content-Type" => "text/html"]
        );
    }
}
?>