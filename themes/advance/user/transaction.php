<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/4/15
 * Time: 11:52 AM
 */
use app\helpers\UserHelper;
use yii\helpers\Url;

/* @var $transactions \app\models\ListTransactions */
/* @var $tran \app\models\Transaction */
?>
<div class="container padding-none">
    <div class="history">
        <h4 class="style-mg">LỊCH SỬ GIAO DỊCH</h4>

        <p class="style-mg">SĐT: <b><?= UserHelper::getMsisdn() ?></b></p>
        <table>
            <?php
            if (isset($transactions) && !empty($transactions)) {
                $i = 1; ?>
                <tr>
                    <th>STT</th>
                    <th>Giao dịch</th>
                    <th>Thời gian</th>
                </tr>
                <?php foreach ($transactions->items as $tran) { ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $tran->description.' có giá '.$tran->cost.' đ' ?></td>
                        <td><?= date('d/m/Y H:i:s', $tran->transaction_time) ?></td>
                    </tr>
                    <?php $i++;
                } ?>

                <?php
            } else { ?> <span style="color: red">Không có dữ liệu</span> <?php } ?>

            <tr id="last"></tr>
        </table>
        <?php $pagination = new \yii\data\Pagination(['totalCount'=> $transactions->_meta['totalCount'],'pageSize'=>$transactions->_meta['perPage']]) ?>
        <?php if(isset($pagination) && !empty($pagination)) {?>
            <div>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
                ?>
            </div>
        <?php }?>
    </div>

</div>
<script type="text/javascript">
    function readMore() {
        var url = '<?= Url::toRoute(['user/transactions'])?>';
        var page = parseInt($('#page').val()) + 1;
        $('#page').val(page);
        $.ajax({
            url: url,
            data: {
                'typeLoad': 1,
                'page': page,
                'per-page': <?=\app\helpers\Constants::_PER_PAGE_HOME?>
            },
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                if (null != result && '' != result) {
                    $(result).insertBefore($('#last'));
                    var pageCount = parseInt($('#pageCount').val());
                    if (page == pageCount) {
                        $('#readMore').css('display', 'none');
                    }
                }

            },
            error: function (result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
            }
        });//end jQuery.ajax

    }

</script>