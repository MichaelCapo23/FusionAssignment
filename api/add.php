<?php

require_once('connect.php');
$output = [];

if ($_POST['client']) {
    $ClientName = $_POST['ClientName'];
    $clientID = $_POST['clientID'];
    $checkForClient = $conn->prepare("SELECT ID 
                                            FROM `clients` 
                                            WHERE ID  = ?");
    $checkForClient->bind_param("i", $clientID);
    $checkForClient->execute();
    $checkForClient->store_result();
//    $checkForClient->bind_result() bind the result of query to this variable, don't need to bind it because this query was a simple check.
    if ($checkForClient->num_rows === 0) {
        $insertNewClient = $conn->prepare("INSERT INTO `clients`
                                                (`ID`, `name`)
                                                VALUES (?,?)");
        $insertNewClient->bind_param("is", $clientID, $ClientName);
    } else {
        $output['ClientError'][] = 'Client Already Exists';
        print(json_encode($output));
    }
}

if ($_POST['sectionName']) {
    $clientID = $_POST['clientID'];
    $sectionName = $_POST['sectionName'];
    $sectionID = $_POST['sectionID'];
    $checkForClient = $conn->prepare("SELECT ID 
                                            FROM `clients` 
                                            WHERE ID  = ?");
    $checkForClient->bind_param("i", $clientID);
    //    $checkForSection->bind_result() bind the result of query to this variable, but I don't need to bind it because this query was a simple check.
    if($checkForClient->num_rows !== 0) {
        $checkForSection = $conn->prepare("SELECT ID
                                             FROM `sections`
                                             WHERE ID = ?");
        $checkForSection->bind_param("i", $sectionID);
        $checkForSection->execute();
        $checkForSection->store_result();
    } else {
        $output['Error'][] = 'Section Already Exists';
    }
}

?>