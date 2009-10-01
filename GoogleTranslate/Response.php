<?php

/**
* Class for the translation response
* 
* @author   Amitay Horwitz <amitayh@gmail.com>
* @package  GoogleTranslate
* @license  MIT style license
* @version  0.1
*/
class GoogleTranslate_Response
{
    
    /**
    * Response as raw text
    * 
    * @var string
    */
    protected $_result_text;
    
    /**
    * Response object
    * 
    * @var mixed
    */
    protected $_result_object;
    
    /**
    * Parsed result
    * 
    * @var array
    */
    protected $_result = array(
        'translation'   => '',
        'dictionary'    => array()
    );
    
    /**
    * Constructor
    * 
    * @param string $result
    * @return void
    */
    public function __construct($result)
    {
        $this->_result_text = $result;
        $this->_parse();
    }
    
    /**
    * Parses response text to response object and result array 
    */
    protected function _parse()
    {
        $this->_result_object = json_decode($this->_result_text);
        switch (gettype($this->_result_object)) {
            case 'string':
                $this->_result['translation'] = $this->_result_object;
                break;
            case 'array':
                $this->_result['translation'] = $this->_result_object[0];
                if (isset($this->_result_object[1])) {
                    foreach ($this->_result_object[1] as $dict) {
                        $this->_result['dictionary'][$dict[0]] = array_slice($dict, 1);
                    }
                }
                break;
            default:
                /**
                * @see GoogleTranslate_Response_Exception
                */
                require_once dirname(__file__) . '/Response/Exception.php';
                throw new GoogleTranslate_Response_Exception('Unable to parse response object');
        }
    }
    
    /**
    * Get result
    * 
    * When $type is not given - returns the whole result array. Otherwise - returns result by type.
    * 
    * @param null|string $type
    * @return mixed
    */
    public function get($type = null) {
        if ($type === null) {
            return $this->_result;
        }
        if (isset($this->_result[$type])) {
            return $this->_result[$type];
        } else {
            /**
            * @see GoogleTranslate_Response_Exception
            */
            require_once dirname(__file__) . '/Response/Exception.php';
            throw new GoogleTranslate_Response_Exception('Invalid result type');
        }
    }
    
}