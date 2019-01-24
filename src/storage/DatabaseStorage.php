<?php
/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/22/2019
 * Time: 1:38 AM
 */

namespace storage;


use PDO;
use storage\interfaces\StorageInterface;

class DatabaseStorage implements StorageInterface
{
    /**
     * DatabaseStorage connection
     *
     * @var PDO
     */
    protected $conn;

    /**
     * Name of database table
     *
     * @var string
     */
    protected $tableName = 'logs';

    /**
     * DatabaseStorage constructor.
     * @param string $host
     * @param string $dbName
     * @param string $tableName
     * @param string $userName
     * @param string $password
     */
    public function __construct(
        string $host,
        string $dbName,
        string $tableName,
        string $userName,
        string $password = '')
    {
        $dsn = "mysql:host=$host;dbname=$dbName";

        $this->conn = new PDO($dsn, $userName, $password);

        if ($tableName) $this->tableName = $tableName;
    }

    /**
     * Save event
     *
     * @param array $data
     * @return bool
     */
    public function save(array $data): bool
    {
        $sql = /** @lang text */
            "INSERT INTO $this->tableName (type, name, performer, subject, additional, created) 
        VALUES (:type, :name, :performer, :subject, :additional, :created)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Get one or more events
     *
     * @param array $criteria
     * @return mixed
     */
    public function get(array $criteria = [])
    {
        if (empty($criteria)) {

            $sql = /** @lang text */
                "SELECT * FROM $this->tableName";
        } else {

            $sql = /** @lang text */
                "SELECT * FROM $this->tableName WHERE";

                // Concat query string
                $firstCriteria = true;
                foreach ($criteria as $key => $value) {
                    $sql .= $firstCriteria ? ' ' : ' AND ';
                    $sql .= $key . '=:' . $key;
                    $firstCriteria = false;
                }
        }

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($criteria);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}