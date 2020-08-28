<?php
namespace LEAP\LEAPConnector;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use REDCap;

class LEAPConnector extends AbstractExternalModule {
    
    // Generates an auth token and saves it in the configuration
    // TODO: this does not work well - the configuration needs to reload for the key to show up. Also, the field should not be editable
    function generateAuthToken() {

        // Generate a key
        $key = bin2hex(random_bytes(16));
        
        // Share key in config options
        $this->setSystemSetting('leap_auth', $key);

        // Refresh the page
        window.location.reload(true);

    }

    // Check auth
    function checkAuthToken($token) {
        if (strval($token) == $this->getSystemSetting('leap_auth')) {
            return true;
        }
        return false;
    }
    
    // Escape strings for SQL
    function escapeString($str) {
        $value = db_escape($str);
        if (!is_numeric($value)) {
            $value = db_real_escape_string($value);
        }
        return $value;
    }

    // Gets data from SQL based on fields and filters
    function getSqlResult($query) {
        try {
            // Query SQL using the external modules query() function
            $result = $this->query($query);

            // Read data
            $data = array();
            if (db_num_rows($result)) {
                while ($row = db_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
        
            // Return query results.
            echo json_encode(array('success' => true, 'data' => $data));
            exit;

        } catch (Exception $e) {
            // Return SQL error
            // TODO: ensure that sensitive information is not returned in the error
            // $this->getSystemSetting('leap_auth')
            $this->returnErrorResponse($e->getMessage());
        }
    }

    function getData($filters, $fields, $pid) {

        $params = [];
        $params['project_id'] = $pid;
        $params['return_format'] = 'json';

        if ($filters != "") {
            $params['filterLogic'] = $filters;
        }

        if ($fields != "") {
            $params['fields'] = explode(', ', $fields);
        }
        
        $data = REDCap::getData($params);
        echo json_encode(array('success' => true, 'data' => json_decode($data)));

        //$all_export_field_names = REDCap::getExportFieldNames();
        //echo json_encode($all_export_field_names);
    }

    function returnErrorResponse($msg) {
        echo json_encode(array('success' => false, 'error' => $msg));
        exit;
    }
    
	
}
 