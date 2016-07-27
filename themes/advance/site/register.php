<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/25/15
 * Time: 9:45 AM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['id' => 'contact-form',
    'action' => ['site/get-password'],
    'method'=> 'Post',
]); ?>
<span style="color: red"><?= isset($message) && !empty($message) ? $message : ''?></span>
<?= $form->field($model, 'phone_number') ?>
    <div class="form-group">
        <?= Html::submitButton('Nhận mật khẩu', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        <?=
        Html::a(Yii::t('app', 'Đăng nhập'), ['login'], [
            'class' => 'btn btn-danger',
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>