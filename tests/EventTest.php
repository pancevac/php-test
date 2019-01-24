<?php

use storage\DatabaseStorage;
use storage\FileStorage;

/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/23/2019
 * Time: 12:50 AM
 */



class EventTest extends \PHPUnit\Framework\TestCase
{
    protected $logger;
    protected $databaseStorage;
    protected $fileStorage;

    public function setUp()
    {
        parent::setUp();

        $this->databaseStorage = new DatabaseStorage('localhost', 'event_logger', 'logs', 'root');
        $this->fileStorage = new FileStorage();

        $this->logger = new Logger();
    }

    protected function setStorage($type)
    {
        switch ($type) {
            case 'database' :
                $this->logger->setStorage($this->databaseStorage);
                break;
            case 'file' :
                $this->logger->setStorage($this->fileStorage);
                break;
        }
    }

    /** @test */
    function is_log_method_for_database_works() {

        $this->setStorage('database');

        $eventSet = [
            'type' => 'Event',
            'name' => 'Log',
            'performer' => 'user',
            'subject' => 'Test',
            'additional' => ['test' => 'test'],
        ];

        $event = new Event();
        $event->set($eventSet);

        $result = $this->logger->log($event);

        $this->assertTrue($result);
    }

    /** @test */
    public function is_log_method_for_file_storage_works()
    {
        $this->setStorage('file');

        $eventSet = [
            'type' => 'Event',
            'name' => 'Log',
            'performer' => 'user',
            'subject' => 'Test',
            'additional' => ['test' => 'test'],
        ];

        $event = new Event();
        $event->set($eventSet);

        $result = $this->logger->log($event);

        $this->assertTrue($result);
    }

    /** @test */
    function is_log_from_database_fetchable()
    {
        $this->setStorage('database');

        $criteria = [
            'id' => 1,
            'name' => 'Log',
        ];

        $result = $this->logger->getLog($criteria);

        $this->assertTrue(! empty($result) && is_array($result));
    }

    /** @test */
    function is_log_from_file_storage_fetchable()
    {
        $this->setStorage('file');

        $criteria = [
            'name' => 'Log',
        ];

        $result = $this->logger->getLog($criteria);

        $this->assertTrue(! empty($result) && is_array($result));
    }
}