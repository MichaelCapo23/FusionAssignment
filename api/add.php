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
//  $checkForClient->bind_result() bind the result of query to a variable, don't need to bind it because this query was a simple check.
    if ($checkForClient->num_rows === 0) { // insert new client if no client already exists with that ID.
        $insertNewClient = $conn->prepare("INSERT INTO `clients`
                                                (`ID`, `name`)
                                                VALUES (?,?)");
        $insertNewClient->bind_param("is", $clientID, $ClientName);
        $insertNewClient->execute();
        $insertNewClient->store_result();
    } else { //if a client with that ID already exists throw an error alerting user that client already exists.
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
    //$checkForClient->bind_result() bind the result of query to a variable, but I don't need to bind it because this query was just a simple check.
    if($checkForClient->num_rows !== 0) { //conditional checking if there is a client with that ID, because there needs to be a client before there can be a section.
        $checkForSection = $conn->prepare("SELECT ID
                                             FROM `sections`
                                             WHERE ID = ?");
        $checkForSection->bind_param("i", $sectionID);
        $checkForSection->execute();
        $checkForSection->store_result();
        if($checkForSection->num_rows === 0) { //checking if a section with that ID already exists. Client to section is a one to many so we want to insert the section unless a section with that ID already exists.
            $insertNewSection = $conn->prepare("INSERT INTO `sections` 
                                                (`ID`, `client_id`, `name`)
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
    if($checkForSection->num_rows !== 0) { //conditional checking if there is a section with that ID, because there needs to be a section before there can be a section.
        $checkForLink = $conn->prepare("SELECT ID
                                              FROM `links`
                                              WHERE ID = ?");
        $checkForLink->bind_param("i", $linkID);
        $checkForLink->execute();
        $checkForLink->store_result();
        if($checkForLink->num_rows === 0) { //checking if a Link with that ID already exists. section to Link is a one to many so we want to insert the link unless a link with that ID already exists.
            $insertNewLink = $conn->prepare("INSERT INTO `links` 
                                            (`ID`, `section_id`, `name`)
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