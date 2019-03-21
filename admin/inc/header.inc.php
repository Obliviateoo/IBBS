<?php
/**
 * Created by PhpStorm.
 * User: jiangnan
 * Date: 2019/3/19
 * Time: 21:47
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo $template['title'] ?></title>
    <link rel="shortcut icon" href="css/bbs32.png">
    <?php
        foreach ($template['css']  as $val) {
            echo "<link rel='stylesheet' type='text/css' href='{$val}'>";
        }
    ?>
</head>
<body>
<div id="top">
    <div class="logo">
        IBBS管理中心
    </div>
    <!--
    <ul class="nav">
        <li><a href="#">占位</a></li>
        <li><a href="#">占位</a></li>
    </ul>
    -->
    <div class="login_info">
        <a href="#" style="color:#fff;">网站首页</a>&nbsp;|&nbsp;
        管理员：admin <a href="#">[注销]</a>
    </div>
</div>
<div id="sidebar">
    <ul>
        <li>
            <div class="small_title">系统</div>
            <ul class="child">
                <li><a href="#" class="current">系统信息</a></li>
                <li><a href="#">管理员</a></li>
                <li><a href="#">添加管理员</a></li>
                <li><a href="#">站点设置</a></li>
            </ul>
        </li>
        <li>
            <div class="small_title">内容管理</div>
            <ul class="child">
                <li><a href="father_module.php" class="<?php if (basename($_SERVER['SCRIPT_NAME']) == 'father_module.php') {echo "current";} ?>">父版块列表</a></li>
                <li><a href="father_module_add.php" class="<?php if (basename($_SERVER['SCRIPT_NAME']) == 'father_module_add.php') {echo "current";} ?>">添加父版块</a></li>
                <li><a href="#">子版块列表</a></li>
                <li><a href="#">添加子版块</a></li>
                <li><a href="#">帖子管理</a></li>
            </ul>
        </li>
        <li>
            <div class="small_title">用户管理</div>
            <ul class="child">
                <li><a href="#">用户列表</a></li>
            </ul>
        </li>
    </ul>
</div>