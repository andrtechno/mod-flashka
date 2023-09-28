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

}
