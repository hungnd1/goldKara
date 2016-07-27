<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 10/30/14
 * Time: 4:44 PM
 */
use app\helpers\CUtils;

$strKeyword = '';
if(isset($keyword)) {
    $strKeyword = CUtils::replace_string_injection($keyword);
}
?>

<input type="hidden" name="user_id" id="user_id" value="<?= isset($user_id) ? $user_id : 0;?>" />
<input type="hidden" name="id" id="id" value="<?= isset($id) ? $id : 0;?>" />
<input type="hidden" name="<?=$pageName?>" id="<?=$pageName?>" value="<?= isset($page) ? $page : 0;?>" />
<input type="hidden" name="<?=$pageCountName?>" id="<?=$pageCountName?>" value="<?= isset($pageCount) ? $pageCount : 0;?>" />
<div id="<?=$pager_id?>" style="clear: both; text-align: center;">
    <a class="more" href="javascript:void(0);" onclick="javascript:showMore<?=$pager_id?>('<?= Yii::$app->request->baseUrl?>', '<?= $strKeyword ?>');">Xem thêm</a>
</div>
<div id="<?=$pager_id?>" style="text-align: center;width: 100%;margin-top: 10px;float: left; cursor: pointer;" onclick="javascript:showMore<?=$pager_id?>('<?= Yii::$app->request->baseUrl?>', '<?= $strKeyword ?>');">
    <span style="color: #0481A3;font-weight: bold;border: 1px solid #999;background-color: #E7F2DC;width: 200px;border-radius: 4px;padding: 5px 38px;font-size: 13px;text-align: center;">Xem Thêm</span>
</div>


<script type="text/javascript">
    function showMore<?=$pager_id?>(host, keyword) {
        var page = parseInt($('#' + '<?=$pageName?>').val()) + 1;
        var user_id = parseInt($('#user_id').val());
        var id = parseInt($('#id').val());
        $('#' + '<?=$pageName?>').val(page);
        var url = host + "/index.php?r=<?=$url_action?>";
        url += "&page=" + page  + "&ajax=1";
        if(user_id > 0) {
            url += "&user_id="+ user_id;
        }
        if(id > 0) {
            url += "&id="+ id;
        }

        if(keyword != null && keyword != '') {
            url += "&keyword="+ keyword;
        }
        $.ajax({
            url: url,
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function(result){
                if(result != null) {
                    $('#<?= $html?>').before(result);
                    if(parseInt($('#' + '<?=$pageName?>').val()) >= parseInt($('#' + '<?=$pageCountName?>').val())){
                        $('#' + '<?=$pager_id?>').css('display','none');
                        return;
                    }
                }
                return;
            },
            error: function(result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                return;
            }
        });//end jQuery.ajax
    }
</script>