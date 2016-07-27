<?php
use app\widgets\Footer;
use app\widgets\Header;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>MY VIDEO</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=Yii::$app->request->baseUrl?>/advance/styles/bootstrap.min.css" rel="stylesheet">
    <link href="<?=Yii::$app->request->baseUrl?>/advance/styles/style.css" rel="stylesheet">
    <link href="<?=Yii::$app->request->baseUrl?>/advance/styles/style-responsive.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript
        ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="<?/*=Yii::$app->request->baseUrl*/?>/advance/js/jquery11.2.min.js"></script>-->
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/jquery.min.js"></script>
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/bootstrap.min.js"></script>
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/functions.js"></script>
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/browserdetect.js"></script>
    <link href="<?= Yii::$app->request->baseUrl?>/advance/styles/star-rating.min.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="<?= Yii::$app->request->baseUrl?>/advance/js/star-rating.min.js" type="text/javascript"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- Custom styles for this template -->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--&lt;!&ndash;[if lt IE 9]>-->

    <!--&lt;!&ndash; HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries &ndash;&gt;-->
    <!--&lt;!&ndash;[if lt IE 9]>-->
    <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
    <!--<![endif]&ndash;&gt;-->
</head>
<body>
    <!-- Header-->
    <?= Header::widget([])?>
    <!-- Header-->
<?php //$this->beginBody() ?>

<?= $content ?>
<!--footer start-->
<?= Footer::widget([])?>
<!--footer end-->
<?php //$this->endBody() ?>
</body>
</html>
<?php //$this->endPage() ?>
<script type="text/javascript">
    function ajax(url,html) {
        $.ajax({
            url: url,
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function(result){
                if(result != null && '' != result) {
                    $(html).html(result);
                } else {
                    $(html).html("<span  style='margin-left: 12px;color: red'>Không có dữ liệu</span>");
                }
                return;
            },
            error: function(result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                return;
            }
            });//end jQuery.ajax
    }

    function loadPlayer(url,subUrl, image){
        if(null == url || '' == url) {
            //$('#price').css('display','none');
            return;
        }
        var device = browserDectect1();
        //load player
        if (device.isApple()) {
            //document.getElementById("player").onclick = '';
            document.getElementById("player").innerHTML = '<video poster="' + image + '" width="100%" height="100%" controls><source src="'+url+'" type="video/mp4">Your browser does not support the video tag.</video>';
        } else if (device.isAndroid()) {
            //document.getElementById("player").onclick = '';
            var htm = "<video poster='" + image + "' id='videoHtml5' src='" + url + "' controls autofullscreen='true' width='300' height='170' type='video/m3u8'></video>";
            document.getElementById("player").innerHTML = htm;
        } else {
            jwplayer("player").setup({
                modes:[
                    {
                        type:'flash',
                        src:'<?= Yii::$app->request->baseUrl; ?>/advance/js/lives/5.3.swf.disable',
                        config:{
                            provider:'<?= Yii::$app->request->baseUrl; ?>/advance/js/lives/adaptiveProvider.swf',
                            image: image,
                            file: url
                        }
                    }
                ],
                flashplayer: "<?= Yii::$app->request->baseUrl; ?>/advance/js/jwplayer/player.swf",
                autostart: 'true',
                value: "netstreambasepath",
                quality: 'false',
                stretching: "exactfit",
                screencolor: "000000",
                provider:'http',
                'http.startparam':'start',
                controlbar: 'over',
                icons : 'true',
                image: '<?= Yii::$app->request->baseUrl; ?>/advance/images/Playerss.jpg',
                skin: "<?= Yii::$app->request->baseUrl; ?>/advance/js/jwplayer/skins/modieus/modieus.zip",
                display: {
                    icons: 'true'
                },
                dock: 'false',
                width:'100%',
                height:'450px',
                plugins: {
                    "<?= Yii::$app->request->baseUrl; ?>/advance/js/jwplayer/captions.js":{
                        file: subUrl,
                        fontSize: 15,
                        pluginmode: "HYBRID"
                    },

                    "ova-jw": {
                        "player": {
                            "modes": {
                                "linear": {
                                    "controls": {
                                        "enableFullscreen": true,
                                        "enablePlay": true,
                                        "enablePause": true,
                                        "enableMute": true,
                                        "enableVolume": true
                                    }
                                }
                            }
                        }
                    },
                    '<?= Yii::$app->request->baseUrl; ?>/advance/js/overlay.js': {
                        text: null//'<img src="<?php //echo Yii::app()->theme->baseUrl; ?>/images/logo2.png"/>'
                    }
                }
            });
        }
    }

</script>
