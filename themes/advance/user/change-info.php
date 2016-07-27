<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/4/15
 * Time: 9:41 AM
 */
use app\helpers\CUtils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $user \app\models\User */
?>
<div class="container padding-none">
    <div class="info-user change-us">
        <h4>THAY ĐỔI THÔNG TIN</h4>
        <?php $form = ActiveForm::begin([
            'action'=> Url::toRoute(['user/change-info']),
            'method' =>'POST',
            'id' => 'edit-form',
        ]); ?>

            <?php if(isset($message) && is_array($message)) {?>
            <div >
                <?php if(isset($message['success']) && $message['success']) {?>
                    <span style="color: #008000">
                        <?=!empty($message['message']) ? $message['message'] : ''?>
                    </span>
                <?php } else if(isset($message['message']) && is_array($message['message'])){?>
                    <span style="color: red">
                        <?php foreach($message['message'] as $msg) {?>
                            <?=!empty($msg) ? $msg : ''?> <br>
                        <?php }?>
                    </span>
                <?php } else {?>
                    <span style="color: red">
                        <?=!empty($message['message']) ? $message['message'] : ''?>
                    </span>
                <?php }?>
            </div>
            <?php }?>

            <div class="form-group">
                <label for="usr">SĐT:<?=$user->msisdn ?></label><br>
            </div>
            <div class="form-group">
                <label for="usr">Họ và tên:</label>
                <input name="User[full_name]" type="text" class="form-control" id="usr" value="<?=$user->full_name ?>">
            </div>
            <div class="form-group" style="float: left">
                <b>Ngày</b> <?= Html::dropDownList('User[day]',preg_replace('/^0/','', $user->day), CUtils::listDay())?>
                <b>Tháng</b> <?= Html::dropDownList('User[month]',$user->month, CUtils::listMonth())?>
                <b>Năm</b> <?= Html::dropDownList('User[year]',!empty($user->year) ? $user->year : (date('Y') - 10), CUtils::listYear())?>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label for="usr">Email:</label>
                <input name="User[email]" type="text" class="form-control" id="usr" value="<?= $user->email?>">
            </div>
            <button class="change-info" type="submit" >Cập nhật</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>

