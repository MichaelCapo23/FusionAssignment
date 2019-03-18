<?php
$output = [];
//code to update Client, assuming the user sends new client name and id to update with.
if($_POST['clientName']){
    $newClientName = $_POST['newClientName']; // new client name
    $clientID = $_POST['clientID']; //old client ID
    $newClientID = $_POST['newClientID']; //new client ID
    $checkForExistingClientWithNewID = $conn->prepare("SELECT id
                                            FROM `clients`
                                            WHERE id  = ?");
    $checkForExistingClientWithNewID->bind_param("i", $newClientID);
    $checkForExistingClientWithNewID->execute();
    $checkForExistingClientWithNewID->store_result();

    if($checkForExistingClientWithNewID->num_rows === 0) {//Conditional to make sure no client already exists with the new ID to update with.

        $checkForClientToUpdate = $conn->prepare("SELECT id
                                            FROM `clients`
                                            WHERE id  = ?");
        $checkForClientToUpdate->bind_param("i", $clientID);
        $checkForClientToUpdate->execute();
        $checkForClientToUpdate->store_result();

        if($checkForClientToUpdate->num_rows !== 0) {//conditional checking if there is a client with the old ID to update.
            $updateClient = $conn->prepare("UPDATE `clients`
                                              SET `clients`.name = ?, `clients`.ID = ?
                                              WHERE `clients`.ID = ?");
            $updateClient->bind_param("sii", $newClientName, $newClientID, $clientID);
            $updateClient->execute();
            $updateClient->store_result();
        } else {
            $output['Error'][] = 'No Client To Update';
            print(json_encode($output));
        }
    } else {
        $output['Error'][] = 'Unable To Update Client With The New ID';
        print(json_encode($output));
    }
}

if($_POST['section_name']) {
    $newClientName = $_POST['newClientName']; // new section ID
    $newSectionName = $_POST['newSectionName']; // new section name
    $sectionID = $_POST['sectionID']; //old section ID
    $newSectionID = $_POST['newSectionID'];//new section ID
    $checkForClient = $conn->prepare("SELECT id
                                            FROM `clients`
                                            WHERE id  = ?");
    $checkForClient->bind_param("i", $newClientID);
    $checkForClient->execute();
    $checkForClient->store_result();
    if($checkForClient->num_rows !== 0) { //Conditional checking if there is an existing Client with thee new ID for this section to be updated.
        $checkForSection = $conn->prepare("SELECT id
                                                 FROM `sections`
                                                 WHERE id=?");
        $checkForSection->bind_param("i", $newSectionID);
    }


}
?>



