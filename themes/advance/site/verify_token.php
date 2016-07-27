<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Verify Token';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <a href="<?=Yii::$app->request->baseUrl.'/?r=site/verify-token&code=' . $code?>">Accept confirm</a>
        <?=
        Html::a(Yii::t('app', 'Accept confirm'), ['verify-token', 'code' => $code], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <a href="<?=Yii::$app->request->baseUrl;?>">Home</a>
    </p>

</div>
