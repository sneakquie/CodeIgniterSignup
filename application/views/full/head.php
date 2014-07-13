<!DOCTYPE html>
<html lang="<?php echo $this->application->_('language');?>">
<head>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/public/templates/full/ico/favicon.ico">

    <title><?php echo $title . $this->config->item('site_name');?></title>

    <link href="/public/templates/full/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/templates/full/css/blog.css" rel="stylesheet">
    <link href="/public/templates/full/css/social.min.css" rel="stylesheet">
    <link href="/public/templates/full/css/jquery.fs.picker.css" rel="stylesheet" type="text/css" media="all" />
    <link href="/public/templates/full/css/font-awesome.min.css" rel="stylesheet">
    <?php if(isset($css)){foreach($css as $value){?><link href="/public/templates/full/css/<?php echo $value;?>.css" rel="stylesheet"/><?php }}?>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/public/templates/full/js/bootstrap.min.js"></script>
    <script src="/public/templates/full/js/main.js"></script>
    <script src="/public/templates/full/js/jquery.fs.picker.js"></script>
    <script src="/public/templates/full/js/noty.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $("input[type=checkbox], input[type=radio]").picker();
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
    <script type="text/javascript">
        var language = new Array();
        var User = {
            logged: <?php echo $this->vinc_auth->logged() ? 'true' : 'false';?>,
        }

        var App = {
            url: "<?php echo $this->config->item('base_url');?>",
        }
    </script>
    <script src="/public/js/languages/ru.js"></script>
    <?php if(isset($js)){foreach($js as $value){?><script type="text/javascript" src="/public/templates/full/js/<?php echo $value;?>.js"></script><?php }}?>
</head>
