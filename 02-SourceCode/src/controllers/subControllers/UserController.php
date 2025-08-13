<?php
namespace skillio\controllers\subControllers;

use skillio\core\routing\Controller;
use skillio\core\routing\Request;
use skillio\core\routing\Response;
use skillio\core\routing\response\RedirectResponse;
use skillio\core\routing\Router;
use skillio\core\utils\content\Component;
use skillio\core\utils\content\View;

class UserController extends Controller
{
    public function test(Request $request): Response
    {
        return new Response(
            content: View::set("test", ["john" => ["name" => "John Doe", "age" => 30, "email" => "john.doe@example.com", "location" => "New York", "occupation" => "Software Engineer", "hobby" => "Coding", "phone" => "123-456-7890", "skills" => ["PHP", "JavaScript", "HTML", "CSS"]]]
            )->children(
                name: "test",
                content: Component::set("test")->children(
                    name: "content",
                    content: '@if(is_array($value))
                                <span style="color: purple;">
                                    {{ $key }} : {{ implode(", ", $value) }}</span>
                                </span>
                            @else
                                <span style="color: aqua;">
                                    {{ $key }} : {{ $value }}
                                </span>
                            @endif'
                )
            )->title("Users Test Page"
            )->layout("main"),
        );
    }

    public function create(Request $request): Response
    {
        $validator = $request->validateWithErrors(fields: [
            "name" => "required|string:strict|min:2|max:50",
            "email" => "required|email",
            "birthdate" => "required|date|today",
            "element" => "required|string|min:1|max:4",
            "hobby" => "required|int"
        ]);

        $validator->save();
        if($validator->anyError())
            return new RedirectResponse(url: Router::getRouteByName("home")->link());

        return new Response(
            content: "create user",
            statusCode: 200,
            headers: ["Content-Type" => "text/html"]
        );
    }
}
?>