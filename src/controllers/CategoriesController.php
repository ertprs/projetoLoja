<?php
namespace src\controllers;

use \core\Controller;
use src\handlers\Store;
use src\models\Categories;
use \src\models\Products;
use \src\models\Filters;

class CategoriesController extends Controller
{
    public function index($atts)
    {
        $store = new Store();
        $products = new Products();
        $categories = new Categories();
        $f = new Filters();

        $data = $store->getTemplateData();

        $data['category_name'] = $categories->getCategoryName($atts['id']);

        if(!empty($data['category_name'])) {
            $currentPage = 1;
            $offset = 0;
            $limit = 12;

            if(!empty($_GET['page'])) {
                $currentPage = $_GET['page'];
            }

            $offset = ($currentPage * $limit) - $limit;

            $filters = [];
            if(!empty($_GET['filter']) && is_array($_GET['filter'])) {
                $filters = $_GET['filter'];
            }
            $filters['category'] = $atts['id'];

            $data['category_filter'] = $categories->getCategoryTree($atts['id']);

            $data['list'] = $products->getList($offset, $limit, $filters);
            $data['total_items'] = $products->getTotal($filters);
            $data['number_of_pages'] = ceil($data['total_items'] / $limit);
            $data['current_page'] = $currentPage;

            $data['id_category'] = $atts['id'];

            $data['filters'] = $f->getFilters($filters);
            $data['filters_selected'] = $filters;

            $data['searchTerm'] = '';
            $data['category'] = '';

            $data['sidebar'] = true;


            $this->render('categories', $data);
        } else {
            $this->redirect('/');
            exit;
        }
    }
}