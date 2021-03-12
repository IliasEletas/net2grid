<?php
class dbService
{
    const hostname = 'candidaterds.n2g-dev.net';
    const username = 'cand_e2ro';
    const password = 'awTAS6m1hjJzVRg4';
    const database = 'cand_e2ro';
//    const hostname = 'localhost';
//    const username = 'root';
//    const password = '';
//    const database = 'cand_e2ro';

    /**
     * Open a connectin to the database
     * Insert the error message in the database
     */
    function insert_to_database($messageIds, $messageBody, $routing)
    {
        $conn = new mysqli(self::hostname, self::username, self::password, self::database);
        //$conn = new mysqli(self::hostname, self::username, self::password, self::database);
        echo " [x] Trying to connect to the database".PHP_EOL;
        if ($conn === FALSE) {
            die(" [-] ERROR: Could not connect.");
        }
        echo " [x] Connected successfully", "\n";
        $sql = "INSERT INTO queue_messages(gatewayEui, profileId, endpointId, clusterId, attributeId, value, date, routing_key)
        VALUES('$messageIds[0]','$messageIds[1]', '$messageIds[2]','$messageIds[3]','$messageIds[4]', $messageBody->Value, '$messageBody->Date', '$routing')";
    
    
        if (mysqli_query($conn, $sql)) {
            echo " [x] New record created successfully" . PHP_EOL;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    
        echo " [x] Done", "\n";
        $conn->query($sql);


        $conn->close();
    }

    /**
     * Create a database
     */

    function create_db()
    {
        $conn = new mysqli(self::hostname, self::username, self::password, self::database);
        echo " [x] Trying to connect to the database".PHP_EOL;
        if ($conn === FALSE) {
            die(" [-] ERROR: Could not connect.");
        }
        echo " [x] Connected successfully", "\n".PHP_EOL;

        $sql = "CREATE TABLE queue_messages(filtered_messages_Id int NOT NULL AUTO_INCREMENT,
                gatewayEui varchar(20) NOT NULL,
                profileId int(10) NOT NULL,
                endpointId int(10) NOT NULL,
                clusterId int(10) NOT NULL,
                attributeId int(10) NOT NULL,
                value bigint(30) NOT NULL,
                date datetime NOT NULL,
                routing_key varchar(100) NOT NULL,
                PRIMARY KEY (filtered_messages_Id))";
        echo " [x] Table created successfully".PHP_EOL;
        $conn->query($sql);

    }


    /**
     * Select all from database
     */
    function select_all_from_db()
    {
        $conn = new mysqli(self::hostname, self::username, self::password, self::database);
        echo " [x] Trying to connect to the database".PHP_EOL;
        if ($conn === FALSE) {
            die(" [-] ERROR: Could not connect.");
        }
        echo " [x] Connected successfully", "\n";

        $sql = "SELECT * FROM queue_messages";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["gatewayEui"] .PHP_EOL ;
            }
        } else {
            echo "0 results";
        }


        if (mysqli_query($conn, $sql)) {
            echo " [x] New record created successfully" . PHP_EOL;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        echo " [x] Done", "\n";
        $conn->close();

    }




}

