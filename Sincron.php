<?php

/**
 * Provides access to the Sincron API.
 *
 * @author Andrei Tripon <contact@andreitripon.ro>
 */
class Sincron{

    /**
     * The Sincron API endpoint URL.
     * @var string
     */
    public $apiUrl = 'https://sincron.biz/v2.7.8/apis';

    /**
     * Information about the current Digest HTTP authorization attempt.
     * @var array
     */
    private $authInfo = array();

    /**
     * Setting authentication information
     * To create an username and password, go to: http://sincron.biz/
     *
     * @param string $username    Client API username.
     * @param string $password    Client API password.
     *
     * @return Sincron `$this`
     */
    public function setAuthInfo($username, $password){
        $this->authInfo = array(
            'username' => $username,
            'password' => $password,
        );

        return $this;
    }

    /**
     * Perform an HTTP request by posting the given payload and returning the result.
     *
     * @param string  $resource     API endpoint to call.
     * @param string  $method       Call method
     * @param array   $obj          Associative array of arguments.
     *
     * @return array Response body
     */
    protected function exec($method, $path, $obj = array()) {
        $curl = curl_init();

        switch($method) {
            case 'GET':
                if(strrpos($path, "?") === FALSE) {
                    $path .= '?' . http_build_query($obj);
                }
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj));
                break;
            case 'PUT':
            case 'DELETE':
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method)); // method
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj)); // body
        }
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($curl, CURLOPT_USERPWD, $this->authInfo['username'].':'.$this->authInfo['password']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl.$path);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);

        // Exec
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        // Data
        $header = trim(substr($response, 0, $info['header_size']));

        strtok($header, "\r\n"); // skip status code
        $responseHeaders = array();
        while(($name = trim(strtok(":"))) && ($value = trim(strtok("\r\n")))){
            $responseHeaders[strtolower($name)] = (isset($responseHeaders[$name])
                    ? $responseHeaders[$name] . '; '
                    : null) . $value;
        }

        $body = substr($response, $info['header_size']);

        return array('status' => $info['http_code'], 'header' => $responseHeaders, 'result' => json_decode($body, true));
    }

    /**
     * Get content with method get
     * @see Sincron->exec()
     */
    public function get($path, $args = array()){
        return $this->exec("GET", $path, $args);
    }

    /**
     * Get content with method post
     * @see Sincron->exec()
     */
    public function post($path, $args = array()) {
        return $this->exec("POST", $path, $args);
    }

    /**
     * Get content with method put
     * @see Sincron->exec()
     */
    public function put($path, $args = array()) {
        return $this->exec("PUT", $path, $args);
    }

    /**
     * Get content with method delete
     * @see Sincron->exec()
     */
    public function delete($path, $args = array()) {
        return $this->exec("DELETE", $path, $args);
    }
}