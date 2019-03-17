<?php

if($_POST['clientName']) {
    $client = $_POST['clientName'];
    $clientID = $_POST['clientID'];
    $deleteClient = $conn->prepare("DELETE FROM `clients`
                                          WHERE ID = ?");
    $deleteClient->bind_param("i", $clientID);
    $deleteClient->execute();
    $deleteClient->store_result();
}

if($_POST['sectionName']) {
    $clientID = $_POST['clientID'];
    $sectionName = $_POST['sectionName'];
    $sectionID = $_POST['sectionID'];
    $deleteSection = $conn->prepare("DELETE FROM `sections`
                                           WHERE id=?");
    $deleteSection->bind_param("i", $sectionID);
    $deleteSection->execute();
    $deleteSection->store_result();
}

if($_POST['linkName']) {
    $sectionID = $_POST['sectionID'];
    $linkName = $_POST['linkName'];
    $linkID = $_POST['linkID'];
    $deleteLink = $conn->prepare("DELETE FROM `links`
                                        WHERE `id`=?");
    $deleteLink->bind_param("i", $linkID);
    $deleteLink->execute();
    $deleteLink->store_result();
}

?>