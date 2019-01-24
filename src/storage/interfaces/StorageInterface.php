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
     * Save event
     *
     * @param array $data
     * @return bool
     */
    public function save(array $data): bool;

    /**
     * Get one or more events
     *
     * @param array $criteria
     * @return mixed
     */
    public function get(array $criteria = []);
}