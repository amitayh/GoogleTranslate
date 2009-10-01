<?php

/**
* Class for retrieveing translation and dictionary results from Google Translate API
* 
* @author   Amitay Horwitz <amitayh@gmail.com>
* @package  GoogleTranslate
* @license  MIT style license
* @version  0.1
*/

class GoogleTranslate
{
    
    /**
    * API gateway url
    */
    const API_GATEWAY = 'http://translate.google.com/translate_a/t';

    /**
    * Source language
    * 
    * @var GoogleTranslate_Language
    */
    protected $_source_lang;
    
    /**
    * Target language
    * 
    * @var GoogleTranslate_Language
    */
    protected $_target_lang;
    
    /**
    * Constructor
    * 
    * $config is an array of key/value pairs. Options are:
    * 
    * source_lang    => (string|GoogleTranslate_Language) Source language
    * target_lang    => (string|GoogleTranslate_Language) Target language
    * 
    * @param array $config
    * @return GoogleTranslate
    */
    public function __construct($config = array())
    {
        if (isset($config['source_lang'])) {
            $this->setSourceLang($config['source_lang']);
        }
        if (isset($config['target_lang'])) {
            $this->setSourceLang($config['target_lang']);
        }
        return $this;
    }
    
    /**
    * Perform a call to the API to retrieve translation
    * 
    * @param string $text Text to translate
    * @return GoogleTranslate_Response|false
    * @throws GoogleTranslate_Exception
    */
    public function translate($text)
    {
        if (is_string($text) && !empty($text)) {
            if ($this->getSourceLang() === null) {
                /**
                * @see GoogleTranslate_Exception
                */
                require_once dirname(__file__) . '/GoogleTranslate/Exception.php';
                throw new GoogleTranslate_Exception('Source language was not set');
            }
            if ($this->getTargetLang() === null) {
                /**
                * @see GoogleTranslate_Exception
                */
                require_once dirname(__file__) . '/GoogleTranslate/Exception.php';
                throw new GoogleTranslate_Exception('Target language was not set');
            }
            $params = array('client' => 't', 'text' => $text,
                            'sl' => $this->getSourceLang()->getCode(),
                            'tl' => $this->getTargetLang()->getCode(),
                            'ie' => 'utf8', 'oe' => 'utf8');
            $url = self::API_GATEWAY . '?' . http_build_query($params, '', '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            if ($result !== false) {
                require_once dirname(__file__) . '/GoogleTranslate/Response.php';
                return new GoogleTranslate_Response($result);
            }
        }
        return false;
    }
    
    /**
    * Set source language
    * 
    * @param string|GoogleTranslate_Language $lang
    * @return GoogleTranslate
    */
    public function setSourceLang($lang)
    {
        if ($lang instanceof GoogleTranslate_Language) {
            $this->_source_lang = $lang;
        } elseif (is_string($lang)) {
            require_once dirname(__file__) . '/GoogleTranslate/Language.php';
            $this->_source_lang = new GoogleTranslate_Language($lang);
        } else {
            require_once dirname(__file__) . '/GoogleTranslate/Exception.php';
            throw new GoogleTranslate_Exception('Invalid source language type');
        }
        return $this;
    }
    
    /**
    * Set target language
    * 
    * @param string|GoogleTranslate_Language $lang
    * @return GoogleTranslate
    */
    public function setTargetLang($lang)
    {
        if ($lang instanceof GoogleTranslate_Language) {
            $this->_target_lang = $lang;
        } elseif (is_string($lang)) {
            require_once dirname(__file__) . '/GoogleTranslate/Language.php';
            $this->_target_lang = new GoogleTranslate_Language($lang);
        } else {
            require_once dirname(__file__) . '/GoogleTranslate/Exception.php';
            throw new GoogleTranslate_Exception('Invalid target language type');
        }
        return $this;
    }
    
    /**
    * Get source language
    * 
    * @return GoogleTranslate_Language
    */
    public function getSourceLang()
    {
        return $this->_source_lang;
    }
    
    /**
    * Get target language
    * 
    * @return GoogleTranslate_Language
    */
    public function getTargetLang()
    {
        return $this->_target_lang;
    }
    
}