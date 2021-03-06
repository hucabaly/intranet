<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Exception;
use Rikkei\Core\View\View;
use Rikkei\Core\View\CacheHelper;
use Illuminate\Support\Facades\Validator;

class School extends CoreModel
{
    
    use SoftDeletes;
    
    const PATH_MEDIA_COLLEGE = 'media/school';
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
    public static function saveItems($schools = []) 
    {
        if (! $schools) {
            return;
        }
        $schoolIds = [];
        try {
            foreach ($schools as $key => $schoolData) {
                if (! isset($schoolData['school']) || ! $schoolData['school']) {
                    continue;
                }
                $schoolData = $schoolData['school'];
                if (isset($schoolData['id']) && $schoolData['id']) {
                    if ( $school = self::find($schoolData['id'])) {
                        $schoolIds[$key] = $schoolData['id'];
                    } else {
                        continue;
                    }
                    unset($schoolData['id']);
                } else {
                    $school = new self();
                }
                $validator = Validator::make($schoolData, [
                    'name' => 'required|max:255',
                    'country' => 'required|max:255',
                    'province' => 'required|max:255',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->send();
                }
                if (isset($schoolData['image_path']) && $schoolData['image_path']) {
                        $school->image = $schoolData['image_path'];
                }
                unset($schoolData['image_path']);
                unset($schoolData['image']);
                $school->setData($schoolData);
                $school->save();
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
            return '[]';
        }
        $result = '[';
        foreach ($schools as $school) {
            $result .= '{';
            $result .= 'id: "' . $school->id . '",';
            $result .= 'label: "' . $school->name . '",';
            $result .= 'country: "' . $school->country . '",';
            $result .= 'province: "' . $school->province . '",';
            $result .= 'image: "' . View::getLinkImage($school->image) . '",';
            $result .= '},';
        }
        $result .= ']';
        CacheHelper::put(self::KEY_CACHE, $result);
        return $result;
    }
}
