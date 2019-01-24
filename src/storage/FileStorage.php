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
        empty($logFilePath) ?
            $this->logFIlePath = dirname(__DIR__, 2) . '\log\log.txt' :
            $this->logFIlePath = $logFilePath;
    }

    /**
     * Save event
     *
     * @param array $data
     * @return bool
     */
    public function save(array $data): bool
    {
        $forInput = [];

        $existedLogs = $this->get();

        if (empty($existedLogs)) {
            $forInput[] = $data;
        } else {
            $forInput = $existedLogs;
            $forInput[] = $data;
        }

        return file_put_contents($this->logFIlePath, serialize($forInput));
    }

    /**
     * Get one or more events
     *
     * @param array $criteria
     * @return mixed
     */
    public function get(array $criteria = [])
    {
        $logsArrays = unserialize(file_get_contents($this->logFIlePath));

        // If there is no criteria, return all logs arrays
        if (empty($criteria)) return $logsArrays;

        // Otherwise, filter log array that match criteria
        $criteriaKey = key($criteria);              // get criteria array key
        $criteriaValue = $criteria[$criteriaKey];   // get criteria array value

        foreach ($logsArrays as $log) {

            // If log array value for criteria key match criteria value,
            // return log array
            if ($log[$criteriaKey] == $criteriaValue) {

                return $log;
            }
        }
    }

}