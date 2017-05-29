<?php


include 'models/CompanionshipManager.php';

if (isset($_REQUEST['action'])) {
    $mngr = new CompanionshipManager();
    switch ($_REQUEST['action']) {
        case 'addComp':
            $mngr->addToCompanionship($_REQUEST['assignee'], $_REQUEST['comp']);
            break;
        case 'rmFromCompanionship':
            $mngr->removeFromCompanionship($_REQUEST['id']);
            break;
        default:
            break;
    }
    $mngr->save();
}

$redirect = './companionships.php';
header('Location: '.$redirect);
die();
