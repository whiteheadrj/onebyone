<?php

include 'models/NotTeachingManager.php';

$mngr = new NotTeachingManager();
if (isset($_REQUEST['nt'])) {
    $mngr->replaceListIDs($_REQUEST['nt']);
    $reasons = array();
    foreach ($_REQUEST['nt'] as $id) {
        if (array_key_exists($id, $_REQUEST['ntReasons'])) {
            $reasons[$id] = $_REQUEST['ntReasons'][$id];
        }
    }
    $mngr->replaceListReasons($reasons);
    $mngr->save();
} else {
    $mngr->replaceListIDs(array());
    $mngr->replaceListReasons(array());
    $mngr->save();
}

$redirect = './index.php';
header('Location: '.$redirect);
die();
