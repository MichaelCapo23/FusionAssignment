<?php
$output = [];
//This section of code that updates a row in the client table and all the data that corresponded to that client.
//first checks to see if a row is using the new ID given by the user.
//Second it checks if the row we want to update exists.
//if row exists in the table, it updates the row to the new data given.
if ($_POST['newClientID']) {
    $newClientName = $_POST['newClientName']; // new client name
    $newSectionName = $_POST['newSectionName']; //new section name
    $newLinkName = $_POST['$newLinkName']; // new link name
    $clientID = $_POST['clientID']; //old client ID
    $newClientID = $_POST['newClientID']; //new client ID
    $checkForExistingClientWithNewID = $conn->prepare("SELECT ID
                                            FROM `clients`
                                            WHERE ID  = ?");
    $checkForExistingClientWithNewID->bind_param("i", $newClientID);
    $checkForExistingClientWithNewID->execute();
    $checkForExistingClientWithNewID->store_result();
    if ($checkForExistingClientWithNewID->num_rows === 0) {//Conditional to make sure no client already exists with the new ID to update with.
        $checkForClientToUpdate = $conn->prepare("SELECT ID
                                            FROM `clients`
                                            WHERE ID  = ?");
        $checkForClientToUpdate->bind_param("i", $clientID);
        $checkForClientToUpdate->execute();
        $checkForClientToUpdate->store_result();
        if ($checkForClientToUpdate->num_rows !== 0) {//conditional checking if there is a client with the old ID to update.

            $updateClient = $conn->prepare("UPDATE `clients` AS C
                                          SET C.ID = ?, C.name = ?
                                          WHERE C.ID = ?");
            $updateClient->bind_param("isi", $newClientID, $newClientName, $clientID);
            $updateClient->execute();
            $updateClient->store_result();

            $updateSection = $conn->prepare("UPDATE `sections` AS S
                                           SET S.ID = ?, S.name = ?, S.client_id = ?
                                           WHERE s.client_id = ?");
            $updateSection->bind_param("isii", $newClientID, $newSectionName, $newClientID , $clientID);
            $updateSection->execute();
            $updateSection->store_result();

            $updateLinks = $conn->prepare("UPDATE `links` AS L
                                         SET L.section_id = ?, L.name = ?
                                         WHERE L.section_id = ?");
            $updateLinks->bind_param("isi",$newClientID, $newLinkName, $clientID );
            $updateLinks->execute();
            $updateLinks->store_result();

            exit(); //exit because client and all sections is updated and don't want the other if statements to run when the job is already done.
        } else {
            $output['Error'][] = 'No Client To Update';
            print(json_encode($output));
        }
    } else {
        $output['Error'][] = 'Unable To Update Client With The New ID';
        print(json_encode($output));
    }
}

//Code to update section's ID and name and the links that share the section data. Not updating the client id because you'd have to update the client for that to change and this is just the sections and the links.
//First Check if the new section ID given already exists. If it does NOT exists check for section to update.
//Second check if the section we want to update exists. if it does exist update it.
if($_POST['newSectionID']) {
    $sectionID = $_POST['sectionID']; // Old section ID
    $newSectionID = $_POST['$newSectionID']; //new section ID
    $newSectionName = $_POST['newSectionName']; //new section name
    $linkID = $_POST['linkID']; //Old link ID
    $newLinkID = $_POST['$newLinkID']; //new link ID
    $newLinkName = $_POST['$newLinkName']; // new link name
    $checkForSectionWithNewID = $conn->prepare("SELECT ID
                                                 FROM `sections`
                                                 WHERE ID=?");
    $checkForSectionWithNewID->bind_param("i", $newSectionID);
    $checkForSectionWithNewID->execute();
    $checkForSectionWithNewID->store_result();
    if($checkForSectionWithNewID->num_rows === 0) {
        $checkForSection = $conn->prepare("SELECT ID
                                                 FROM `sections`
                                                 WHERE ID=?");
        $checkForSection->bind_param("i", $sectionID);
        $checkForSection->execute();
        $checkForSection->store_result();
        if($checkForSection->num_rows !== 0) {
            $updateSection = $conn->prepare("UPDATE `sections` AS S
                                                   SET S.name=?, S.ID=?
                                                   WHERE S.ID=?");
            $updateSection->bind_param("sii",$newSectionName, $newSectionID, $sectionID);
            $updateSection->execute();
            $updateSection->store_result();

            $updateLinks = $conn->prepare("UPDATE `links` AS L
                                                 SET L.name=?, L.section_id=?, L.ID=?
                                                 WHERE L.ID=?");
            $updateLinks->bind_param("siii", $newLinkName, $newSectionID, $newLinkID, $linkID);

            exit(); //exit because client and all sections is updated and don't want the other if statements to run when the job is already done.
        } else {
            $output['Error'][] = 'No Section To Update';
            print(json_encode($output));
        }
    } else {
        $output['Error'][] = 'Unable To Update Section With The New ID';
        print(json_encode($output));
    }
}

//Code to update only a link. Won't update the section_id because im not updating the section therefor that wouldn't change.
//First Check if a link with the new ID already exists. If not check for the old ID
//Second Check if a row with the old ID exists to update. If it does, update it.
if($_POST['newLinkID']) {
    $clientID = $_POST['$clientID']; //client ID
    $linkID = $_POST['linkID']; //Old link ID
    $newLinkID = $_POST['$newLinkID']; //new link ID
    $newLinkName = $_POST['$newLinkName']; // new link name
    $checkForLinkWithNewId = $conn->prepare("SELECT ID
                                                   FROM `links`
                                                   WHERE ID=?");
    $checkForLinkWithNewId->bind_param("i", $newLinkID);
    $checkForLinkWithNewId->execute();
    $checkForLinkWithNewId->store_result();
    if($checkForLinkWithNewId->num_rows === 0) {
        $checkForLink = $conn->prepare("SELECT ID
                                              FROM `links`
                                              WHERE ID=?");
        $checkForLink->bind_param("i", $linkID);
        $checkForLink->execute();
        $checkForLink->store_result();
        if($checkForLink->num_rows !== 0) {
            $updateLinks = $conn->prepare("UPDATE `links` AS L
                                         SET L.ID = ?, L.name = ?
                                         WHERE L.section_id = ?");
            $updateLinks->bind_param("isi",$newLinkID,$newLinkName, $clientID);
            $updateLinks->execute();
            $updateLinks->store_result();
        } else {
            $output['Error'][] = 'No Link To Update';
            print(json_encode($output));
        }
    } else {
        $output['Error'][] = 'Unable To Update Link With The New ID';
        print(json_encode($output));
    }
}


?>