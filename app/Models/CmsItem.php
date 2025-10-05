<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\Product;
use App\Traits\ModelTrait;

class CmsItem extends Model
{
    use ModelTrait;

	protected $table            = 'epos_cms_items';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;

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

    private static function _treatCmsItemRow(&$row)
    {
        if (!empty($row['ribbon'])) {
            $ribbon = json_decode($row['ribbon'] ?? '', true);
            if (empty($ribbon['bg_color']))
                $ribbon['bg_color'] = 'transparent';
            if (empty($ribbon['txt_color']))
                $ribbon['txt_color'] = 'white';
            $row['ribbon'] = $ribbon;
        }

        if (!empty($row['media_web']))
            $row['media_web'] = self::_treatMedia($row['media_web']);
        if (!empty($row['media_mobile']))
            $row['media_mobile'] = self::_treatMedia($row['media_mobile']);

        // media url for mobile version
        $row['media_mobile_url'] = '';
        if(!empty($row['media_mobile']['url'])) {
            $row['media_mobile_url'] = $row['media_mobile']['url'];
        } else if(!empty($row['media_web']['url'])) {
            $row['media_mobile_url'] = $row['media_web']['url'];
        }

        // media url for pc version
        $row['media_web_url'] = '';
        if(!empty($row['media_web']['url'])) {
            $row['media_web_url'] = $row['media_web']['url'];
        } else if(!empty($row['media_mobile']['url'])) {
            $row['media_web_url'] = $row['media_mobile']['url'];
        }

        // To treat custom data
        if(!empty($row['data'])) {
            $row['data'] = json_decode($row['data'] ?? '', true);
        }

        switch ($row['type']) {
        }
    }

    private static function _hasNoMedia($row)
    {
        return empty($row['media_web']['url']) && empty($row['media_mobile']['url']);
    }

    private static function _basicQuery($cms_id) {
        $model = new CmsItem();
        $query = $model->where('cms_id', $cms_id)
            ->where('deleted != 1')
            ->where('active', 1);

        return $query;
    }

    public static function _getActiveItems($cms_id, $options=null) {
        $query = self::_basicQuery($cms_id);
        $rows = $query->findAll();

        $result = [];
        foreach ($rows as &$row) {
            self::_treatCmsItemRow($row);

            if(!empty($options)) {
                if (!empty($options['has_media']) &&  $options['has_media'] && !self::_hasNoMedia($row)) {
                    $result[] = $row;
                }
            }
        }

        return $result;
    }

    public static function getActiveProductsCarousels($cms_id, $user_info, $priceList) {
        $query = self::_basicQuery($cms_id);
        $rows = $query->findAll();

        foreach ($rows as &$row) {
            self::_treatCmsItemRow($row);

            // To get products
            $row['products'] = [];
            $prod_codes = explode(',', $row['prod_codes']);
            foreach ($prod_codes as $prod_code) {
                $product = Product::getLowestPriceProductByCode($user_info, $prod_code);
                if (!empty($product)) {
                    Product::populate($product, $priceList, $user_info, false);
                    $row['products'][] = $product;
                }
            }

            if (!empty($row['products']))
                $result[] = $row;
        }

        return $result;

    }

    public static function getActiveItems($cms_id, $type, $user_info = null, $priceList = null) {
        try {
            switch($type) {
                case CmsType['products_carousel']:
                    return self::getActiveProductsCarousels($cms_id, $user_info, $priceList);
                case CmsType['category_carousel']:
                case CmsType['brand']:
                case CmsType['brochure']:
                case CmsType['home_banner']:
                case CmsType['bottom_banner']:
                case CmsType['category_banner']:
                    return self::_getActiveItems($cms_id, ['has_media' => true]);
            }
        } catch(\Exception $e) {
            throw $e;
        }
    }
}