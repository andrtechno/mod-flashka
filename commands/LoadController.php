<?php

namespace panix\mod\flashka\commands;

use panix\mod\flashka\components\ProductByIdQueue;
use panix\mod\shop\models\AttributeOption;
use panix\mod\shop\models\Brand;
use panix\mod\shop\models\Category;
use panix\mod\shop\models\ProductAttributesEav;
use panix\mod\shop\models\ProductCategoryRef;
use panix\mod\shop\models\ProductImage;
use panix\mod\shop\models\Supplier;
use Yii;
use panix\mod\flashka\components\Flashka;
use panix\engine\CMS;
use panix\engine\console\controllers\ConsoleController;
use panix\mod\shop\models\Attribute;
use panix\mod\shop\models\Product;
use yii\base\ErrorException;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use yii\helpers\BaseFileHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\httpclient\Client;


ignore_user_abort(1);
set_time_limit(0);

/**
 * Class LoadController
 * @package panix\mod\flashka\commands
 */
class LoadController extends ConsoleController
{
    public $tempDirectory = '@runtime/flashka';
    /**
     * @var Flashka
     */
    private $fs;

    public function beforeAction($action)
    {
        if (!extension_loaded('intl')) {
            throw new ErrorException('PHP Extension intl not active.');
        }
        $flashkaClass = Yii::$app->getModule('flashka')->flashkaClass;
        $this->fs = new $flashkaClass;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Информация о товаре: "flashka/load/product <id>"
     * @param $id
     * @param $before_delete Delete before product
     * @return bool
     */
    public function actionProduct($id, $before_delete = 0)
    {
        if ($before_delete) {
            $p = Product::findOne(['external_id' => $id]);
            $p->delete();
        }
        $product = $this->fs->getProduct($id);
        //print_r($product->product);die;
        //print_r($product->getProductProps($product->product));die;
        if ($product) {
            $response = $product->execute();
        } else {
            echo 'no open product';
        }
        return $response;
    }

    public static function log($mssage)
    {
        Yii::info($mssage);
    }

    /**
     * Экспорт всех поставщиков их контактов
     * @param string $delimiter default ";"
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionExportContacts($delimiter = ';')
    {
        $suppliers = $this->fs->getSuppliers();

        foreach ($suppliers as $supplier) {
            $list[] = [$supplier['company'], str_replace('+', '', CMS::phoneFormat($supplier['phone'])), $supplier['phone'], $supplier['email'], $supplier['address']];
        }
        asort($list);
        $fp = fopen(Yii::getAlias('@runtime/') . 'suppliers_contact.csv', 'w');
        fputcsv($fp, ['Имя', 'Телефон', 'Телефон формат', 'E-mail', 'Адрес'], $delimiter);
        foreach ($list as $fields) {
            fputcsv($fp, $fields, $delimiter);
        }
        fclose($fp);
    }




    public function actionDiffSupplier()
    {

        $suppliers = $this->fs->getSuppliers();
        $cur_suppliers = Supplier::find()->where('`external_id` IS NOT NULL')->all();

        $forsage_list = [];
        $cur_forsage_list = [];
        foreach ($suppliers['suppliers'] as $supplier) {

            $forsage_list[] = $supplier['id'];
        }

        foreach ($cur_suppliers as $supplier) {
            if ($supplier->external_id) {
                $cur_forsage_list[] = $supplier->external_id;
            }
        }

        //$res1 = array_intersect($forsage_list, $cur_forsage_list);
        $res2 = array_diff($cur_forsage_list, $forsage_list);

        foreach ($res2 as $supplier_id) {
            $sup = Supplier::findOne(['external_id' => $supplier_id]);
            if ($sup) {
                $products = Product::findAll(['supplier_id' => $sup->id]);
                foreach ($products as $product) {
                    $product->delete();
                }
                $sup->delete();
            }

        }
    }

}
