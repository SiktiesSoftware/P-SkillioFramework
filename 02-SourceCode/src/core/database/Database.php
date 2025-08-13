<?php
namespace skillio\core\database;

use PDO;
use PDOException;
use PDOStatement;
use skillio\core\internal\exceptions\InternalException;

/**
 * Database class provides a singleton instance for database connection and query execution.
 * It allows executing simple and prepared queries, formatting results, and managing database connections.
 * This class uses PDO for database interactions and ensures that only one instance of the database connection exists.
 */
class Database
{
    /** @var Database Instance of the Database class */
    private static Database $instance; 

    /** @var PDO The PDO connector for database interactions */
    public PDO $connector;

    /**
     * Private constructor to prevent direct instantiation
     * Initializes the database connection
     */
    private function __construct(bool $withErrors)
    {
        // Get the database connection parameters from environment variables
        list($host, $port, $name, $user, $password, $charset) = [
            getenv("DATABASE_HOST"),
            getenv("DATABASE_PORT"),
            getenv("DATABASE_NAME"),
            getenv("DATABASE_USER"),
            getenv("DATABASE_PASSWORD"),
            getenv("DATABASE_CHARSET")
        ];

        // Connect to the database using PDO
        try 
        {
            $this->connector = new PDO(
                dsn: "mysql:host=$host;port=$port;dbname=$name;charset=$charset",
                username: $user,
                password: $password
            );
            $this->connector->setAttribute(PDO::ATTR_ERRMODE, $withErrors ? PDO::ERRMODE_EXCEPTION : PDO::ERRMODE_SILENT);
        } 
        catch (PDOException $e) 
        {
            new InternalException(
                message: "Database connection failed: " . $e->getMessage(),
                code: $e->getCode()
            );
        }
    }

    /**
     * Get the instance of the database
     * @return Database => the instance of the database
     */
    public static function getInstance(bool $withErrors = true): Database
    {
        if (!isset(self::$instance))
            self::$instance = new Database(withErrors: $withErrors);

        return self::$instance;
    }

    /**
     * Does a simple execution
     * @param query => query to execute
     * @return array<string,PDOStatement|array> the result of the query as an associative array
     */
    public function QuerySimpleExecute(string $query, mixed $class = null): array
    {
        $req = $this->connector->prepare(query: $query);
        $req->execute();
        return $this->FormatData(req: $req, class: $class);
    }

    /**
     * Prepare and execute a query
     * @param query => query to execute
     * @param binds => binds of the query
     * @return array<string,PDOStatement|array> the result of the query as an associative array
     */
    public function QueryPrepareExecute(string $query, array $binds, mixed $class = null): array
    {
        $req = $this->connector->prepare(query:$query);
        foreach ($binds as $bind) 
            $req->bindValue(param: $bind["param"], value: $bind["value"], type: $bind["type"]);
        
        $req->execute();
        return $this->FormatData(req: $req, class: $class);
    }

    /**
     * Format the req to an assoc array
     * @param req => req to format
     * @return array<string,PDOStatement|array> the result of the query as an associative array
     */
    private function FormatData(PDOStatement $req, mixed $class): array
    {
        $data = [];
        if ($class == null)
            $data = $req->fetchAll(mode: PDO::FETCH_OBJ);
        else
            $data = $req->fetchAll(mode: PDO::FETCH_CLASS, class: $class);

        return ["data" => $data, "req" => $req];
    }

    /**
     * Unset data
     * @param req => request to close
     */
    public function UnsetData(PDOStatement $req): void
    {
        $req->closeCursor();
    }
}
