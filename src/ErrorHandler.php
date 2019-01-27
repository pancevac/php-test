<?php
/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/27/2019
 * Time: 1:50 AM
 */

/**
 * Record error in log and display message.
 *
 * @param $message
 */
function logError($message) {

    // Define error and when it occurred
    $error = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL  . ' ';

    // Define log path
    $logFIlePath = dirname(__DIR__, 1) . '/log/error.log';

    // Record error in log file
    error_log($error, 3, $logFIlePath);

    // Display error message
    trigger_error($message);
}