<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/4/15
 * Time: 9:34 AM
 */
use yii\helpers\Url;

/* @var $user \app\models\User */
?>
<?php if(null != $user) {?>
<div class="container padding-none">
    <div class="info-user">
        <h4>THÔNG TIN CÁ NHÂN</h4>
        <p>
            <span>SĐT:</span> <?= $user->msisdn?><br/>
            <span>Họ và tên:</span> <?= $user->full_name?><br/>
            <span>Ngày sinh:</span> <?= !empty($user->birthday) ? date('d/m/Y', $user->birthday) : ''?><br/>
            <span>Email:</span> <?= $user->email?><br/>
        </p>
        <a class="change-info" href="<?=Url::toRoute(['user/change-info'])?>">Thay đổi thông tin</a>
    </div>
</div>
<?php }?>