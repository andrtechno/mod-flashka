<?php

namespace panix\mod\flashka\controllers;

use Yii;
use yii\web\Controller;
use panix\mod\flashka\components\Flashka;
use panix\mod\flashka\components\ProductByIdQueue;
use yii\web\BadRequestHttpException;

class DefaultController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->settings->get('forsage', 'hook_key') != Yii::$app->request->get('hook')) {
            Yii::info('ERROR HOOK KEY', 'flashka');
            throw new BadRequestHttpException('Error');
        }
        return parent::beforeAction($action);
    }

    public function actionWebhook()
    {
        $forsageClass = Yii::$app->getModule('flashka')->flashkaClass;
        $fs = new $forsageClass;
        $input = json_decode($fs->input, true);

        if ($input) {
            if (isset($input['product_ids'])) {
                Yii::info('push: ' . implode(',', $input['product_ids']), 'flashka');
                foreach ($input['product_ids'] as $product_id) {
                    Yii::$app->queue->push(new ProductByIdQueue([
                        'id' => $product_id,
                    ]));
                }
            }
        } else {
            Yii::info('Error input', 'flashka');
        }
    }


    public function actionGetProduct($id)
    {
        $result['success'] = 'true';
        $chrs = [
            'accept' => true,
            'box' => '8',
            'brand_name' => 'Slipers',
            "category" => 1,
            "category_name" => "Обувь",
            "colour" => "Белый",
            "cost" => "85,0",
            "cost_doll" => "",
            "cost_euro" => "",
            "cost_site" => "92,0",
            "cost_site_doll" => "",
            "cost_site_euro" => "",
            "cost_site_uah" => "92,0",
            "cost_uah" => "85,0",
            "currency" => "грн",
            "date" => "02-09-2018",
            "descreption" => "",
            "empty" => "",
            "group" => "сапоги",
            "manufacturer" => "Украина",
            "material_inside" => "искусственный мех",
            "material_insole" => "искусственный мех",
            "material_outside" => "пена",
            "min_order" => "8",
            "presence" => "нет", //наличие вроде как!!!
            "provider_name" => "Slipers",
            "real_group" => "сапоги",
            "repeat" => "",
            "season" => "зима",
            "sex" => "мальчик",
            "size" => "28-35",
            "thumbnail" => "http://fleshka.od.ua/thumbnails/128029.jpg",
            "type" => "детская"
        ];
        $product = [
            'vcode' => 'Сапоги пена детск пиксель черн',
            'name' => 'Сапоги Slipers',
            'id' => $id,
            'characteristics' => $chrs,
            'photos' => ['URL'], //ПОД ВОПРОСОМ
        ];

        $result['products'] = $product;

        return $this->asJson($result);
    }

    public function actionGetSuppliers()
    {
        $result['success'] = 'true';
        $list[] = [
            'address' => 'Голубая 1614',
            'company' => 'AAPR',
            'exchange_rate' => 0.0,
            'id' => 173
        ];
        $list[] = [
            'address' => 'Белая 1087',
            'company' => 'ABA',
            'exchange_rate' => 0.0,
            'id' => 156
        ];
        $result['suppliers'] = $list;
        return $this->asJson($result);
    }

    public function actionGetBrands()
    {
        $result['success'] = 'true';
        $result['brands'] = [
            "",
            "4-EVA",
            "4Rest",
            "7S7",
            "Aa Boxing",
            "aapr",
            "Aapr",
            "AAPR",
            "Aba",
            "ABA"
        ];
        return $this->asJson($result);
    }

    public function actionGetProductsBySupplier($id)
    {
        $result['success'] = 'true';
        $result['product_ids'] = [
            275828,
            279905,
            279906
        ];
        return $this->asJson($result);
    }

    public function actionGetProducts()
    {
        $result['success'] = 'true';
        return $this->asJson($result);
    }

    public function actionGetChanges()
    {
        $result['success'] = 'true';
        $changes[] = [
            "datetime" => "18-11-2019 08:29",
            "id" => 71917,
            "news_type" => "Изменение цены товара",
            "news_type_int" => 3,
            "product_ids" => [
                300189,
                300190
            ],
            "products" => [
                [
                    "cost" => "400,0",
                    "cost_site" => "420,0",
                    "currency" => "грн",
                    "id" => 300189,
                    "old_cost" => 430.0,
                    "old_cost_site" => 450.0,
                    "old_currency" => "грн"
                ],
                [
                    "cost" => "400,0",
                    "cost_site" => "420,0",
                    "currency" => "грн",
                    "id" => 300190,
                    "old_cost" => 430.0,
                    "old_cost_site" => 450.0,
                    "old_currency" => "грн"
                ]
            ],
            "provider_address" => "Голубая 1487",
            "provider_name" => "ADA"

        ];

        $changes[] = [
            "datetime" => "17-11-2019 06:55",
            "id" => 71698,
            "news_type" => "Изменение цены товара",
            "news_type_int" => 3,
            "product_ids" => [
                299559,
                299562
            ],
            "products" => [
                [
                    "cost" => "350,0",
                    "cost_site" => "370,0",
                    "currency" => "грн",
                    "id" => 299559,
                    "old_cost" => 400.0,
                    "old_cost_site" => 420.0,
                    "old_currency" => "грн"
                ],
                [
                    "cost" => "350,0",
                    "cost_site" => "370,0",
                    "currency" => "грн",
                    "id" => 299562,
                    "old_cost" => 400.0,
                    "old_cost_site" => 420.0,
                    "old_currency" => "грн"
                ]
            ],
            "provider_address" => "Голубая 1487",
            "provider_name" => "ADA"

        ];

        $result['news'] = $changes;
        return $this->asJson($result);
    }
}
