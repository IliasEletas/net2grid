<?php
require_once(__DIR__ . '/vendor/autoload.php');
include 'config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class messageQueueService
{
    /**
     * Get a new AMQP connection based on the configuration set up
     */
    function get_amqp_connection(): AMQPStreamConnection
    {
        $config = new config();
        return new AMQPStreamConnection($config->get_host(), $config->get_port(), $config->get_user(), $config->get_password());
    }

    function set_up_channel($config, $connection)
    {
        // Open the connection channel
        $channel = $connection->channel();

        // Declare the queue configuration settings
        $channel->queue_declare($config->get_queue(), false, true, false, false);

        // Declare the exchange configuration settings
        $channel->exchange_declare($config->get_exchange(), 'direct', false, true, false);

        return $channel;
    }

    /**
     * Bind channel with exchange

    function bind_channel_and_exchange($config, $channel)
    {
        $channel->queue_bind($config->get_queue(), $config->get_exchange());
        return $channel;
    }
     * /

    /**
     * Set up a new AMQP message
     */
    function set_new_amqp_message($result_message)
    {
        return new AMQPMessage(json_encode($result_message), array(
            'content_type' => 'application/json',
            'delivery_mode' => 2 #make message persistent.
        ));
    }

}

