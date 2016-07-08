<?php

namespace Rikkei\Core\View;

use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

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
     * @param srting $path name path after storage/app/
     * @param array $allowType
     * @param boolean $rename
     * @return string|null
     * @throws Exception
     */
    public static function uploadFile($file, $path, $allowType = [], $maxSize = null, $rename = true)
    {
        if ($file->isValid()) {
            if ($allowType) {
                $extension = $file->getClientMimeType();
                if (! in_array($extension, $allowType)) {
                    throw new Exception(Lang::get('core::message.File type dont allow'));
                }
            }
            if ($maxSize) {
                $fileSize = $file->getClientSize();
                if ($fileSize / 1000 > $maxSize) {
                    throw new Exception(Lang::get('core::message.File size is large'));
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
            Storage::put(
                $path . '/' . $fileName,
                file_get_contents($file->getRealPath())
            );
            return $fileName;
        }
        return null;
    }
    
    /**
     * get link image file
     * 
     * @param string|null $path
     * @param boolean $useDefault
     * @return string|null
     */
    public static function getLinkImage($path = null, $useDefault = true)
    {
        if (! $path) {
            if ($useDefault) {
                return URL::asset('common/images/noimage.png');
            }
            return null;
        }
        if (preg_match('/^http(s)?:\/\//', $path)) {
            return $path;
        }
        if (file_exists(public_path($path))) {
            return URL::asset($path);
        }
        if ($useDefault) {
            return URL::asset('common/images/noimage.png');
        }
        return null;
    }
    
    /**
     * get language level
     * 
     * @return array
     */
    public static function getLanguageLevel()
    {
        return Config::get('general.language_level');
    }
    
    /**
     * get format json language level
     * 
     * @return string json
     */
    public static function getLanguageLevelFormatJson()
    {
        return \GuzzleHttp\json_encode(self::getLanguageLevel());
    }
    
    /**
     * to option language level
     * 
     * @param type $nullable
     * @return type
     */
    public static function toOptionLanguageLevel($nullable = true)
    {
        $options = [];
        if ($nullable) {
            $options[] = [
                'value' => '',
                'label' => Lang::get('core::view.-- Please choose --'),
            ];
        }
        $level = self::getLanguageLevel();
        if (! $level) {
            return $options;
        }
        foreach ($level as $key => $item) {
            if (! $key) {
                continue;
            }
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }
        return $options;
    }
    
    /**
     * get label of level language
     * 
     * @param type $key
     * @return type
     */
    public static function getLabelLanguageLevel($key)
    {
        $level = self::getLanguageLevel();
        if (! $level || ! isset($level[$key]) || ! $level[$key]) {
            return;
        }
        return $level[$key];
    }
    
    /**
     * get normal level
     * 
     * @return array
     */
    public static function getNormalLevel()
    {
        return Config::get('general.normal_level');
    }
    
    /**
     * to option normal level
     * 
     * @param type $nullable
     * @return type
     */
    public static function toOptionNormalLevel($nullable = true)
    {
        $options = [];
        if ($nullable) {
            $options[] = [
                'value' => '',
                'label' => Lang::get('core::view.-- Please choose --'),
            ];
        }
        $level = self::getNormalLevel();
        if (! $level) {
            return $options;
        }
        foreach ($level as $key => $item) {
            if (! $key) {
                continue;
            }
            $options[] = [
                'value' => $key,
                'label' => $item,
            ];
        }
        return $options;
    }
    
    /**
     * get label of level normal
     * 
     * @param type $key
     * @return type
     */
    public static function getLabelNormalLevel($key)
    {
        $level = self::getNormalLevel();
        if (! $level || ! isset($level[$key]) || ! $level[$key]) {
            return;
        }
        return $level[$key];
    }
    
    /**
     * get format json normal level
     * 
     * @return string json
     */
    public static function getNormalLevelFormatJson()
    {
        return \GuzzleHttp\json_encode(self::getNormalLevel());
    }
}