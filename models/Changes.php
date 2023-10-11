<?php

namespace panix\mod\flashka\models;

use Yii;
use panix\engine\db\ActiveRecord;


/**
 * Class Kit
 * @property integer $id
 * @property integer $owner_id
 * @property integer $product_id
 */
class Changes extends ActiveRecord
{

    const MODULE_ID = 'shop';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%flashka_changes}}';
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'product_id'], 'required'],
            [['product_id', 'owner_id'], 'integer'],
        ];
    }
}
