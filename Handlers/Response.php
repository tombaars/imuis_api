<?php

namespace nalletje\imuis_api\Handlers;

/**
 * Imuis XML Response
 *
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class Response
{
    protected $_response;

    /**
     * Constructor
     *
     * Parse the XML String to XML
     * @param \Object $response
     */
    public function __construct($response)
    {
        $this->_response = $this->parseResponseToXML($response);
    }

    /**
     * Check if the response has errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        if (isset($this->_response->ERROR)) {
            return true;
        }
        return false;
    }

    /**
     * Get the errors
     *
     * @return string
     */
    public function getError()
    {
        if (isset($this->_response->ERROR)) {
            $message = (string) $this->_response->ERROR->MESSAGE;
            return $message;
        }
        return '';
    }

    /**
     * Get the data as an array
     *
     * @return array
     */
    public function getData()
    {
        return (object) $this->_response;
    }

    /**
     * Parse GuzzleHTTP response data to XML
     *
     * @return xml
     */
    protected function parseResponseToXML($response)
    {
        return simplexml_load_string((string)$response->getBody()->getContents());
    }
}
