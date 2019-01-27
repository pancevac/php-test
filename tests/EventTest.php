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

        $this->databaseStorage = new DatabaseStorage('localhost', 'event_logger', 'root', '', 'logs', 'templates');
        $this->fileStorage = new FileStorage();
    }

    protected function setStorage($type)
    {
        switch ($type) {
            case 'database' :
                $this->logger = new Logger($this->databaseStorage);
                break;
            case 'file' :
                $this->logger = new Logger($this->fileStorage);
                break;
        }
    }

    /** @test */
    function does_defining_log_structure_work()
    {
        $this->setStorage('database');

        $result = $this->databaseStorage->defineLogTemplate('warning', [
            'name',
            'performer',
            'subject',
            'addition',
        ]);

        $this->assertTrue($result);
    }

    /** @test */
    function does_log_method_for_database_work() {

        $this->setStorage('database');

        $eventSet = [
            'name' => 'Log',
            'performer' => 'user',
            'subject' => 'Generic',
            'addition' => ['Generic' => 'Generic'],
        ];

        $event = new Event();
        $event->set('warning', $eventSet);

        $result = $this->logger->log($event);

        $this->assertTrue($result);
    }

    /** @test */
    function is_log_from_database_fetchable()
    {
        $this->setStorage('database');

        $criteria = [
            'created:<=' => date('Y-m-d H:i:s'),
        ];

        $result = $this->logger->getLog('warning', $criteria);

        $this->assertTrue(! empty($result) && is_array($result));
    }

}