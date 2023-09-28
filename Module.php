<?php

namespace panix\mod\flashka;

use panix\engine\CMS;
use Yii;
use panix\engine\WebModule;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class Module extends WebModule implements BootstrapInterface
{

    public $icon = 'supplier';
    public $onlySuppliers = [];
    public $flashkaClass = '\panix\mod\flashka\components\Flashka';
    public $excludeCategories = [];
    public $accessories_key = 'Accessories';
    public $bags_key = 'Bags';
    public $clothes_key = 'Clothes';
    public $sizeGroup = [
        '44-99' => '44 и более',
        '40-45' => '40-45',
        '38-43' => '38-43',
        '36-41' => '36-41',
        '31-37' => '31-37',
        '26-32' => '26-32',
        '20-26' => '20-26',
        '0-20' => 'до 20',
    ];

    public function bootstrap($app)
    {
        $app->urlManager->addRules(
            ['flashka/api/get_suppliers' => 'flashka/default/get-suppliers'],
            ['flashka/api/get_suppliers2' => 'flashka/default/get-supplier2s'],
            true
        );
    }

    public function getAdminMenu()
    {
        return [
            'modules' => [
                'items' => [
                    [
                        'label' => Yii::t($this->id.'/default', 'MODULE_NAME'),
                        'url' => '#',
                        'icon' => $this->icon,
                        'visible' => true,
                        'items' => [
                            [
                                'label' => Yii::t('shop/admin', 'SUPPLIER'),
                                'url' => ['/admin/flashka/default/suppliers'],
                                'icon' => 'supplier',
                                'visible' => true,
                            ],
                            [
                                'label' => Yii::t($this->id.'/default', 'CHANGES'),
                                'url' => ['/admin/flashka/default/changes'],
                                'icon' => 'shopcart',
                                'visible' => true,
                            ],
                            [
                                'label' => Yii::t('app/default', 'SETTINGS'),
                                'url' => ['/admin/flashka/settings'],
                                'icon' => 'settings',
                                'visible' => true,
                            ],

                        ]
                    ],

                ],
            ],
        ];
    }

    public function getAdminSidebar()
    {
        $menu = $this->getAdminMenu();
        return \yii\helpers\ArrayHelper::merge($menu['modules']['items'], $menu['modules']['items'][0]['items']);
    }

    public function getInfo()
    {
        return [
            'label' => Yii::t('flashka/default', 'MODULE_NAME'),
            'author' => $this->author,
            'version' => '1.0',
            'icon' => $this->icon,
            'description' => Yii::t('flashka/default', 'MODULE_DESC'),
            'url' => ['/admin/flashka'],
        ];
    }

}
