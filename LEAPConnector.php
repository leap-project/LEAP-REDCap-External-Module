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

        // Construct SQL string
        // TODO: escape str, project_id needs come from EM settings
        // $sql = 'SELECT * FROM redcap_data WHERE project_id=13 AND record IN (SELECT record FROM redcap_data WHERE project_id=13 AND ' . $filters . ')';
        
        // $sql = 'SELECT ' . $this->escapeString($fields) . ' FROM redcap_data WHERE ' . $filters;

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
            $this->returnErrorResponse($e->getMessage());
        }
    }

    function getData($filters, $fields) {

        $params = [];
        $params[project_id] = 13;
        $params['return_format'] = 'json';

        if ($filters != "") {
            $params['filterLogic'] = $filters;
        }

        if ($fields != "") {
            $params['fields'] = explode(', ', $fields);
        }
        
        // $params = array('project_id'=>13,'filterLogic'=>$filters, 'fields'=>explode(', ', $fields));
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
 