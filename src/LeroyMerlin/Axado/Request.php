<?php
namespace Axado;

class Request
{
    /**
     * Api url
     * @var string
     */
    protected $urlConsult = "http://api.axado.com.br/v2/consulta/?token=";

    /**
     * Constructor
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Do the consult for shipping
     * @param  string $jsonString
     */
    public function consultShipping($jsonString)
    {
        $raw = $this->doRequest("POST", $this->urlConsult, $jsonString);
        return $this->createResponse($raw);
    }

    /**
     * Return the response instance.
     * @param  string $raw
     * @return Axado\Response
     */
    protected function createResponse($raw)
    {
        $response = new Response;
        $response->parse($raw);

        return $response;
    }

    /**
     * Request to Axado API.
     * @return string
     */
    protected function doRequest($method, $path, $data)
    {
        $conn = curl_init();

        curl_setopt($conn, CURLOPT_URL, $path);
        curl_setopt($conn, CURLOPT_TIMEOUT, 5);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($conn, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($conn);
        $data     = null;

        if ($response !== false) {
            $data = json_decode($response, true);
        }

        curl_close($conn);

        return $data;
    }
}