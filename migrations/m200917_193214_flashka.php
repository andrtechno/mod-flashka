<?php

/**
 * Generation migrate by PIXELION CMS
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * Class m170908_104527_forsage_studio
 */

use panix\engine\db\Migration;
use panix\mod\shop\models\Product;
use panix\mod\shop\models\Supplier;
use panix\mod\shop\models\Brand;
use panix\mod\shop\models\Attribute;
use panix\mod\shop\models\AttributeOption;
use panix\mod\shop\models\ProductImage;
use panix\mod\shop\models\Category;

class m200917_193214_flashka extends Migration
{

    public $settingsForm = 'panix\mod\flashka\models\SettingsForm';

    public function up()
    {
        $this->addColumn(Product::tableName(), 'external_id', $this->integer()->null());
        //$this->addColumn(Product::tableName(), 'ukraine', $this->boolean()->defaultValue(0));
        //$this->addColumn(Product::tableName(), 'leather', $this->boolean()->defaultValue(0));
        $this->addColumn(Supplier::tableName(), 'external_id', $this->integer()->null());
        $this->addColumn(Brand::tableName(), 'external_id', $this->integer()->null());
        $this->addColumn(Attribute::tableName(), 'external_id', $this->integer()->null());
        $this->addColumn(ProductImage::tableName(), 'external_id', $this->integer()->null());
        $this->addColumn(Category::tableName(), 'path_hash', $this->string(32)->null());

        //$this->createIndex('external_id', Product::tableName(), 'external_id');
        //$this->createIndex('external_id', Supplier::tableName(), 'external_id');
        //$this->createIndex('external_id', Attribute::tableName(), 'external_id');
        //$this->createIndex('external_id', Brand::tableName(), 'external_id');
        $this->loadSettings();
    }

    public function down()
    {

        $this->dropColumn(Product::tableName(), 'external_id');
        //$this->dropColumn(Product::tableName(), 'ukraine');
        //$this->dropColumn(Product::tableName(), 'leather');
        $this->dropColumn(Supplier::tableName(), 'external_id');
        $this->dropColumn(Brand::tableName(), 'external_id');
        $this->dropColumn(Attribute::tableName(), 'external_id');
        $this->dropColumn(ProductImage::tableName(), 'external_id');
        $this->dropColumn(Category::tableName(), 'path_hash');
        if (Yii::$app->get('settings')) {
            Yii::$app->settings->clear('flashka');
        }
    }

}
