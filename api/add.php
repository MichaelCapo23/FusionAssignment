<?php

require_once('connect.php');
$output = [];

if ($_POST['ClientName']) {
    $ClientName = $_POST['ClientName'];
    $clientID = $_POST['clientID'];
    $checkForClient = $conn->prepare("SELECT ID 
                                            FROM `clients` 
                                            WHERE ID  = ?");
    $checkForClient->bind_param("i", $clientID);
    $checkForClient->execute();
    $checkForClient->store_result();
//    $checkForClient->bind_result() bind the result of query to a variable, don't need to bind it because this query was a simple check.
    if ($checkForClient->num_rows === 0) {
        $insertNewClient = $conn->prepare("INSERT INTO `clients`
                                                (`ID`, `name`)
                                                VALUES (?,?)");
        $insertNewClient->bind_param("is", $clientID, $ClientName);
        $insertNewClient->execute();
        $insertNewClient->store_result();
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
    $checkForClient->execute();
    $checkForClient->store_result();
    //    $checkForClient->bind_result() bind the result of query to a variable, but I don't need to bind it because this query was just a simple check.
    if($checkForClient->num_rows !== 0) {
        $checkForSection = $conn->prepare("SELECT ID
                                             FROM `sections`
                                             WHERE ID = ?");
        $checkForSection->bind_param("i", $sectionID);
        $checkForSection->execute();
        $checkForSection->store_result();
        if($checkForSection->num_rows === 0) {
            $insertNewSection = $conn->prepare("INSERT INTO `sections` 
                                                (`id`, `client_id`, `name`)
                                                VALUES (?,?,?)");
            $insertNewSection->bind_param("iis", $sectionID, $clientID, $sectionName);
            $insertNewSection->execute();
            $insertNewSection->store_result();
        } else {
            $output['error'][] = 'Section Already Exists';
            print(json_encode($output));
        }
    } else {
        $output['Error'][] = 'No Corresponding Client';
        print(json_encode($output));
    }
}

if($_POST['linkName']) {
    $sectionID = $_POST['sectionID'];
    $linkName = $_POST['linkName'];
    $linkID = $_POST['linkID'];
    $checkForSection = $conn->prepare("SELECT ID
                                             FROM `sections`
                                             WHERE ID = ?");
    $checkForSection->bind_param("i", $sectionID);
    $checkForSection->execute();
    $checkForSection->store_result();
    //    $checkForSection->bind_result() bind the result of query to a variable, but I don't need to bind it because this query was just a simple check.
    if($checkForSection->num_rows !== 0) {
        $checkForLink = $conn->prepare("SELECT id
                                              FROM `links`
                                              WHERE id = ?");
        $checkForLink->bind_param("i", $linkID);
        $checkForLink->execute();
        $checkForLink->store_result();
        if($checkForLink->num_rows === 0) {
            $insertNewLink = $conn->prepare("INSERT INTO `links` 
                                            (`id`, `section_id`, `name`)
                                            VALUES (?,?,?)");
            $insertNewLink->bind_param("iis", $linkID, $sectionID, $linkName);
            $insertNewLink->execute();
            $insertNewLink->store_result();
        } else {
            $output['error'][] = 'Link Already Exists';
            print(json_encode($output));
        }
    } else {
        $output['error'][] = 'No Corresponding Section';
        print(json_encode($output));
    }
}



?>