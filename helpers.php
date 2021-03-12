<?php
require_once(__DIR__ . '/vendor/autoload.php');


/**
 * Initialize and wrap into an exception handler the provided channel
 * When there will be no more messages to consume the consumer will wait for as long as the $timeout variable is set and then he will automatically stop
 */
function initialize_channel($channel)
{
    try {
        while (count($channel->callbacks)) {
            print " [*] Waiting for more messages..." . PHP_EOL;
            //Variable $timeout counts in seconds so now he will wait 3 seconds before closing
            $channel->wait($allowed_methods = null, $nonBlocking = true, $timeout = 3);
        }
    } catch (Exception $e) {
        print " [*] There are no more tasks in the queue." . PHP_EOL;
        sleep(1);
        print " [*] The consumer will automatically close.";
        sleep(1);
    }
}




/**
 * Get the routing key from the message payload
 */
function get_routing_key($message)
{
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    return $message->get('routing_key');
}

/**
 * Parse the routing identifiers into an array
 */
function get_routing_ids_in_array($routing): array
{
    $Ids = explode(".",$routing);
    for($i=1;$i < count($Ids); $i++)
    {
        $Ids[$i] = intval($Ids[$i]);
    }
    return $Ids;
}

/**
 * Convert hexadecimal to decimal
 */
function hex_to_dec($hex): int|string
{
    $dec = 0;
    $len = strlen($hex);
    for ($i = 1; $i <= $len; $i++) {
        $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
    }
    return $dec;
}

/**
 * Format routing key x.x.x.x
 */
function set_formated_routing_key($result): string
{
    return print_r(hex_to_dec($result->gatewayEui).".".hex_to_dec($result->profileId).".".hex_to_dec($result->endpointId).".".hex_to_dec($result->clusterId).".".hex_to_dec($result->attributeId) , true);

}

/**
 * Format to UTC date
 */
function unix_to_utc_formatted($result): string
{
    date_default_timezone_set('UTC');
    return print_r(date("Y-m-d H:i:s", substr($result->timestamp, 0, 10)),true);
}

/**
 * Get the api results from url and return decoded message body
 */
function get_api_results($URL)
{
    $client = curl_init($URL);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($client);
    $decoded_message = json_decode($response);
    return($decoded_message);
}

// Went to messageQueueService
// function get_amqp_connection(): AMQPStreamConnection
// {
//     $config = new config();
//     return new AMQPStreamConnection($config->get_host(), $config->get_port(), $config->get_user(), $config->get_password());
// }


// Went to dbService
// function connect_to_db($messageIds, $messageBody, $routing)
// {

//     $conn = new mysqli('localhost', 'root', '', 'cand_e2ro');
//     echo " [x] Trying to connect to the database".PHP_EOL;
//     if ($conn === FALSE) {
//         die(" [-] ERROR: Could not connect.");
//     }
//     echo " [x] Connected successfully", "\n";
//     $sql = "INSERT INTO queue_messages(gatewayEui, profileId, endpointId, clusterId, attributeId, value, date, routing_key)
//     VALUES('$messageIds[0]','$messageIds[1]', '$messageIds[2]','$messageIds[3]','$messageIds[4]', $messageBody->Value, '$messageBody->Date', '$routing')";


//     if (mysqli_query($conn, $sql)) {
//         echo " [x] New record created successfully" . PHP_EOL;
//     } else {
//         echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//     }

//     echo " [x] Done", "\n";
//     $conn->close();
// }








