<?php


include 'models/AssignmentsManager.php';

if (isset($_REQUEST['action'])) {
    $mngr = new AssignmentsManager();
    switch ($_REQUEST['action']) {
        case 'assignComp':
            $mngr->addAssignment($_REQUEST['assignee'], $_REQUEST['comp']);
            break;
        case 'unassign':
            $mngr->removeByIndividualID($_REQUEST['id']);
            break;
        default:
            break;
    }
    $mngr->save();
}

$redirect = './individuals.php';
header('Location: '.$redirect);
die();
