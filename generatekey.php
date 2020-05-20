<?php

$module->generateAuthToken();

echo json_encode(array('status' => true));

?>