<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Exception;
use Rikkei\Core\View\View;
use Rikkei\Core\View\CacheHelper;
use Illuminate\Support\Facades\URL;

class School extends CoreModel
{
    
    use SoftDeletes;
    
    const PATH_MEDIA_COLLEGE = 'media/college';
    const KEY_CACHE = 'school_college';

    protected $table = 'schools';

    /**
     * save model school
     * 
     * @param array $college
     * @param array $options
     * @return array
     * @throws Exception
     */
    public static function saveItems($college = [], array $options = array()) 
    {
        if (! $college) {
            return;
        }
        $schoolIds = [];
        try {
            foreach ($college as $key => $collegeData) {
                if (! $collegeData['name']) {
                    continue;
                }
                if (isset($collegeData['id']) && $collegeData['id']) {
                    if (School::find($collegeData['id'])) {
                        $schoolIds[$key] = $collegeData['id'];
                        continue;
                    }
                    unset($collegeData['id']);
                }
                $school = new self();
                if (isset($collegeData['image']) && $collegeData['image']) {
                    $image = $collegeData['image'];
                    $image = View::uploadFile(
                        $image, 
                        public_path(self::PATH_MEDIA_COLLEGE), 
                        Config::get('services.image_allow')
                    );
                    if ($image) {
                        $school->image = self::PATH_MEDIA_COLLEGE . '/' . $image;
                    }
                    unset($collegeData['image']);
                }
                $school->setData($collegeData);
                $school->save($options);
                $schoolIds[$key] = $school->id;
            }
            CacheHelper::forget(self::KEY_CACHE);
            return $schoolIds;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * get all school format json
     * 
     * @return string json
     */
    public static function getAllFormatJson()
    {
        if ($schools = CacheHelper::get(self::KEY_CACHE)) {
            return $schools;
        }
        $schools = self::select('id', 'name', 'country', 'province', 'image')
            ->orderBy('name')->get();
        if (! count($schools)) {
            return null;
        }
        $result = '[';
        foreach ($schools as $school) {
            $result .= '{';
            $result .= 'id: "' . $school->id . '",';
            $result .= 'label: "' . $school->name . '",';
            $result .= 'country: "' . $school->country . '",';
            $result .= 'province: "' . $school->province . '",';
            if ($school->image) {
                $result .= 'image: "' . URL::asset($school->image) . '",';
            } else {
                $result .= 'image: "",';
            }
            $result .= '},';
        }
        $result .= ']';
        CacheHelper::put(self::KEY_CACHE, $result);
        return $result;
    }
}
