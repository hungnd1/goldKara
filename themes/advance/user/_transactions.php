<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/9/15
 * Time: 5:35 PM
 */
use app\helpers\Constants;

/* @var $transactions \app\models\ListTransactions */
/* @var $tran \app\models\Transaction */
?>

<?php if(null != $transactions) {
    $i = ($transactions->_meta['currentPage'] - 1) * Constants::_PER_PAGE_HOME + 1;
    foreach($transactions->items as $tran) {?>
        <tr>
            <td><?=$i?></td>
            <td><?=$tran->description?></td>
            <td><?=date('d/m/Y H:i:s', $tran->transaction_time)?></td>
        </tr>
    <?php $i++; }?>
<?php }?>