<?php
/*
 * Library for the KeyCDN API
 *
 * @author Tobias Moser
 * @version 0.1
 *
 */

class KeyCDN {

    public $username;
    public $password;
    public $KeyCDN_api = 'https://www.keycdn.com';


    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function get($selected_call, $params = array()) {
        return $this->execute($selected_call, 'GET', $params);
    }

    public function post($selected_call, $params = array()) {
        return $this->execute($selected_call, 'POST', $params);
    }

    public function put($selected_call, $params = array()) {
        return $this->execute($selected_call, 'PUT', $params);
    }

    public function delete($selected_call, $params = array()) {
        return $this->execute($selected_call, 'DELETE', $params);
    }


    private function execute($selected_call, $method_type, $params) {

        $endpoint = $this->KeyCDN_api.'/'.$selected_call;

        // start with curl and prepare accordingly
        $ch = curl_init();

        // create basic auth information        
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);

        // return transfer as string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // set curl timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        // retrieve headers
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

        // set request type
        if ($method_type != 'POST' && $method_type != 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method_type);
        }

        $query_str = http_build_query($params);
        // send query-str within url or in post-fields
        if ($method_type == 'POST' || $method_type == 'PUT' || $method_type == 'DELETE') {
            $req_uri = $endpoint;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_str);
        } else {
            $req_uri = $endpoint .'?'. $query_str;
        }

        // url
        curl_setopt($ch, CURLOPT_URL, $req_uri);

        // make the request
        $result = curl_exec($ch);
        $headers = curl_getinfo($ch);
        $curl_error = curl_error($ch);

        curl_close($ch);

        // get json_output out of result (remove headers)
        $json_output = substr($result, $headers['header_size']);

        // error catching
        if (!empty($curl_error) || empty($json_output)) {
            throw new Exception('KeyCDN-Error: '.$curl_error.', Output: '.$json_output);
        }

        return $json_output;
        
    }
    
}

?>
