<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/4/15
 * Time: 12:06 PM
 */
use app\helpers\UserHelper;
use yii\helpers\Url;

?>
<div class="container padding-none">
    <div class="padding-none package">
        <h4 class="style-mg">MUA GÓI THUÊ BAO</h4>
        <p class="style-mg">SĐT: <b><?= UserHelper::getMsisdn() ?></b></p>
        <p class="style-mg"><b>Khuyến mãi 50% giảm giá....</b></p>
        <?php if (!empty($listGroupPackages)) {
            /* @var $group \app\models\listGroupPackages */
            foreach ($listGroupPackages->items as $group) {
                $isChange = 0;
                ?>
                <div class="p-all">
                    <div class="left-p">
                        <h4><img
                                src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-music.png"><?= mb_strtoupper($group->display_name, 'UTF-8') ?>
                        </h4> <span><?= ucfirst(strtolower($group->description)) ?></span>
                    </div>
                    <?php if (!empty($group->items)) {
                        /* @var $package \app\models\ServicePackage */
                        $usedPackage = 0;
                        $usedPackageName = '';
                        foreach ($group->items as $package) {
                            if ($package->is_my_package) {
                                $usedPackage = $package->id;
                                $usedPackageName = $package->display_name . '(' . $package->name . ')';
                            }
                            $class = '';
                            $typePackage = '';
                            if (7 == $package->period) {
                                $typePackage = 'Tuần';
                                $class = 'p-week';
                            } else if (1 == $package->period) {
                                $class = 'p-day';
                                $typePackage = 'Ngày';
                            } else if (30 == $package->period || 31 == $package->period
                                || 29 == $package->period || 28 == $package->period
                            ) {
                                $class = 'p-month';
                                $typePackage = 'Tháng';
                            } ?>

                            <div class="right-p"
                                 onclick="showPopup(<?= true == $package->is_my_package ? 1 : 0 ?>,'<?= $package->description ?>',<?= $package->id ?>, '<?= $package->display_name ?>',<?= $package->price ?>, $(this), <?= $group->id ?>);"
                                 data-toggle="modal">
                                <span><?= $package->price ?> <span>VNĐ</span></span><br>
                                <a class="buy"><?= $package->is_my_package == true ? 'Hủy' : 'Mua' ?></a>
                                <?php if ($package->is_my_package) { ?>
                                    <a class="status"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Đã
                                        mua</a>
                                <?php } ?>
                            </div>

                        <?php } ?>
                        <input type="hidden" id="used_package_<?= $group->id ?>" value="<?= $usedPackage ?>">
                        <input type="hidden" id="used_package_name_<?= $group->id ?>" value="<?= $usedPackageName ?>">

                    <?php } else { ?>
                        <span style="color: red">Không có gói cước</span>
                    <?php } ?>
                </div>

            <?php } ?>
            <input type="hidden" id="check" value="">
        <?php } else { ?>
            <span style="color: red">Không có gói cước</span>
        <?php } ?>

        <!-- Modal buy confirm purchase-->
        <div class="modal fade" id="buy-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-gift"
                                                                        aria-hidden="true"></span><b id="text-buy">MUA
                                GÓI</b></h4>
                    </div>
                    <div class="modal-body" id="msg"></div>
                    <div class="modal-footer">
                        <input type="hidden" name="pk_id" id="pk_id">
                        <input type="hidden" name="type" id="type">
                        <button type="button" id="no" class="btn btn-default" data-dismiss="modal">Không</button>
                        <button type="button" id="yes" class="btn btn-primary" onclick="acceptPurchase($(this));">Có
                        </button>
                        <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" id="id_check" name="id_check">
        <!-- end Modal buy confirm -->

        <!-- Modal message notice-->

        <div class="modal fade" id="notice-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 100px">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12"
                                                                        class="glyphicon glyphicon-bell"
                                                                        aria-hidden="true"></span> <b>THÔNG BÁO</b></h4>
                    </div>
                    <div class="modal-body" id="msg2"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="ok()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal message notice -->
    </div>
</div>
<input type="hidden" id="url_check"
       value="<?= mb_strtolower(urlencode("http://vplus.vinaphone.com.vn/user/packages")) ?>">
</div>
<script type="text/javascript">
    function ok() {
        location.reload();
    }
    function acceptPurchase(tag) {
        var group = $('#check').val();
        var type = parseInt($('#type').val());
        var packageId = parseInt($('#pk_id').val());
        var url_package = $('#url_check').val();
        var group_id = $('#id_check').val();
        if (1 == type) {//huy goivar

            var url = '<?= Url::toRoute(["user/cancel-package"])?>';
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    id: packageId,

                },
                crossDomain: true,
                dataType: "text",
                success: function (result) {
                    var data = JSON.parse(result);
                    window.location.assign(data['message']);
                    return;
                },
                error: function (result) {
                    $('#msg2').html('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                }
            });
        } else {//mua goi
            var used_package = $('#used_package_' + group).val();
            var used_package_name = $('#used_package_name_' + group).val();
            if (typeof  used_package == 'undefined') {//doi goi
                var package_id = $('#used_package_' + group_id).val();
//                alert(package_id);
                var url = '<?= Url::toRoute(["user/cancel-package"])?>';
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        id: package_id,

                    },
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var data = JSON.parse(result);
                        window.location.assign(data['message']);
                        return;
                    },
                    error: function (result) {
                        $('#msg2').html('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                    }
                });
            } else {//mua moi goi
                var url = '<?= Url::toRoute(["user/purchase-package"])?>';
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        id: packageId,

                    },
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var data = JSON.parse(result);
                        window.location.assign(data['message']);
                        return;
                    },
                    error: function (result) {
                        $('#msg2').html('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                    }
                });//end jQuery.ajax
                //shutdown model
                //tag.attr('data-dismiss','modal');
                //show notice model
                //$('#notice-a').click();
            }
        }


    }

    function showPopup(isMyPackage, msg, packageId, packageName, price, tagA, group) {
        var msisdn = '<?=UserHelper::getMsisdn()?>';
        if ('' == msisdn) {
            location.href = '/site/sso-login?return_url=/user/packages';
            return;
        }
        tagA.attr('data-target', '#buy-modal');
        if (1 == isMyPackage) {//huy goi
            $('#text-buy').html('HỦY GÓI');
            var msg = '' == msg ? 'Quý khách đang sử dụng gói ' + packageName + '. Bạn có đồng ý hủy gói cước?' : '';
            $('#msg').html(msg);
        } else {//mua goi
            $('#text-buy').html('MUA GÓI');
            var used_package = $('#used_package_' + group).val();
            var used_package_name = $('#used_package_name_' + group).val();
//            alert(used_package);
            if (used_package > 0) {
                $('#id_check').val(group);
                var url = '<?= Url::toRoute(["user/change-purchase-package"])?>';
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        id: packageId,

                    },
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var data = JSON.parse(result);
                        if (null != data && data['success']) {
                            $('#yes').html('Đồng ý');
                            $('#no').html('Không');
                            $('#msg').html(data['message']);
                        } else {
                            $('#yes').html('Đồng ý');
                            $('#no').html('Không');
                            $('#msg').html(data['message']);
                        }
                        return;
                    },
                    error: function (result) {
                        $('#msg2').html('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                    }
                });//end jQuery.ajax
                //shutdown model
                tag.attr('data-dismiss', 'modal');
                //show notice model
                $('#notice-a').click();
                var msg = '' == msg ? 'Quý khách đang sử dụng gói ' + used_package_name + '. Bạn có đồng ý đổi gói cước ? ' : '';
            } else {
                if (14 == packageId || 15 == packageId || 16 == packageId) {
                    var msg = '' == msg ? 'Xác nhận đăng ký truyền hình ' + packageName + '/' + price + 'Đ để được xem các kênh truyền hình đặc sắc nhất!' : '';
                } else {
                    var msg = 'Xác nhận đăng ký gói ' + packageName + '/' + price + 'Đ để được xem các nội dung cập nhật, đặc sắc nhất!';
                }
            }
            $('#msg').html(msg);
        }

        $('#pk_id').val(packageId);
        $('#type').val(isMyPackage);
        document.getElementById('check').value = group;
        //tagA.onclick();
    }
</script>