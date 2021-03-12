<?php
require_once(__DIR__ . '/vendor/autoload.php');

include 'helpers.php';
include 'messageQueueService.php';
include 'dbService.php';
$dbService = new dbService();
$dbService ->create_db();

$config = new config();
$messageQueueService = new messageQueueService();

/**
 * Set up connection
 */
$connection =$messageQueueService -> get_amqp_connection();

/**
 * Set up channel
 */
$channel = $messageQueueService -> set_up_channel($config, $connection);

/**
 * Bind the queue and the exchange together.
*/
$channel->queue_bind($config->get_queue(), $config->get_exchange());

echo ' [*] The process will start soon. To exit press CTRL+C', "\n";
sleep(1);

/**
 * Set up the callback fuction to be consumed
 * Implementation: Decode the message and insert it into the database
 */
$callback = function($message)
{
    $dbService = new dbService();

    echo " [x] Received ", $message->body, "\n";

    // Decode the consumed message body
    $messageBody = json_decode($message->body);

    // Set up the routing key
    $routing = get_routing_key($message);

    // Insert messages in DB

    $dbService ->insert_to_database(get_routing_ids_in_array($routing),$messageBody,$routing);
};

/**
 * Initialize consuming of the callback implementation in the queue
 */
$channel->basic_consume($config->get_queue(), $consumerTag= '', false, false, false, false, $callback);

/**
 * Initialized the channel
 */
initialize_channel($channel);


$channel->close();
$connection->close();
