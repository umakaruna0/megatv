<?php
/**
 * Created by olegpro.ru.
 * User: Oleg Maksimenko <oleg.39style@gmail.com>
 * Date: 28.03.2015
 */

namespace Olegpro\IpGeoBase;

use Bitrix\Main\Text\Encoding;


class IpGeoBase extends \IPGeoBase
{

    /** @var IPGeoBase */
    public static $instance;

    /** @var array */
    protected static $cacheIp = array();

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $dataDir = dirname(__DIR__);
            self::$instance = new self($dataDir . '/data/cidr_optim.txt', $dataDir . '/data/cities.txt');
        }

        return self::$instance;
    }

    /**
     * @param string $ip
     * @return mixed
     */
    public function getRecord($ip = null)
    {
        if($ip === null) {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        }

        if (!isset(self::$cacheIp[$ip])) {
            $result = parent::getRecord($ip);

            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    $result[$key] = Encoding::getInstance()->convertEncoding($value, 'windows-1251', SITE_CHARSET);
                }
            }

            self::$cacheIp[$ip] = $result;
        }

        return self::$cacheIp[$ip];
    }

}