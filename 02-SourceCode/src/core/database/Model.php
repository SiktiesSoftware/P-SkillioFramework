<?php
namespace skillio\core\database;

use PDO;

/**
 * Model class provides a base structure for all database models.
 * It includes methods for retrieving all records, getting a record by ID, and deleting a record by ID.
 * This class should be extended by specific model classes to implement additional functionality.
 */
abstract class Model
{
    /**
     * Get all records of the model
     * @return array All records of the model
     */
    public static function getAll(): array
    {
        // Get the database instance
        $db = Database::getInstance();

        // Prepare the query to get all the data from the table
        $query = "SELECT * FROM " . static::class;

        // Execute the query and return the result
        ["data" => $data] = $db->QuerySimpleExecute(query: $query, class: static::class);
        return $data;
    }

    /**
     * Get a record by its ID
     * @param int $id ID of the record
     * @return array Record with the given ID
     */
    public static function getById(int $id): array
    {
        // Get the database instance
        $db = Database::getInstance();

        // Prepare the query to get the data from the table by ID
        $query = "SELECT * FROM " . static::class . " WHERE id = :id";
        $binds =
        [
            ["param" => ":id", "value" => $id, "type" => PDO::PARAM_INT]
        ];

        // Execute the query and return the result
        ["data" => $data] = $db->QueryPrepareExecute(query: $query, binds: $binds, class: static::class);

        return $data;
    }

    /**
     * Insert a new record into the table
     * @param array $data Data to be inserted
     * @return void
     */
    public static function deleteById(int $id): bool
    {
        // Get the database instance
        $db = Database::GetInstance();

        // Prepare the query to delete the data from the table
        $query = "DELETE FROM " . static::class . " WHERE id = :id";
        $binds = 
        [
            ["param" => ":id", "value" => $id, "type" => PDO::PARAM_INT]
        ];

        // Execute the query with the parameters
        ["req" => $req] = $db->QueryPrepareExecute(query: $query, binds: $binds);
        return $req->rowCount() > 0;
    }
}
?>