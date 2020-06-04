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

if (!isset($_POST['fields'])) {
    $module->returnErrorResponse('Missing param - fields');
}

$module->getData($_POST['filters'], $_POST['fields']);

?>