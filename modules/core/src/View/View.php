<?php

namespace Rikkei\Core\View;

use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Exception;
use Illuminate\Support\Facades\Lang;

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
    
    /**
     * check email is root
     * 
     * @return boolean
     */
    public static function isRoot($email)
    {
        if (trim(Config('services.account_root')) == $email) {
            return true;
        }
        return false;
    }
    
    /**
     * show permission view
     */
    public static function viewErrorPermission()
    {
        echo view('errors.permission');
        exit;
    }
    
    /**
     * route to option
     * 
     * @return array
     */
    public static function routeListToOption()
    {
        $routeCollection = Route::getRoutes();
        $option = [];
        foreach ($routeCollection as $value) {
            if (preg_match('/[{}?]/', $value->getPath())) {
                continue;
            }
            $option[] = [
               'value' => $value->getPath(),
                'label' => $value->getPath(),
            ];
        }
        return $option;
    }
    
    /**
     * get no. starter from grid data
     */
    public static function getNoStartGrid($collectionModel)
    {
        if (! $collectionModel->total()) {
            return 1;
        }
        $currentPage = $collectionModel->currentPage();
        $perPage = $collectionModel->perPage();
        return ($currentPage - 1) * $perPage + 1;
    }
    
    /**
     * upload file
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param srting $path
     * @param array $allowType
     * @param boolean $rename
     * @return string|null
     * @throws Exception
     */
    public static function uploadFile($file, $path, $allowType = [], $rename = true)
    {
        if ($file->isValid()) {
            if ($allowType) {
                $extension = $file->getClientMimeType();
                if (! in_array($extension, $allowType)) {
                    throw new Exception(Lang::get('core::message.File type dont allow'));
                }
            }
            if ($rename) {
                $extension = $file->getClientOriginalExtension();
                $fileNameWithoutExtension = preg_replace(
                    "/.{$extension}$/",
                    '',
                    $file->getClientOriginalName()
                );
                $fileName = $fileNameWithoutExtension . '_' . time() . '.' . $extension;
            } else {
                $fileName = $file->getClientOriginalName();
            }
            $file->move($path, $fileName);
            return $fileName;
        }
        return null;
    }
}