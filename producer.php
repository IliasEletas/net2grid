<?php
require_once(__DIR__ . '/vendor/autoload.php');
include 'helpers.php';
include 'messageQueueService.php';



$config = new config();
$messageQueueService = new messageQueueService();

/**
 * Set up connection
 */
$connection = $messageQueueService -> get_amqp_connection();

/**
 * Set up channel
 */
$channel = $messageQueueService -> set_up_channel($config, $connection);


$limit = 0;//The variable limit is used so that we can control how many messages we want to publish
$id = 1;
echo " [*] The message will now start being published".PHP_EOL;
sleep(1);
while ($limit < 10 )
{
    // Connect to the API and get the messages payload
    $result = get_api_results($config->get_apiUrl());

    // Set up the routing key from response
    $routing_key = set_formated_routing_key($result);

    // Get the formatted datetime from response
    $datetime = unix_to_utc_formatted($result);

    // set up the message payload
    $result_message = array("Date" => $datetime, "Value" => $result->value);

    // set the AMQP message
    $message = $messageQueueService->set_new_amqp_message($result_message);

    // bind the queue and the exchange with a routing key.
    $channel->queue_bind($config->get_queue(), $config->get_exchange(), $routing_key);

    // publish the message
    $channel->basic_publish($message, $config->get_exchange(), $routing_key);
    
    print ' [x] '.$id.' messages are published.' . PHP_EOL;
    $id++;
    $limit++;
}

print ' [*] All messages are published to the queue' . PHP_EOL;

$channel->close();
$connection->close();