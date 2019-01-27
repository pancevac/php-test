<?php
/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/23/2019
 * Time: 1:53 AM
 */

namespace storage;


use storage\interfaces\StorageInterface;

class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $logFIlePath;

    /**
     * FileStorage constructor.
     * @param null $logFilePath
     */
    public function __construct($logFilePath = null)
    {

    }

    /**
     * Save event
     *
     * @param \Event $event
     * @return bool
     */
    public function save(\Event $event): bool
    {
        // TODO: Implement save() method.
    }

    /**
     * Get one or more events
     *
     * @param string $templateName
     * @param array $criteria
     * @return mixed
     */
    public function get(string $templateName, array $criteria = [])
    {
        // TODO: Implement get() method.
    }

    /**
     * Define new log template structure
     *
     * @param string $name
     * @param array $structure
     * @return mixed
     */
    public function defineLogTemplate(string $name, array $structure): bool
    {
        // TODO: Implement defineLogTemplate() method.
    }

    /**
     * Get log template by name
     *
     * @param $name string
     * @return mixed
     */
    public function getLogTemplate(string $name)
    {
        // TODO: Implement getLogTemplate() method.
    }
}