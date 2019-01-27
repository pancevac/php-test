<?php
/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/22/2019
 * Time: 1:09 AM
 */

namespace storage\interfaces;

interface StorageInterface
{
    /**
     * Save log
     *
     * @param \Event $event
     * @return bool
     */
    public function save(\Event $event): bool;

    /**
     * Get one or more logs
     *
     * @param string $templateName
     * @param array $criteria
     * @return mixed
     */
    public function get(string $templateName, array $criteria = []);

    /**
     * Define new log template structure
     *
     * @param string $name
     * @param array $structure
     * @return bool
     */
    public function defineLogTemplate(string $name, array $structure): bool;

    /**
     * Get log template by name
     *
     * @param $name string
     * @return mixed
     */
    public function getLogTemplate(string $name);
}