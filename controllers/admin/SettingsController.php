<?php

namespace panix\mod\flashka\controllers\admin;

use panix\engine\CMS;
use panix\mod\flashka\components\Flashka;
use panix\mod\flashka\components\ProductIdQueue;
use Yii;
use panix\engine\controllers\AdminController;
use panix\mod\flashka\models\SettingsForm;

class SettingsController extends AdminController
{

    public $icon = 'settings';

    public function actionIndex()
    {
        $this->pageName = Yii::t('app/default', 'SETTINGS');
        $this->view->params['breadcrumbs'] = [
            $this->pageName
        ];

        $this->buttons = [
            [
                'icon' => 'export',
                'label' => Yii::t('flashka/default', 'BUTTON_EXPORT'),
                'url' => ['export'],
                'options' => ['class' => 'btn btn-success']
            ]
        ];

        $model = new SettingsForm();
        if ($model->load(Yii::$app->request->post())) {
            //print_r(Yii::$app->request->post());die;
            if ($model->validate()) {
                $model->save();
                Yii::$app->session->setFlash("success", Yii::t('app/default', 'SUCCESS_UPDATE'));
            } else {
                foreach ($model->getErrors() as $error) {
                    Yii::$app->session->setFlash("error", $error);
                }
            }
            return $this->refresh();
        }
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionProducts()
    {
        $fs = new Flashka();
        $start = strtotime(Yii::$app->request->get('start'));
        $end = strtotime(Yii::$app->request->get('end'));
        $changes = $fs->getChanges2($start, $end);
        $products = $fs->getProducts($start, $end, ['with_descriptions' => 0]);

        foreach ($products as $product) {
            array_push($changes['product_ids'], $product['id']);
        }


        foreach (array_chunk($changes['product_ids'], 100) as $product_ids) {
            Yii::$app->queue->push(new ProductIdQueue([
                'product_ids' => $product_ids,
            ]));

        }


        $model = new SettingsForm();
        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * Экспорт всех поставщиков их контактов
     * @param string $delimiter default ";"
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionExport()
    {
        $flashkaClass = Yii::$app->getModule('flashka')->flashkaClass;
        $fs = new $flashkaClass;
        $suppliers = $fs->getSuppliers();

        foreach ($suppliers as $supplier) {
            $list[] = [$supplier['id'], $supplier['company'], $supplier['address'], $supplier['exchange_rate']];
        }
        asort($list);
        $path = Yii::getAlias('@runtime/') . 'suppliers_contact.csv';
        $fp = fopen($path, 'w');
        fputcsv($fp, ['ID', 'Название', 'Адрес', 'Курс'], ';');
        foreach ($list as $fields) {
            fputcsv($fp, $fields, ';');
        }
        fclose($fp);


        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, 'suppliers_contact.csv'); //->send()
            //return $this->redirect(['/admin/flashka/settings']);
        } else {
            throw new \yii\web\NotFoundHttpException("{$path} is not found!");
        }

    }

    public function actionUpdate($id)
    {
        $flashkaClass = Yii::$app->getModule('flashka')->flashkaClass;
        $fs = new $flashkaClass;
        $product = $fs->getProduct($id);
        if ($product) {
            $response = $product->execute();
            echo '<script>window.close();</script>';
        } else {
            echo 'no open product';
        }
    }
}
