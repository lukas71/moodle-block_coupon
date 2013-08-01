<?php

/**
 * File: rest_server.php
 * Encoding: UTF-8
 * @package: BLCC
 *
 * @Version: 1.0.0
 * @Since 11-mrt-2013
 * @Author: Sebastian Berm :: sebsoft.nl
 * @Copyright sebsoft.nl
 * 
 * Overloaded/Modified version of the Zend_Rest_Server class.
 * Enabled to output in serveral formats (e.g. xml, json, jsonp, ...)
 * */
class Rest_Server extends Zend_Rest_Server {

    /**
     * Handles the Rest request
     *
     * @param array $request Request parameters
     */
    public function handle($request = false) {
        //Make sure Zend_Rest_Server returns its response (and does not ouput it!)
        $this->returnResponse(true);

        //Get the XML result generated by Zend_Rest_Server
        $xml = parent::handle($request);

        //Add extra header to ditch caching
        $this->_headers[] = "Expires: Mon, 26 Jul 1990 05:00:00 GMT";
        $this->_headers[] = "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT";
        $this->_headers[] = "Cache-Control: no-store, no-cache, must-revalidate";
        $this->_headers[] = "Cache-Control: post-check=0, pre-check=0";
        $this->_headers[] = "Pragma: no-cache";


        //Parse/Load XML
        $xml = simplexml_load_string($xml);

        if (!isset($_GET['resultType'])) {
            $_GET['resultType'] = 'xml';
        }
        //Magic to give the requested output
        //Switches beteween the requested format and call's the function associated with the output format
        switch ($_GET['resultType']) {
            case 'xml':
                $this->outputAsXML($xml);
                break;
            case 'txt':
            case 'text':
                $this->outputAsText($xml);
                break;
            case 'array':
                $this->outputAsArray($xml);
                break;
            case 'array_serialize':
                $this->outputAsSerializedArray($xml);
                break;
            case 'json':
                $this->outputAsJson($xml);
                break;
            case 'jsonp':
                $this->outputAsJsonp($xml);
                break;
            case 'plain':
                $this->outputAsPlain($xml);
                break;
        }
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in Text format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsText(SimpleXMLElement $response, $returnData = false) {
        $this->changeContentTypeHeader('text/plain');

        $data = '';
        //Check if status is failed
        if (isset($response->status) && $response->status == 'failed') {
            //We failed (function does not exists)
            $data = 'status=FALSE|message=' . (string) $response->response->message;
        } elseif (isset($response->$_GET['method']->status) && $response->$_GET['method']->status == 'failed') {
            //Function call failed (e.g. too few parameters)
            $data = 'status=FALSE|message=' . (string) $response->$_GET['method']->response->message;
        } else {
            $data = $this->simpleXMLToText($response->$_GET['method']);
        }

        $this->outputResponse($data);
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in XML format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsXML(SimpleXMLElement $response, $returnData = false) {
        //Send correct content-type headers
        $this->changeContentTypeHeader('text/xml');

        //Create an empty XML Document
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->xmlStandalone = true;
        $xml->formatOutput = true;

        //Create root element
        $root = $xml->createElement('moodle');
        $xml->appendChild($root);


        //Check if status is failed
        if (isset($response->status) && $response->status == 'failed') {
            //We failed (function does not exists)
            $status = $xml->createElement('status');
            $status->appendChild($xml->createTextNode('FALSE'));
            $root->appendChild($status);

            $status = $xml->createElement('message');
            $status->appendChild($xml->createTextNode((string) $response->response->message));
            $root->appendChild($status);
        } elseif (isset($response->$_GET['method']->status) && $response->$_GET['method']->status == 'failed') {

            //Function call failed (e.g. too few parameters)
            $status = $xml->createElement('status');
            $status->appendChild($xml->createTextNode('FALSE'));
            $root->appendChild($status);

            $status = $xml->createElement('message');
            $status->appendChild($xml->createTextNode((string) $response->$_GET['method']->response->message));
            $root->appendChild($status);
        } else {
            $this->simpleXMLToDom($xml, $root, $response->$_GET['method']);
        }


        if ($returnData)
            return $xml->saveXML();

        $this->outputResponse($xml->saveXML());
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in Array format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsArray(SimpleXMLElement $response, $returnData = false) {
        //Send correct content type headers
        $this->changeContentTypeHeader('text/plain');

        //Use print_r to turn array into text
        $data = print_r($this->responseToArray($response), true);

        if ($returnData)
            return $data;
        $this->outputResponse($data);
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in Serialized Array format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsSerializedArray(SimpleXMLElement $response, $returnData = false) {
        //Set correct content type
        $this->changeContentTypeHeader('text/plain');

        //Serialize the array
        $data = serialize($this->responseToArray($response));

        if ($returnData)
            return $data;
        $this->outputResponse($data);
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in Json format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsJson(SimpleXMLElement $response, $returnData = false) {
        //Set correct content type
        $this->changeContentTypeHeader('application/json');

        //Get data as an array and encode it
        $data = json_encode($this->responseToArray($response));

        if ($returnData)
            return $data;
        $this->outputResponse($data);
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in JsonP format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsJsonp(SimpleXMLElement $response, $returnData = false) {
        //Set correct content type
        $this->changeContentTypeHeader('application/json');

        //Check if a specific callback is specified
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
        } else {
            $callback = $_GET['method'];
        }

        //get output from json function and wrap it in jsonp
        $data = $callback . '(' . $this->outputAsJson($response, true) . ');';

        if ($returnData)
            return $data;
        $this->outputResponse($data);
    }

    /**
     * Ouputs the Zend_Rest_Server ouput in Plain (boolean only) format
     *
     * @param SimpleXMLElement $response Response from Zend_Rest_Server
     * @param boolean $returnData If set to true, it will return it's result, instead of outputting it
     */
    private function outputAsPlain(SimpleXMLElement $response, $returnData = false) {
        //Set correct content type
        $this->changeContentTypeHeader('text/plain');

        //Check if status is failed
        if ((isset($response->status) && $response->status == 'failed') ||
                (isset($response->$_GET['method']->status) && $response->$_GET['method']->status == 'failed')) {
            //We failed (function does not exists or failed to execute because of Zend_Rest stuff)
            $data = 'FALSE';
        } else {
            //TODO How to check this?! :S
            $data = 'TRUE';
        }

        if ($returnData)
            return $data;
        $this->outputResponse($data);
    }

    /**
     * Converts an Zend_Rest_Server response (put into an SimpleXML object)
     * into an array with the orginal result data.
     *
     * @param SimpleXMLElement $response
     * @return array of mixed
     */
    private function responseToArray(SimpleXMLElement $response) {
        //Empty array where the data should go
        $data = array();

        //Check if status is failed
        if (isset($response->status) && $response->status == 'failed') {
            //We failed (function does not exists)
            $data['status'] = 'FALSE';
            $data['error'] = (string) $response->response->message;
        } elseif (isset($response->$_GET['method']->status) && $response->$_GET['method']->status == 'failed') {

            //Function call failed (e.g. too few parameters)
            $data['status'] = 'FALSE';
            $data['error'] = (string) $response->$_GET['method']->response->message;
        } else {

            //Check if a singulair value was returned
            if (isset($response->$_GET['method']->response)) {
                //Single value, we can't map it, so a 'result' field is created
                $data['result'] = (string) $response->$_GET['method']->response;
            } else {
                //We've got multiple values, let another function handle this one
                $data = $this->simpleXMLToArray($response->$_GET['method']);
            }
        }
        return $data;
    }

    /**
     * Converts an SimpleXMLElement to an array
     *
     * @param SimpleXMLElement $xml
     * @param string $self (check if the function called itself, internal use, please ignore!)
     * @return array of mixed
     */
    private function simpleXMLToArray(SimpleXMLElement $xml, $self = false) {
        //Create empty array to start with
        $array = array();

        //Walk thru all the children
        foreach ($xml->children() as $key => $data) {
            //Lose the 'key_' prefix, if any
            if (strpos($key, 'key_') === 0) {
                $key = str_replace('key_', '', $key);
            }

            //Do we have children? If so, we need to convert them to arrays
            if ($data->children()) {
                //Recursive :)
                $array[$key] = $this->simpleXMLToArray($data);
            } else {
                //No children, get the value
                $array[$key] = (string) $data;
            }
        }

        //Finaly lose status node, it's not part of the orginal data
        if (!$self) {
            unset($array['status']);
        }

        //Return the data
        return $array;
    }

    /**
     * Converts an SimpleXMLElement to text
     *
     * @param SimpleXMLElement $xml
     * @param string $self (check if the function called itself, internal use, please ignore!)
     * @return array of mixed
     */
    private function simpleXMLToText(SimpleXMLElement $xml, $self = false) {
        //Create empty array to start with
        $text = array();

        //Walk thru all the children
        foreach ($xml->children() as $key => $data) {
            //Lose the 'key_' prefix, if any
            if (strpos($key, 'key_') === 0) {
                $key = str_replace('key_', '', $key);
            }

            //Do we have children? If so, we need to convert them to arrays
            if ($data->children()) {
                //Recursive :)
                $text[] = $key . '=[' . $this->simpleXMLToText($data, true) . ']';
            } else {
                //No children, get the value
                //Skip status in root (came from Zend_Soap_Server)
                if (!$self && $key == 'status')
                    continue;

                $text[] = $key . '=' . (string) $data;
            }
        }

        //Finaly lose status node, it's not part of the orginal data
        if (!$self) {
            unset($text['status']);
        }

        //Return the data
        return implode('|', $text);
    }

    /**
     * Converts an SimpleXMLElement to text
     *
     * @param DOMDocument $dom the XML Document
     * @param DOMElement $root Root element to append it all to
     * @param SimpleXMLElement $xml The SImpleXML document to convert
     * @param boolean $oldstyle Use the oldstyle xml (pre Idefix)
     * @param boolean $self (check if the function called itself, internal use, please ignore!)
     * @return DOMElement
     */
    private function simpleXMLToDom(DOMDocument $dom, DOMElement $root, SimpleXMLElement $xml, $oldstyle = false, $self = false) {
        //Walk thru all the children
        foreach ($xml->children() as $key => $data) {
            //Lose the 'key_' prefix, if any
            if (strpos($key, 'key_') === 0) {
                //Check if oldstyle XML is requested. It used <n1>, <n2>, <n3>, enz...
                if ($oldstyle) {
                    $key = str_replace('key_', 'n', $key);
                } else {
                    //New style... <item><item><item>
                    $key = 'item';
                }
            }

            //Do we have children? If so, we need to convert them to arrays
            if ($data->children()) {
                //Recursive :)
                $root->appendChild($this->simpleXMLToDom($dom, $dom->createElement($key), $data, $oldstyle, true));
            } else {
                //No children, get the value
                //Skip status in root (came from Zend_Soap_Server)
                if (!$self && $key == 'status')
                    continue;

                //Create Node for the text element
                $node = $dom->createElement($key);

                //Create text node and append it to it's parent node
                $node->appendChild($dom->createTextNode((string) $data));

                //Append node to root element
                $root->appendChild($node);
            }
        }

        //Return the data
        return $root;
    }

    /**
     * Modifies the current set Content-Type header
     *
     * By default the Content-Type header is set to text/xml by the Zend_Rest_Server class
     *
     * @param string $contentType (e.g. text/plain)
     */
    private function changeContentTypeHeader($contentType) {
        //Get all currently defined headers and walk thru them
        foreach ($this->_headers as &$header) {
            //Check if we got the Content-Type header
            if (strpos($header, 'Content-Type:') === 0) {
                //Modifi Content-Type header
                $header = 'Content-Type: ' . $contentType;
                //We are done, return
                return;
            }
        }
        //If we are here, we did not return... so no Content-Type header is present
        //Add it!
        $this->_header[] = 'Content-Type: ' . $contentType;
    }

    /**
     * Output response to stdout
     *
     * Send headers and ouput to stdout
     *
     * @param string $response
     */
    private function outputResponse($response) {
        if (!headers_sent()) {
            foreach ($this->_headers as $header) {
                header($header);
            }
        }
        echo $response;
    }

    /**
     * Implement Zend_Server_Interface::fault()
     *
     * Creates XML error response, returning DOMDocument with response.
     *
     * @param string|Exception $fault Message
     * @param int $code Error Code
     * @return DOMDocument
     */
    public function fault($exception = null, $code = null) {
        if (isset($this->_functions[$this->_method])) {
            $function = $this->_functions[$this->_method];
        } elseif (isset($this->_method)) {
            $function = $this->_method;
        } else {
            $function = 'rest';
        }

        if ($function instanceof Zend_Server_Reflection_Method) {
            $class = $function->getDeclaringClass()->getName();
        } else {
            $class = false;
        }

        if ($function instanceof Zend_Server_Reflection_Function_Abstract) {
            $method = $function->getName();
        } else {
            $method = $function;
        }

        $dom = new DOMDocument('1.0', $this->getEncoding());
        if ($class) {
            $xml = $dom->createElement($class);
            $xmlMethod = $dom->createElement($method);
            $xml->appendChild($xmlMethod);
        } else {
            $xml = $dom->createElement($method);
            $xmlMethod = $xml;
        }
        $xml->setAttribute('generator', 'zend');
        $xml->setAttribute('version', '1.0');
        $dom->appendChild($xml);

        $xmlResponse = $dom->createElement('response');
        $xmlMethod->appendChild($xmlResponse);

        if ($exception instanceof Exception) {
            $element = $dom->createElement('message');
            $element->appendChild($dom->createTextNode($exception->getMessage()));
            $xmlResponse->appendChild($element);
            $code = $exception->getCode();
        } elseif (($exception !== null) || 'rest' == $function) {
            $xmlResponse->appendChild($dom->createElement('message', 'An unknown error occured. Please try again.'));
        } else {
            $xmlResponse->appendChild($dom->createElement('message', 'Call to ' . $method . ' failed.'));
        }

        $xmlMethod->appendChild($xmlResponse);
        $xmlMethod->appendChild($dom->createElement('status', 'failed'));

        // Headers to send
        if ($code == 401) {
            $this->_headers[] = 'HTTP/1.0 401 Unauthorized';
        } elseif ($code == 404) {
            $this->_headers[] = 'HTTP/1.0 404 File Not Found';
        } else {
            $this->_headers[] = 'HTTP/1.0 400 Bad Request';
        }

        return $dom;
    }

}
?>