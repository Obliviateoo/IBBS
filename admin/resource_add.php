<?php
/**
 * Created by PhpStorm.
 * User: jiangnan
 * Date: 2019/5/17
 * Time: 22:55
 */
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
include_once 'inc/upload.inc.php';

$template['title'] = '添加资源';
$template['css'] = array('css/index.css');

$conn = connect();

$save_path = '';
$upload = null;

if (isset($_POST['pic'])) {
    $save_path = '../resources/uploads'.date('/Y/m/d/');
    $upload = upload($save_path,'2M','photo');
    if(!$upload['return']) {
        $save_path = '';
        skip('resource_add.php', 'error', $upload['error']);
    }
    else {
        setcookie('savepath', $save_path, time() + 300);
    }
}
if (isset($_POST['submit'])) {
    $pic_path = $_COOKIE['savepath'];
    skip('resource_add.php', 'ok', $pic_path);
}

?>
<?php include 'inc/header.inc.php' ?>
<div id="main">
    <div class="title" style="margin-bottom:20px;">添加资源</div>
    <form method="post" enctype="multipart/form-data">
        <table class="au">
            <tr>
                <td>封面图片</td>
                <td>
                    <input style="cursor:pointer;" width="100" type="file" name="photo" /><br /><br />
                    <input class="btn" type="submit" name="pic" value="保存" />
                </td>
                <td class="note">
                    务必添加封面图片,添加后请点击保存按钮，页面刷新完成后再继续添加下列内容
                </td>

            </tr>
        </table>
    </form>
    <form method="post">
        <table class="au">
            <tr>
                <td>资源名称</td>
                <td><input name="title" type="text" /></td>
                <td class="note">
                    资源名称不得为空，且最长不得超过50个字符
                </td>
            </tr>

            <tr>
                <td>资源简介</td>
                <td>
                    <textarea name="info"></textarea>
                </td>
                <td class="note">
                    建议添加有关该资源的简要介绍
                </td>
            </tr>
        </table>
        <input style="margin-left:110px;margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
    </form>
</div>
<?php include 'inc/footer.inc.php' ?>



