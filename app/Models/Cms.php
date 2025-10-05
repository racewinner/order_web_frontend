<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\Product;
use App\Models\Category;
use App\Traits\ModelTrait;

class Cms extends Model
{
    use ModelTrait;

    protected $table = 'epos_cms';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;

    private static function _treatCmsRow(&$row)
    {
        // To treat ribbon
        if (!empty($row['ribbon'])) {
            $ribbon = json_decode($row['ribbon'] ?? '', true);
            if (empty($ribbon['bg_color']))
                $ribbon['bg_color'] = 'transparent';
            if (empty($ribbon['txt_color']))
                $ribbon['txt_color'] = 'white';
            $row['ribbon'] = $ribbon;
        }

        // To treat custom data
        if(!empty($row['data'])) {
            $row['data'] = json_decode($row['data'] ?? '', true);
        }

        // To treat template, which are to be got with clause when fetching cms.
        if(!empty($row['background'])) {
            $row['template'] = [
                'background' => json_decode($row['background'], true),
            ];
        }
    }

    public static function _basicQuery($type, $date = null, $page_pos = null)
    {
        $model = new Cms();

        if (empty($date)) {
            $date = date('Y-m-d H:i:s');
        }

        $query = $model
            ->where('type', $type)
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('start_date<=', $date)
            ->where('end_date>=', $date);

        if (!empty($page_pos)) {
            $query->where('page_pos', $page_pos);
        }

        $query->orderBy('page_pos');

        $branch = session()->get('branch');
        if (!empty($branch)) {
            $query->groupStart()
                ->where("FIND_IN_SET($branch, branches)")
                ->orWhere('branches', 'all')
                ->groupEnd();
        }

        $organization = session()->get('organization');
        if (!empty($organization)) {
            $query->groupStart()
                ->where('organization_id', $organization)
                ->orWhere('organization_id', 0)
                ->orWhere('organization_id IS NULL', null, false) 
                ->groupEnd();
        }

        return $query;
    }

    public static function getHomeCmsByPagePosition($user_info, $priceList, $img_host, $controller, $date = null)
    {
        $model = new Cms();

        if (empty($date)) {
            $date = date('Y-m-d H:i:s');
        }

        $fields = [
            CmsType['home_banner'],
            CmsType['category_carousel'],
            CmsType['products_carousel'],
            CmsType['brochure'],
            CmsType['brand'],
            CmsType['bottom_banner'],
            CmsType['shop_by_category'],
        ];
        $query = $model
            ->where('active', 1)
            ->where('epos_cms.deleted', 0)
            ->where('start_date<=', $date)
            ->whereIn('type', $fields)
            ->where('end_date>=', $date);

        $branch = session()->get('branch');
        if (!empty($branch)) {
            $query->groupStart()
                ->where("FIND_IN_SET($branch, branches)")
                ->orWhere('branches', 'all')
                ->groupEnd();
        }

        $organization = session()->get('organization');
        if (!empty($organization)) {
            $query->groupStart()
                ->where('organization_id', $organization)
                ->orWhere('organization_id', 0)
                ->orWhere('organization_id IS NULL', null, false) 
                ->groupEnd();
        }
        $query->with([
            [
                'table' => 'epos_cms_templates',
                'local_field' => 'template_id',
                'remote_field' => 'id',
            ]
        ]);
        $query->select([
            'epos_cms.*',
            'epos_cms_templates.background'
        ]);

        $query->orderBy('page_pos');

        $rows = $query->findAll();

        $result = [];
        foreach ($rows as $row) {
            self::_treatCmsRow($row);

            $found = false;
            $type = $row['type'];
            
            switch($type) {
                case CmsType['home_banner']:
                case CmsType['category_carousel']:
                case CmsType['brochure']:
                case CmsType['brand']:
                case CmsType['bottom_banner']:
                    $items = CmsItem::getActiveItems($row['id'], $type, $user_info, $priceList);
                    if(empty($items)) break;
    
                    $row['items'] = $items;
                    $found = true;

                    break;

                case CmsType['products_carousel']:
                    if( empty($row['data']['prod_codes']) ) break;

                    // To get products
                    $products = [];
                    $prod_codes = explode(',', $row['data']['prod_codes']);
                    foreach ($prod_codes as $prod_code) {
                        $product = Product::getLowestPriceProductByCode($user_info, $prod_code);
                        if (!empty($product)) {
                            Product::populate($product, $priceList, $user_info, false);
                            $products[] = $product;
                        }
                    }
                    if(empty($products)) break;
    
                    $row['products'] = $products;
                    $found = true;

                    break;

                case CmsType['shop_by_category']:
                    $found = true;
                    break;
                }

            if($found) {
                $result[] = [
                    'type' => $type,
                    'data' => $row,
                ];
            }
        }

        return $result;
    }
    public static function getActiveSponsor($term, $date = null)
    {
        if (empty($term))
            return null;

        $query = Cms::_basicQuery('sponsor', $date);

        $rows = $query->findAll();

        foreach($rows as $row) {
            Cms::_treatCmsRow($row);
            if(empty($row['data']['terms'])) continue;
            
            $terms = explode(',', $row['data']['terms']);
            if(in_array($term, $terms)) return $row;
        }

        return null;
    }
    public static function getActiveTopRibbon($date = null)
    {
        $query = Cms::_basicQuery('top_ribbon', $date);
        $row = $query->first();
        if (!empty($row)) {
            self::_treatCmsRow($row);
        }

        return $row;
    }
    public static function getActiveCategoryBanners($category_id, $date=null) {
        if (empty($category_id)) return [];

        // To get category
        $category_model = new Category();
        $category = $category_model->find($category_id);

        // To get category_banners
        $query = Cms::_basicQuery('category_banner', $date);
        $rows = $query->findAll();
        if(empty($rows)) return [];

        $result = null;
        foreach ($rows as &$row) {
            self::_treatCmsRow($row);

            if (!empty($row['data']['sub_cat_id'])) {
                if ($row['data']['sub_cat_id'] != $category_id) continue;
            } else if (!empty($row['data']['top_cat_id'])) {
                if ($row['data']['top_cat_id'] != $category_id && $row['data']['top_cat_id'] != $category['parent_id']) continue;
            }

            $result['items'] = CmsItem::getActiveItems($row['id'], 'category_banner');
        }

        return $result;
    }

    public static function getActiveBrandBanners($brand, $date = null) {
        return null;
    }
}