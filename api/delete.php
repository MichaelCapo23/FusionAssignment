<?php
// If a client is deleted, all sections for that client and all links for that section also must be deleted. (or moved/saved into a different table for future data manipulation)
if($_POST['clientID']) {
    $clientID = $_POST['clientID'];
    $deleteAllClientData = $conn->prepare("DELETE C,S,L FROM `client` AS C
                                                 JOIN `sections` AS S ON C.ID = S.client_id
                                                 JOIN `links` AS L ON L.section_id = S.ID
                                                 WHERE C.ID = ?");
    $deleteAllClientData->bind_param("i", $clientID);
    $deleteAllClientData->execute();
    $deleteAllClientData->store_result();
}

//If a section is deleted, then all the links that belong to that section need to be deleted too.
if($_POST['sectionID']) {
    $sectionID = $_POST['sectionID'];
    $deleteSectionAndSectionLinks = $conn->prepare("DELETE S,L FROM `sections` AS S
                                                          JOIN `links` AS L ON L.section_id = S.ID
                                                          WHERE S.ID = ?");
    $deleteSectionAndSectionLinks->bind_param("i", $sectionID);
    $deleteSectionAndSectionLinks->execute();
    $deleteSectionAndSectionLinks->store_result();
}

//If a link is deleted, No other data relies on the link table's data so nothing else needs to be deleted.
if($_POST['linkID']) {
    $linkID = $_POST['linkID'];
    $deleteLink = $conn->prepare("DELETE FROM `links`
                                        WHERE `ID`=?");
    $deleteLink->bind_param("i", $linkID);
    $deleteLink->execute();
    $deleteLink->store_result();
}
?>