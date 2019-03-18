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
    $checkForExistingClientWithNewID = $conn->prepare("SELECT id
                                            FROM `clients`
                                            WHERE id  = ?");
    $checkForExistingClientWithNewID->bind_param("i", $newClientID);
    $checkForExistingClientWithNewID->execute();
    $checkForExistingClientWithNewID->store_result();
    if ($checkForExistingClientWithNewID->num_rows === 0) {//Conditional to make sure no client already exists with the new ID to update with.
        $checkForClientToUpdate = $conn->prepare("SELECT id
                                            FROM `clients`
                                            WHERE id  = ?");
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
                                           SET S.ID = ?, S.name = ?
                                           WHERE s.client_id = ?");
            $updateSection->bind_param("isi", $newClientID, $newSectionName, $clientID);
            $updateSection->execute();
            $updateSection->store_result();

            $updateLinks = $conn->prepare("UPDATE `links` AS L
                                         SET L.section_id = ?, L.name = ?
                                         WHERE L.section_id = ?");
            $updateLinks->bind_param("isi",$newClientID, $newLinkName, $clientID );
            $updateLinks->execute();
            $updateLinks->store_result();

        } else {
            $output['Error'][] = 'No Client To Update';
            print(json_encode($output));
        }
    } else {
        $output['Error'][] = 'Unable To Update Client With The New ID';
        print(json_encode($output));
    }
}


?>