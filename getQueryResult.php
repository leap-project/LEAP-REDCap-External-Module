<?php

if (!isset($_POST['auth'])) {
    $module->returnErrorResponse('Missing param - auth');
}

if (!$module->checkAuthToken($_POST['auth'])) {
    $module->returnErrorResponse('Could not authenticate');
}

if (!isset($_POST['query'])) {
    $module->returnErrorResponse('Missing param - query');
}

$module->getSqlResult($_POST['query']);

?>