<?php

/**
* Class for the language object
* 
* @author   Amitay Horwitz <amitayh@gmail.com>
* @package  GoogleTranslate
* @license  MIT style license
* @version  0.1
*/
class GoogleTranslate_Language
{
    
    /**
    * Language code
    * 
    * @var string
    */
    protected $_code;
    
    /**
    * List of supported languages. Contains language code and name in English.
    * 
    * @var array
    */
    protected static $_langs = array('af' => 'Afrikaans', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic',
                                     'hy' => 'Armenian', 'az' => 'Azerbaijani', 'eu' => 'Basque', 'be' => 'Belarusian',
                                     'bn' => 'Bengali', 'bh' => 'Bihari', 'bg' => 'Bulgarian', 'my' => 'Burmese',
                                     'ca' => 'Catalan', 'chr' => 'Cherokee', 'zh' => 'Chinese', 'zh-CN' => 'Chinese simplified',
                                     'zh-TW' => 'Chinese traditional', 'hr' => 'Croatian', 'cs' => 'Czech', 'da' => 'Danish',
                                     'dv' => 'Dhivehi', 'nl' => 'Dutch', 'en' => 'English', 'eo' => 'Esperanto',
                                     'et' => 'Estonian', 'tl' => 'Tagalog', 'fi' => 'Finnish', 'fr' => 'French',
                                     'gl' => 'Galician', 'ka' => 'Georgian', 'de' => 'German', 'el' => 'Greek',
                                     'gn' => 'Guarani', 'gu' => 'Gujarati', 'iw' => 'Hebrew', 'hi' => 'Hindi',
                                     'hu' => 'Hungarian', 'is' => 'Icelandic', 'id' => 'Indonesian', 'iu' => 'Inuktitut',
                                     'it' => 'Italian', 'ja' => 'Japanese', 'kn' => 'Kannada', 'kk' => 'Kazakh',
                                     'km' => 'Khmer', 'ko' => 'Korean', 'ku' => 'Kurdish', 'ky' => 'Kyrgyz',
                                     'lo' => 'Laothian', 'lv' => 'Latvian', 'lt' => 'Lithuanian', 'mk' => 'Macedonian',
                                     'ms' => 'Malay', 'ml' => 'Malayalam', 'mt' => 'Maltese', 'mr' => 'Marathi',
                                     'mn' => 'Mongolian', 'ne' => 'Nepali', 'no' => 'Norwegian', 'or' => 'Oriya',
                                     'ps' => 'Pashto', 'fa' => 'Persian', 'pl' => 'Polish', 'pt-PT' => 'Portuguese',
                                     'pa' => 'Punjabi', 'ro' => 'Romanian', 'ru' => 'Russian', 'sa' => 'Sanskrit',
                                     'sr' => 'Serbian', 'sd' => 'Sindhi', 'si' => 'Sinhalese', 'sk' => 'Slovak',
                                     'sl' => 'Slovenian', 'es' => 'Spanish', 'sw' => 'Swahili', 'sv' => 'Swedish',
                                     'tg' => 'Tajik', 'ta' => 'Tamil', 'te' => 'Telugu', 'th' => 'Thai',
                                     'bo' => 'Tibetan', 'tr' => 'Turkish', 'uk' => 'Ukrainian', 'ur' => 'Urdu',
                                     'uz' => 'Uzbek', 'ug' => 'Uighur', 'vi' => 'Vietnamese');

    /**
    * List of right-to-left language codes
    * 
    * @var array
    */
    protected $_langs_rtl = array('ar', 'iw', 'dv', 'ur');
    
    /**
    * Constructor
    * 
    * If $lang is not null the object will try to match language by code. If no matches
    * are found - by name. If both fail - throws a GoogleTranslate_Language_Exception
    * 
    * @param null|string $lang
    * @return GoogleTranslate_Language
    * @throws GoogleTranslate_Language_Exception
    */
    public function __construct($lang = null)
    {
        if ($lang !== null) {
            try {
                $this->setLangByCode($lang);
            } catch (GoogleTranslate_Language_Exception $exception) {
                try {
                    $this->setLangByName($lang);
                } catch (GoogleTranslate_Language_Exception $exception) {
                    /**
                    * @see GoogleTranslate_Language_Exception
                    */
                    require_once dirname(__file__) . '/Language/Exception.php';
                    throw new GoogleTranslate_Language_Exception('Unable to instantiate: unrecognized language');
                }
            }
        }
        return $this;
    }
    
    /**
    * @return string Language name 
    */
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
    * Set language by code
    * 
    * @param string $code
    * @return GoogleTranslate_Language
    * @throws GoogleTranslate_Language_Exception
    */
    public function setLangByCode($code)
    {
        if (self::langCodeExists($code)) {
            $this->_code = $code;
        } else {
            /**
            * @see GoogleTranslate_Language_Exception
            */
            require_once dirname(__file__) . '/Language/Exception.php';
            throw new GoogleTranslate_Language_Exception('Invalid language code');
        }
        return $this;
    }
    
    /**
    * Set language by name
    * 
    * @param string $name
    * @return GoogleTranslate_Language
    * @throws GoogleTranslate_Language_Exception
    */
    public function setLangByName($name)
    {
        $code = self::langNameExists($name);
        if ($code !== false) {
            $this->_code = $code;
        } else {
            /**
            * @see GoogleTranslate_Language_Exception
            */
            require_once dirname(__file__) . '/Language/Exception.php';
            throw new GoogleTranslate_Language_Exception('Invalid language name');
        }
        return $this;
    }
    
    /**
    * Get language code
    * 
    * @return string
    */
    public function getCode()
    {
        return $this->_code;
    }
    
    /**
    * Get language name
    * 
    * @return string
    */
    public function getName()
    {
        return self::$_langs[$this->_code];
    }
    
    /**
    * Check if language is written right-to-left
    * 
    * @return boolean
    */
    public function isRTL()
    {
        return in_array($this->_code, $this->_langs_rtl);
    }
    
    /**
    * Check if a language code is valid
    * 
    * @param string $code
    * @return boolean
    */
    public static function langCodeExists($code)
    {
        return isset(self::$_langs[$code]);
    }
    
    /**
    * Check if a language name is valid
    * 
    * @param string $name
    * @return false|string
    */
    public static function langNameExists($name)
    {
        return array_search($name, self::$_langs, true);
    }
    
}