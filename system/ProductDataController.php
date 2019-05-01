<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 9000);
require_once "system/CategoryDataController.php";
/**
 * Created by PhpStorm.
 * User: andrii
 * Date: 07.04.19
 * Time: 16:42
 */

require_once "./model/product.php";

class ProductDataController
{
    protected $productModel;
    protected $categoryModel;
    protected $marketModel;
    protected $productsUpdate;
    protected $productsAdd;
    protected $shops;
    protected $category;
    protected $categoryList;

    public function __construct()
    {

        $this->category = new CategoryDataController();
        $this->setCategoryList();
        echo "<pre>";
        print_r("fuck 3");
        echo "</pre>";
    }
    public function modelInit(AbstractDbConnection $dbConnection)
    {
        $this->productModel = new ProductModel();
        $this->productModel->setConnection($dbConnection);
    }

    public function productProcessing(AbstractParser $parser)
    {
        $parser->parseProductsFromCode();
        $this->productDistribution($parser->getProductsData());
    }

    public function getAllProduct()
    {
        return $this->productModel->getAllProduct();
    }

    public function productDistribution($products)
    {
        foreach ($products as $product)
        {
            $this->addProduct($product);
        }
    }

    protected function addProduct($product)
    {
        $product = $this->addCategoryToProduct($product);
        $this->productModel->addProduct($product);
    }

    /**
     * @param mixed $categoryList
     */
    public function setCategoryList()
    {
        $this->categoryList = $this->category->getCategories();
    }

    private function findCategoryByKeywords($name)
    {
        $categories = [];
        foreach ($this->categoryList as $category){
//echo "<pre>";
//print_r($category);
//echo "</pre>";
            foreach (explode(', ', $category['keywords']) as $word){
                if (stristr($name, $word))
                    $categories[] = $category['category_id'];
            }
        }

        if ($categories) {
            echo "<pre>";
            print_r($categories);
            echo "</pre>";
        }
    }
    private function addCategoryToProduct($product)
    {
        $product['categories'] = $this->findCategoryByKeywords($product['title']);

        return $product;
    }

}