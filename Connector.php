<?php

namespace nalletje\imuis_api;

use nalletje\imuis_api\Handlers\Response;
use nalletje\imuis_api\Exception\FailedLoginException;

/**
 * Imuis Connector -
 *
 * Based on Example code from http://cswdoc.imuisonline.com/?page_id=173
 * Based on https://github.com/Opifer/Imuis/ - Credits to Rick van Laarhoven <r.vanlaarhoven@opifer.nl>
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class Connector
{
    /** @var string */
    protected $partnerKey;

    /** @var string */
    protected $environment;

    /** @var string */
    protected $url;

    /** @var \GuzzleHttp\Client */
    protected $_client;

    /** @var string */
    protected $_session;

    /**
     * Constructor
     *
     * @param string $partnerKey
     * @param string $environment
     * @param string $url
     */
    public function __construct($partnerKey, $environment, $url = 'https://cloudswitch.imuisonline.com/ws1_api.aspx')
    {
        $this->partnerKey = $partnerKey;
        $this->environment = $environment;
        $this->url = $url;
        $this->initializeClient();  //  Initialize GuzzleHttp
        $this->setSession();             // Logs in @ cloudswitch && sets session
    }

    /**
     * Initialize the client
     */
    public function initializeClient()
    {
        $this->_client = new \GuzzleHttp\Client();
    }

    /**
     * Logs in to the iMuis API and returns the session ID
     *
     * @throws FailedLoginException
     *
     * @return string
     */
    public function login()
    {
        $response = $this->_client->post($this->url, [
            'form_params' => [
                'ACTIE' => 'LOGIN',
                'partnerkey' => $this->partnerKey,
                'omgevingscode' => $this->environment
            ]
        ]);
        $xmlResponse = new Response($response);
        if ($xmlResponse->hasErrors()) {
            throw new FailedLoginException($xmlResponse->getError());
        }

        $data = $xmlResponse->getData();
        return (string) $data->SESSION->SESSIONID;
    }

    /**
     * Logs out to the iMuis API
     *
     * @throws FailedLogoutException
     *
     * @return string
     */
    public function logout()
    {
        $response = $this->_client->post($this->url, [
            'form_params' => [
                'ACTIE'         => 'LOGOUT',
                'partnerkey'    => $this->partnerKey,
                'omgevingscode' => $this->environment,
                'SESSIONID'     => $this->_session,
            ]
        ]);

        $xmlResponse = new Response($response);

        if ($xmlResponse->hasErrors()) {
            throw new FailedLoginException($xmlResponse->getError());
        }
    }

    /**
     * Call the API
     *
     * @return Response
     */
    public function call($action, $definition, $statements)
    {
        $response = $this->_client->post($this->url, [
            'form_params' => [
                'ACTIE' => $action,
                'SESSIONID' => $this->_session,
                'partnerkey' => $this->partnerKey,
                'omgevingscode' => $this->environment,
                $definition => $statements
            ]
        ]);
        $xmlResponse = new Response($response);
        if ($xmlResponse->hasErrors()) {
            throw new FailedLoginException($xmlResponse->getError());
        }
        return $xmlResponse->getData();
    }

    /**
     * Set session ID from cloudswitch
     *
     * @return string
     */
    public function setSession()
    {
        if (null === $this->_session) {
            $this->_session = $this->login();
        }
        return $this->_session;
    }

    /**
     * Array to XML
     *
     * @return XML
     **/
    public function arrayToXML($array, $xml = false)
    {
        $xml = new \SimpleXMLElement('<NewDataSet/>');
        $this->subarrayToXML($xml, $array);
        $return = $xml->asXML();
        return str_replace("<?xml version=\"1.0\"?>\n", '', $return);
    }

    /* In case arraytoXMl has Subs */
    private function subarrayToXML($xml, $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (@$value['_remove_key']) {
                    unset($value['_remove_key']);
                    $sub = $xml->addChild($value['_use_key']);
                    $this->subarrayToXML($sub, $value);
                } else {
                    if (@$value[0]['_remove_key']) {
                        $this->subarrayToXML($xml, $value);
                    } else {
                        $sub = $xml->addChild($key);
                        $this->subarrayToXML($sub, $value);
                    }
                }
            } else {
                if ($key === '_use_key') {
                    continue;
                }
                $xml->addChild($key, $value);
            }
        }
        return $xml;
    }
}
