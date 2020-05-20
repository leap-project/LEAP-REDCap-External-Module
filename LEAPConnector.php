<?php
namespace LEAP\LEAPConnector;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

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
    function getData($fields, $filters) {

        // Construct SQL string
        $sql = 'SELECT ' . $this->escapeString($fields) . ' FROM redcap_data WHERE ' . $this->escapeString($filters);

        try {
            // Query SQL using the external modules query() function
            $result = $this->query($sql);

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


    function returnErrorResponse($msg) {
        echo json_encode(array('success' => false, 'error' => $msg));
        exit;
    }
    
	
}
 