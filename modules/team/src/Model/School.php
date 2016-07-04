<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Exception;
use Rikkei\Core\View\View;

class School extends CoreModel
{
    
    use SoftDeletes;
    
    const PATH_MEDIA_COLLEGE = 'media/college';
    
    /**
     * rewrite save model
     * 
     * @param array $options
     */
    public static function saveItems($college = [], array $options = array()) {
        $image = [];
        foreach ($college as $key => $collegeData) {
            if (! $collegeData['name']) {
                continue;
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
            try {
                $school->save();
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }
}
