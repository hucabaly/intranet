<?php

namespace Rikkei\Core\View;

use DateTime;
use Illuminate\Support\Facades\Config;

/**
 * View ouput gender
 */
class View
{
    
    /**
     * get date from datetime standard
     * 
     * @param type $datetime
     * @return string
     */
    public static function getDate($datetime)
    {
        return self::formatDateTime('Y-m-d H:i:s', 'Y-m-d', $datetime);
    }
    
    /**
     * format datetime
     * 
     * @param string $formatFrom
     * @param string $formatTo
     * @param string $datetime
     * @return string
     */
    public static function formatDateTime($formatFrom, $formatTo, $datetime)
    {
        if (! $datetime || ! $formatFrom || ! $formatTo) {
            return;
        }
        $date = DateTime::createFromFormat($formatFrom, $datetime);
        if (! $date) {
            $date = strtotime($datetime);
            return date($formatTo, $date);
        }
        return $date->format($formatTo);
    }
    
    /**
     * check email allow of intranet
     * 
     * @param string $email
     * @return boolean
     */
    public static function isEmailAllow($email)
    {
        //add check email allow
        $domainAllow = Config::get('domain_logged');
        if ($domainAllow && count($domainAllow)) {
            foreach ($domainAllow as $value) {
                if (preg_match('/@' . $value . '$/', $email)) {
                    return true;
                }
            }
        }
        return false;
    }
}