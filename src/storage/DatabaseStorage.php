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
     * Name of database table for logs
     *
     * @var string
     */
    protected $logTableName = 'logs';

    /**
     * Name of database table for log templates
     *
     * @var string
     */
    protected $templateTableName = 'templates';

    /**
     * DatabaseStorage constructor.
     * @param string $host
     * @param string $dbName
     * @param string $userName
     * @param string $password
     * @param string $logTableName
     * @param string $templateTableName
     */
    public function __construct(
        string $host,
        string $dbName,
        string $userName,
        string $password = '',
        string $logTableName = '',
        string $templateTableName = ''
    )
    {
        $dsn = "mysql:host=$host;dbname=$dbName";

        // Try to connect, otherwise log and return error
        try {
            $this->conn = new PDO($dsn, $userName, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (\PDOException $e) {
            logError($e->getMessage());
        }

        if ($logTableName) $this->logTableName = $logTableName;
        if ($templateTableName) $this->templateTableName = $templateTableName;

        $this->createTemplatesTableIfNotExist();
        $this->createLogTableIfNotExist();
    }

    /**
     * Save log
     *
     * @param \Event $event
     * @return bool
     * @throws \Exception
     */
    public function save(\Event $event): bool
    {
        $logTemplate = $this->getLogTemplate($event->getType());

        // If there is no log template, log and return error
        if (!$logTemplate) {
            logError("Unknown log template!");
        }

        foreach ($event->getData() as $key => $value) {

            // If event data attributes don't match defined template structure, throw error
            if (!in_array($key, json_decode($logTemplate['structure']))) {
                logError("Template parameters names must match defined!");
            }
        }

        $sql = /** @lang text */
            "INSERT INTO $this->logTableName (template_id, data, created) 
        VALUES (:templateId, :data, :created)";

        $stmt = $this->conn->prepare($sql);

        try {
            return $stmt->execute([
                'templateId' => $logTemplate['id'],
                'data' => json_encode($event->getData()),
                'created' => date('Y-m-d H:i:s'),
            ]);
        }
        catch (\PDOException $e) {
            logError($e->getMessage());
            return false;
        }
    }

    /**
     * Get one or more logs
     *
     * @param string $templateName
     * @param array $criteria
     * @return mixed
     * @throws \Exception
     */
    public function get(string $templateName, array $criteria = [])
    {
        // Throw error if there is no log type(template name) stated.
        if (empty($templateName)) logError("Template name must be stated!");

        // Select all logs who belongs to stated log type
        $sql = /** @lang text */
            "SELECT $this->logTableName.data, $this->logTableName.created, $this->templateTableName.name
            FROM $this->logTableName 
            LEFT JOIN $this->templateTableName ON 
            $this->logTableName.template_id = $this->templateTableName.id
            WHERE $this->templateTableName.name = :name";

            if ($criteria) {

                // Concat query string
                foreach ($criteria as $key => $value) {

                    // If there is "<" or ">" in criteria, it should be passed with ":">
                    if ($explodedKey = explode(':', $key)) {

                        $sql .= " AND $this->logTableName.$explodedKey[0] $explodedKey[1] :$explodedKey[0]";

                        $criteria[$explodedKey[0]] = $value;
                        unset($criteria[$key]);
                    }
                    else {
                        $sql .= " AND $key =: $key";
                    }
                }
            }

        $stmt = $this->conn->prepare($sql);

        $stmt->execute(array_merge(['name' => $templateName], $criteria));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @return int
     */
    protected function createTemplatesTableIfNotExist()
    {
        try {

            return $this->conn->exec(
            /** @lang text */
                "CREATE TABLE IF NOT EXISTS $this->templateTableName(
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            structure TEXT NOT NULL);"
            );
        }
        catch (\PDOException $e) {

            logError($e->getMessage());

            return false;
        }
    }

    /**
     * @return int
     */
    protected function createLogTableIfNotExist()
    {
        try {

            return $this->conn->exec(
                /** @lang text */
                    "CREATE TABLE IF NOT EXISTS $this->logTableName(
                id INTEGER AUTO_INCREMENT PRIMARY KEY,
                template_id INTEGER,
                data TEXT,
                created DATETIME NULL,
                FOREIGN KEY (template_id) REFERENCES templates(id));
            ");
        }
        catch (\PDOException $e) {

            logError($e->getMessage());

            return false;
        }
    }

    /**
     * Define new log template structure
     *
     * @param string $name
     * @param array $structure
     * @return bool
     * @throws \Exception
     */
    public function defineLogTemplate(string $name, array $structure): bool
    {
        // Throw error if there is log template with existing name
        if ($this->getLogTemplate($name)) {
            logError("Log template with name: '$name' already exist");
        }

        $sql = /** @lang text */
            "INSERT INTO $this->templateTableName (name, structure) 
        VALUES (:name, :structure)";

        $stmt = $this->conn->prepare($sql);

        try {
            return $stmt->execute(['name' => $name, 'structure' => json_encode($structure)]);
        }
        catch (\PDOException $e) {
            logError($e->getMessage());
            return false;
        }
    }

    /**
     * Get log template by name
     *
     * @param $name string
     * @return mixed
     */
    public function getLogTemplate(string $name)
    {
        $sql = /** @lang text */
            "SELECT * FROM $this->templateTableName WHERE name=:name";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

}