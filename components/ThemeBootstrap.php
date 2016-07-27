<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 12/12/14
 * Time: 9:08 AM
 */

namespace app\components;


use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

class ThemeBootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        // create an instance of Mobile_Detect class
        $detector = new \Mobile_Detect();
        //$detector = Yii::$app->mobileDetect;
        if ($detector->isMobile()) {
            if ($detector->isTablet() || $detector->is('iOS') || $detector->is('AndroidOS')) {
                $app->set('view', [
                    'class' => 'yii\web\View',
                    'theme' => [
                        'pathMap' => ['@app/views' => '@app/themes/advance'],
                        'baseUrl' => '@app/themes/advance',
                    ]
                ]);
            } else {
                if ($detector->mobileGrade() === 'A') {
                    $app->set('view', [
                        'class' => 'yii\web\View',
                        'theme' => [
                            'pathMap' => ['@app/views' => '@app/themes/advance'],
                            'baseUrl' => '@app/themes/advance',
                        ]
                    ]);
                } else {
                    $app->set('view', [
                        'class' => 'yii\web\View',
                        'theme' => [
                            'pathMap' => ['@app/views' => '@app/themes/basic'],
                            'baseUrl' => '@app/themes/basic',
                        ]
                    ]);
                }
            }
        }
    }
}