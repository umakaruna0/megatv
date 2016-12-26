<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;
use Snoopy\Snoopy;

Localization\Loc::loadMessages(__FILE__);

class Kinopoisk{
    /**
     * @var Snoopy
     */
    private $snoopy;
    /**
     * @var array with kinopoisk account info
     */
    private $auth = array();
    const CLIENT_AGENT = "Mozilla/5.0 (Windows; U; Windows NT 6.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 Some plugins";
    
    /**
     * KinopoiskInfo constructor.
     * @param string|null $kinopoiskLogin
     * @param string|null $kinopoiskPass
     */
    public function __construct($kinopoiskLogin=null, $kinopoiskPass=null)
    {
        $this->snoopy = new Snoopy();
        $this->snoopy->maxredirs = 2;
        if($kinopoiskLogin && $kinopoiskPass) $this->auth = array(
            'shop_user[login]' => $kinopoiskLogin,
            'shop_user[pass]' => $kinopoiskPass,
            //'shop_user[mem]' => 'on',
            'auth' => 'go',
        );
        $this->snoopy->agent = self::CLIENT_AGENT;
    }
    
    /**
     * KinopoiskInfo constructor.
     * @param string|null $kinopoiskLogin
     * @param string|null $kinopoiskPass
     */
    public function searchActor($actor)
    {
        /*if(count($this->auth)>0)
        {
            $this->snoopy->submit('http://www.kinopoisk.ru/level/7/', $this->auth);
            if($this->snoopy->status > 500 )
            {
                die("Error: ".$this->snoopy->response_code.", ".$this->snoopy->status);
            }
        }*/
        
        $url = "https://www.kinopoisk.ru/index.php?level=7&from=forma&result=adv&m_act[from]=forma&m_act[what]=actor&m_act[find]=".$actor;
        $this->snoopy->fetch($url);
        
        $mainPage = $this->snoopy -> results;
        $mainPage = iconv('windows-1251' , 'utf-8', $mainPage);

        $pattern = '#<a href="/name/(\d+)/sr/1/".*?data-url="(.*?)".*?class="js-serp-metrika".*?data-type="person".*?>(.*?)</a>#si';
        if (preg_match($pattern, $mainPage, $matches)) 
        {
            if(intval($matches[1])>0 && !empty($matches[2]) && strpos($matches[2], $matches[1])!==false)
            {
                return "https://www.kinopoisk.ru". $matches[2];
            }
        }
        
        return false;
    }

    private function fixBadChars($string){
        $charsMap = array(
            '&#130;'=>',',//',' baseline single quote
            '&#131;'=>'',//'NLG' florin
            '&#132;'=>'"',//'"' baseline double quote
            '&#133;'=>'...',//'...' ellipsis
            '&#134;'=>'**', // dagger (a second footnote)
            '&#135;'=>'***', //double dagger (a third footnote)
            '&#136;'=>'^', // circumflex accent
            '&#151;'=>'-',// emdash
        );
        return str_replace(array_keys($charsMap),array_values($charsMap),$string);
    }
    private function resultClear( $val, $key = '' ){
        if ( empty( $val ) || $val == '-' ){
            $val = '';
        } else {
            $pattern = array('&nbsp;', '&laquo;', '&raquo;');
            $pattern_replace = array(' ','','');
            $val = str_replace( $pattern, $pattern_replace, $val );
        }
        switch ($key) {
            case 'genre':
            case 'producer':
            case 'operator':
            case 'director':
            case 'script':
            case 'composer':
                $val = str_replace(', ...','', $val );
                break;
        }
        return $val;
    }
}