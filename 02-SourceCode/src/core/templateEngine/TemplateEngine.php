<?php
namespace skillio\core\templateEngine;

use skillio\core\interfaces\INestable;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\utils\content\View;
use skillio\core\utils\Renderable;

/**
 * TemplateEngine is a singleton class that provides methods to render templates.
 * It supports rendering variables, instructions, and child components.
 */
class TemplateEngine
{
    /** @var TemplateEngine|null */
    private static ?TemplateEngine $instance = null;

    /** @var Renderable */
    private Renderable $renderable;

    /** @var array<string> */
    private const array TAGS = [
        "child" => "@child",
        "children" => "@children",  
        "script" => "@scripts",
        "style" => "@styles",
    ];

    /** @var array<array{header:string,hasContent:bool,isEnd:bool}> */
    private const array INSTRUCTIONS = 
    [
        [
            "header" => "foreach",
            "hasContent" => true,
            "isEnd" => false,
        ],
        [
            "header" => "if",
            "hasContent" => true,
            "isEnd" => false,
        ],
        [
            "header" => "elseif",
            "hasContent" => true,
            "isEnd" => false,
        ],
        [
            "header" => "else",
            "hasContent" => false,
            "isEnd" => false,
        ],
        [
            "header" => "endforeach",
            "hasContent" => false,
            "isEnd" => true,
        ],
        [
            "header" => "endif",
            "hasContent" => false,
            "isEnd" => true,
        ]
    ];

    /**
     * TemplateEngine constructor.
     */
    private function __construct(Renderable $renderable)
    {
        $this->renderable = $renderable;
    }

    /**
     * Returns the singleton instance of TemplateEngine.
     *
     * @return TemplateEngine
     */
    public static function getInstance(?Renderable $renderable = null): TemplateEngine
    {
        if (self::$instance === null || $renderable !== null) 
            self::$instance = new TemplateEngine($renderable);
        
        return self::$instance;
    }

    /**
     * Renders the template.
     *
     * @return string The rendered content.
     */
    private function replacePlaceholders(array $patterns, string $content, ?string $subject = null): void
    {
        $this->renderable->setContent(content: str_replace(
            search: $patterns,
            replace: $content,
            subject: $subject ?? $this->renderable->getContent()
        ));
    }

    /**
     * Renders the children of a renderable.
     */
    public function renderChildren(): void
    {        
        $childTag = self::TAGS["child"];

        // Check if the renderable implements INestable interface
        if (!$this->renderable instanceof INestable) {
            new InternalException(
                "The renderable must implement INestable interface, got: " . get_class($this->renderable),
            );
            return;
        }

        // Iterate through each child and replace the placeholder in the content
        foreach ($this->renderable->getChildren() as $name => $content)
        {
            // Get the html content of the child, then replace the placeholder in the content
            $htmlContent = strval($content);

            // Replace the placeholder in the content
            $namePatterns = ["{$childTag}.name=\"{$name}\"", "{$childTag}.name=\'{$name}\'"]; 
            $this->replacePlaceholders(patterns: $namePatterns, content: $htmlContent);    
        }
    }

    /**
     * Renders the instructions in the content of the renderable.
     */
    public function renderInstructions(): void
    {
        // Set the pattern to match the instructions
        // Instruction example : @foreach($users as $user) @endforeach
        foreach (self::INSTRUCTIONS as $key => $instruction) 
        {   
            // If the instruction has no content, we don't need to match it
            if (!$instruction['hasContent'])
                $pattern = "/(@{$instruction['header']})/s";
            else
                $pattern = "/(@{$instruction['header']}\s*\(.*?[\)]+)/s";
            
            // Replace the instruction in the content with the PHP code
            $this->renderable->setContent(content: preg_replace_callback($pattern, function($matches) use ($instruction)
            {
                array_shift($matches); // Remove the full match

                // Extract the instruction
                $instructionHeader = "<?php " . str_replace("@", "", $matches[0]) . ($instruction["isEnd"] ? "; ?>" : ": ?>");
                return $instructionHeader;

            }, $this->renderable->getContent())); 
        }
    }

    /**
     * Renders the variables in the content of the renderable.
     */
    public function renderVariables() : void
    {
        $pattern = "/\{\{(.*?)\}\}/m";
        $namePattern = "/(\\$\w+)/m";

        // Replace the variables in the content with their values
        $this->renderable->setContent(content: preg_replace_callback(
            pattern: $pattern,
            callback: function($matches) use ($namePattern) 
            {
                array_shift($matches); // Remove the full match
                $expression = trim($matches[0]);

                preg_match_all($namePattern, $expression, $nameMatches);
                array_shift($nameMatches); // Remove the full match

                return "<?= " . $expression . " ?>";
            },
            subject: $this->renderable->getContent()
        ));
    }

    /**
     * Renders the content of the renderable.
     *
     * @return string The rendered content.
     */
    public function renderLayout(): void
    {
        // If the renderable is a View and has a layout, render the layout
        if(($this->renderable instanceof View) && $this->renderable->hasLayout())
        {
            // Get the layout content
            $layoutContent = $this->renderable->getLayout();
            if (!str_contains($layoutContent, self::TAGS["children"]))
                new InternalException(
                    "The layout must contain the \"@children\" tag, got: " . $layoutContent,
                );

            $this->replacePlaceholders(
                patterns: [self::TAGS["children"]],
                content: $this->renderable->getContent(),
                subject: $layoutContent
            );
        }
    }

    /**
     * Renders the JavaScript files in the renderable.
     * This method replaces the @script tag with the script tag and the src attribute.
     */
    public function renderJavascript(): void
    {
        // If the renderable is a View and has a javascript file, render the javascript
        if(($this->renderable instanceof Renderable && $this->renderable->hasJs()))
        {
            $htmlContent = "";
            foreach ($this->renderable->getJs() as $jsFile) 
                $htmlContent .= "<script src=\"" . $jsFile . "\"></script>";

            $this->replacePlaceholders(
                patterns: [self::TAGS["script"]],
                content: $htmlContent
            );
        }
        else
            $this->replacePlaceholders(
                patterns: [self::TAGS["script"]],
                content: ""
            );
    }

    /**
     * Renders the CSS files in the renderable.
     * This method replaces the @style tag with the link tag and the href attribute.
     */
    public function renderCss(): void
    {
        // If the renderable is a View and has a css file, render the css
        if(($this->renderable instanceof Renderable && $this->renderable->hasCss()))
        {
            $htmlContent = "";
            foreach ($this->renderable->getCss() as $cssFile) 
                $htmlContent .= "<link rel=\"stylesheet\" href=\"" . $cssFile . "\">";

            $this->replacePlaceholders(
                patterns: [self::TAGS["style"]],
                content: $htmlContent
            );
        }
        else
            $this->replacePlaceholders(
                patterns: [self::TAGS["style"]],
                content: ""
            );
    }
}
?>