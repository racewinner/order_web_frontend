<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Traits\ModelTrait;

class TopRibbonConfig extends Model
{
    use ModelTrait;

	protected $table            = 'epos_siteconfig_topribbon';
    protected $primaryKey       = 'id';

    private static function _treatMedia($media)
    {
        $jsonData = json_decode($media ?? '', true);
        if ($jsonData['active_link'] == 'external' && !empty($jsonData['external_link'])) {
            $jsonData['url'] = $jsonData['external_link'];
        }

        if ($jsonData['active_link'] == 'upload' && !empty($jsonData['upload_file'])) {
            $jsonData['url'] = env('app.uploads_baseurl') . "/" . $jsonData['upload_file'];
        }

        return $jsonData;
    }

    public static function getConfig() {
        try {
            $model = new TopRibbonConfig();
            
            $row = $model->first();
            if(!empty($row)) {
                if(!empty($row['style'])) $row['style'] = json_decode($row['style'], true);
            }

            return $row;
        } catch(\Exception $e) {
            throw $e;
        }
    }

}