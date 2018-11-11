<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.tagit.css">
    <link rel="stylesheet" href="<?php echo $css; ?>tagit.ui-zendesk.css">
    <link rel="stylesheet" href="<?php echo $css; ?>bkstyle.css">
    <?php if (isset($bk)) {
        echo "<style>body {background-color: #e3fdfd;}</style>";
    }?>
    <title><?php getTitle() ?></title>
</head>
<body>