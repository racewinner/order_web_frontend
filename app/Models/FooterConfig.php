<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Traits\ModelTrait;

class FooterConfig extends Model
{
    use ModelTrait;

	protected $table            = 'epos_siteconfig_footer';
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
            $model = new FooterConfig();
            
            $row = $model->first();
            if(!empty($row)) {
                if(!empty($row['style'])) $row['style'] = json_decode($row['style'], true);
                if(!empty($row['logo_web'])) $row['logo_web'] = self::_treatMedia($row['logo_web']);
                if(!empty($row['logo_mobile'])) $row['logo_mobile'] = self::_treatMedia($row['logo_mobile']);
                if(!empty($row['column1'])) $row['column1'] = json_decode($row['column1'], true);
                if(!empty($row['column2'])) $row['column2'] = json_decode($row['column2'], true);
            }

            return $row;
        } catch(\Exception $e) {
            throw $e;
        }
    }

}