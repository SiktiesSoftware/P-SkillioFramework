<?php
/**
 * Manage the database interactions
 */
class Database
{
    private static Database $instance;           // Database instance
    private PDO $connector;                      // Database connection
 
    /**
     * Get the database
     * @return Database => unique database
     */
    public static function GetInstance() : Database
    {
        // Check if the database instance is set
        if (!isset(Database::$instance))
        {
            // Set the database instance and connect to db with config.json
            $serverInfo = json_decode(file_get_contents(__DIR__."/../../.config/config.json"),true, 2, JSON_OBJECT_AS_ARRAY);
            $login = json_decode(file_get_contents(__DIR__."/../../.config/secret.json"),true, 2, JSON_OBJECT_AS_ARRAY);
            Database::$instance = new Database
            (
                "mysql:host=".$serverInfo["host"].";dbname=".$serverInfo["databaseName"].";charset=".$serverInfo["charset"],
                $login["username"],
                $login["password"]
            );
        }

        // Return database instance
        return Database::$instance;
    }

    /**
     * Construct the object
     * @param dsn => the string of connection, with the host, name of the database and charset
     * @param username => name of the user
     * @param password => password of the user
     */
    private function __construct(string $dsn, string $username, string $password)
    {
        // Try to connect to the database or reject an error
        try
        {
            $this->connector = new PDO($dsn, $username, $password);
        }
        catch(PDOException $e)
        {
            die("Error : " . $e->getMessage());
        }
    }

    /**
     * Does a simple execution
     * @param query => query to execute
     * @return array => result of the query, an associative array
     */
    public function QuerySimpleExecute($query) : array
    {
        // Set request and send them to db
        $req = $this->connector->query($query);
        return $this->FormatData($req);
    }

    /**
     * Prepare and execute a query
     * @param query => query to execute
     * @param binds => binds of the query
     * @return array the result of the query as an assiosiative array
     */
    public function QueryPrepareExecute($query, $binds) : array
    {
        // Prepare query
        $req = $this->connector->prepare($query);

        // Set the binds
        foreach ($binds as $bind)
        {
            $req->bindValue($bind["param"], $bind["value"], $bind["type"]);
        }

        // Execute the query
        $req->execute();

        // Return array
        return $this->FormatData($req);
    }

    /**
     * Format the req to an assoc array
     * @param req => req to format
     * @return array => the fetched array
     */
    private function FormatData($req) : array
    {
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Unset data
     * @param req => request to close
     */
    public function UnsetData($req) : void
    {
        $req->closeCursor();
    }
}

?>