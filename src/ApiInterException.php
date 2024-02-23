<?php

namespace Divulgueregional\ApiInterV2;

use GuzzleHttp\Exception\ClientException;

class ApiInterException extends \Exception
{
    /**
     * @var ClientException
     */
    protected $clientException;

    public function __construct($message, $code = 0, \Exception $previous = null, ClientException $clientException = null)
    {
        $this->clientException = $clientException;

        if ($clientException) {
            $this->code = $clientException->getResponse()->getStatusCode();

            if ($clientException && $clientException->getResponse()) {
                $content = $clientException->getResponse()->getBody()->getContents();
                if ($json = json_decode($content)) {
                    $message .= ': ' . (property_exists($json, 'title') ? $json->title : (property_exists($json, 'message') ? $json->message : $content));
                } else {
                    $message .= ': ' . $clientException->getResponse()->getBody()->getContents();
                }
            }
        }

        parent::__construct($message);
    }
}
