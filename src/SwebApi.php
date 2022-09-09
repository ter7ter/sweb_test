<?php
class SwebApi
{
    /**
     * @var string
     */
    public $server = 'https://api.sweb.ru/';

    /**
     * @var string
     */
    public $version = '1.146.20220905112233';

    /**
     * @var string|null
     */
    private $_token = null;
    /**
     * @var string|null
     */
    private $_login = null;
    /**
     * @var string|null
     */
    private $_password = null;

    /**
     * SwebApi constructor.
     * @param string $login
     * @param string $password
     * @param string|null $server
     */
    public function __construct(string $login, string $password, $server = null) {
        $this->_login = $login;
        $this->_password = $password;
        if (isset($server)) {
            $this->server = $server;
        }
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $params
     * @return string
     * @throws \Exception
     */
    private function _request(string $uri, string $method, array $params):string {
        $request_data = [
            'jsonrpc' => '2.0',
            'version' => $this->version,
            'method'  => $method,
            'params'  => $params
        ];
        $ch = curl_init();
        $headers = ['Content-Type: application/json; charset=utf-8',
                    'Accept: application/json'];
        if (isset($this->token)) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }
        curl_setopt_array( $ch,
                [ CURLOPT_URL => $this->server . $uri,
                  CURLOPT_HTTPHEADER => $headers,
                  CURLOPT_HEADER => 0,
                  CURLOPT_POST => 1,
                  CURLOPT_POSTFIELDS => json_encode($request_data),
                  CURLOPT_RETURNTRANSFER => 1 ]
        );
        $response_json = curl_exec( $ch );
        if ( curl_errno( $ch ) )
        {
            throw new Exception( curl_error( $ch ), curl_errno( $ch ) );
        }
        if ( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) != 200 )
        {
            throw new Exception( sprintf( 'Curl response http error code "%s"',
                curl_getinfo( $ch, CURLINFO_HTTP_CODE ) ) );
        }
        curl_close( $ch );
        $response = json_decode( $response_json );
        if (isset($response->result)) {
            return $response->result;
        } elseif (isset($response->error)) {
            throw new \Exception("Error code " . $response->error->code . ": " . $response->error->message);
        }
    }

    /**
     * @param string|null $login
     * @param string|null $password
     * @return string
     * @throws \Exception
     */
    public function getToken(string $login = null, string $password = null):string {
        if (isset($login)) {
            $this->_login = $login;
        }
        if (isset($password)) {
            $this->_password = $password;
        }
        $params = ['login' => $this->_login, 'password' => $this->_password];
        $response = $this->_request('notAuthorized', 'getToken', $params);
        $this->token = $response;
        return $this->token;
    }

    /**
     * @param string $domain
     * @param string $prolongType
     * @return int
     * @throws \Exception
     */
    public function move(string $domain, string $prolongType = 'none'):int {
        if (is_null($this->_token)) {
            $this->getToken();
        }
        $params = ['domain' => $domain, 'prolongType' => $prolongType];
        $response = $this->_request('domains', 'move', $params);
        return $response;
    }
}