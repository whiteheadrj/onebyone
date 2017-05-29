<?php

include 'models/TeacherManager.php';

if (isset($_REQUEST['t'])) {
    $mngr = new TeacherManager();
    $mngr->replaceTeacherList($_REQUEST['t']);
    $mngr->save();
}

$redirect = 'index.php';
header('Location: '.$redirect);
die();
