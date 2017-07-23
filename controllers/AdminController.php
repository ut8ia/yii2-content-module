<?php

namespace ut8ia\contentmodule\controllers;

use Yii;
use ut8ia\contentmodule\models\Content;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class AdminController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['importall'],
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['importall'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ]
            ]
        ];
    }


    /**
     * @return string
     */
    public function actionImportall()
    {
        $this->module->importImagesAllContent();
        return "import all images";
    }


}
