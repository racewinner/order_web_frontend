<?php
namespace App\Models;
use CodeIgniter\Model;
use function PHPUnit\Framework\isEmpty;

class Category extends Model
{
    protected $table = 'epos_categories';
    protected $primaryKey = 'category_id';
    protected $useTimestamps = true;

    private static function _treatMedia($media)
    {
        $jsonData = json_decode($media ?? '', true);

        if (empty($jsonData['active_link']))
            return null;

        if ($jsonData['active_link'] == 'external' && !empty($jsonData['external_link'])) {
            $jsonData['url'] = $jsonData['external_link'];
        }
        if ($jsonData['active_link'] == 'upload' && !empty($jsonData['upload_file'])) {
            $jsonData['url'] = env('app.uploads_baseurl') . "/" . $jsonData['upload_file'];
        }
        return $jsonData;
    }

    private static function _treatCategory(&$row)
    {
        if (!empty($row['logo_web']))
            $row['logo_web'] = Category::_treatMedia($row['logo_web']);
        if (!empty($row['logo_mobile']))
            $row['logo_mobile'] = Category::_treatMedia($row['logo_mobile']);
    }

    public static function findOne($id)
    {
        try {
            $model = new Category();

            $category = $model->find($id);
            if (empty($category))
                return null;

            Category::_treatCategory($category);

            return $category;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getCategoryTree($branch) {
        try {
            $model = new Category();

            // To get all categories
            $model->where('display', 1);

            if(!empty($branch)) {
                $model->where("FIND_IN_SET($branch, branches)");
            }

            $model->orderBy('sequence', 'asc');

            $categories = $model->findAll();

            // To get top categories
            $top_categories = array_filter($categories, function($category) {
                return (empty($category['parent_id']) && isset($category['alias']) && $category['alias'] != '') ? true : false;
            });

            // To get sub_categories for all top_categories
            foreach ($top_categories as &$top_category) {
                $top_category['sub_categories'] = array_filter($categories, function($category) use($top_category) {
                    if($category['parent_id'] == '135') {
                        $i =0;
                    }
                    return $category['parent_id'] == $top_category['category_id'] ? true : false;
                });
            }

            return $top_categories;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}