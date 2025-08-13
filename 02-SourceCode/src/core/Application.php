<?php
namespace skillio\core;

use skillio\core\languages\Lang;
use skillio\core\routing\Router;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class Application
 * Represents the main application instance.
 *
 * @package skillio\core
 */
class Application
{
    /** @var Application Instance of the Application class */
    private static Application $instance;

    /** @var array $configs Holds the application configurations */
    private array $configs = [];

    /**
     * Get the application configuration by name.
     *
     * @param string $name The name of the configuration file (without extension).
     * @return array The configuration data.
     */
    public function getConfigFile(string $name): array
    {
        $name = strtolower($name . ".config");
        return $this->configs[$name] ?? [];
    }
    
    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        // Initialize the application
        $this->initialize();
        require_once __DIR__ . "/../routes/master.php";
    }

    /**
     * Get the singleton instance of the Application class.
     *
     * @return Application The instance of the Application class.
     */
    public static function getInstance(): Application
    {
        // If the instance is not set, create a new one
        if (!isset(self::$instance)) 
            self::$instance = new self();
        
        return self::$instance;
    }

    /**
     * Run the application.
     *
     * This method initializes the routing and starts the application.
     */
    public function run()
    {
        Router::route();
    }

    /**
     * Initialize the application.
     *
     * This method loads environment variables and performs any necessary setup.
     */
    private function initialize(): void
    {
        // Start the session
        Session::start();

        // Get the environment variables
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        if($_ENV["API"] === "true")
            $_ENV["INTEGRATED_LANGUAGES"] = false; // Disable integrated languages for API

        // Setup the languages
        Lang::setup();

        // Load the application configurations
        $configPath = __DIR__ . '/../.configs/';
        if (is_dir($configPath)) 
        {
            foreach (glob($configPath . '*.json') as $configFile) 
            {
                $key = basename($configFile, '.json');
                $this->configs[strtolower($key)] = json_decode(file_get_contents($configFile), true);
            }
        }
    }
}
?>