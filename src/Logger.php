<?php

use storage\interfaces\StorageInterface;

/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/22/2019
 * Time: 1:57 AM
 */

class Logger
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Logger constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Change current logger storage
     *
     * @param StorageInterface $storage
     */
    public function changeStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get logger storage
     *
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * Create log in storage
     *
     * @param Event $event
     * @return bool
     */
    public function log(Event $event): bool
    {
        return $this->storage->save($event->toArray());
    }

    /**
     * Retrieve log from storage
     *
     * @param array $criteria
     * @return mixed
     */
    public function getLog($criteria = [])
    {
        return $this->storage->get($criteria);
    }
}