<?php

if (!isset($_POST['auth'])) {
    $module->returnErrorResponse('Missing param - auth');
}

if (!$module->checkAuthToken($_POST['auth'])) {
    $module->returnErrorResponse('Could not authenticate');
}

if (!isset($_POST['filters'])) {
    $module->returnErrorResponse('Missing param - filters');
}

$module->getData($_POST['filters']);

?>