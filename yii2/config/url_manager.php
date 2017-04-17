<?php
/**
 * Created by PhpStorm.
 * User: Joldyzzz
 * Date: 16.04.2017
 * Time: 23:23
 */

return [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class'   => 'yii\web\UrlRule',
                    'pattern' => 'fast-registration-widget/<action:(check-fast-input)>',
                    'route'   => 'site/<action>',
                    'verb'    => 'POST'
                ],
            ],
        ],
    ],
];