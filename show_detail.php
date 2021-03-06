<?php
/**
 * Created by PhpStorm.
 * User: jiangnan
 * Date: 2019/4/27
 * Time: 16:28
 */
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';

$template['title'] = '详情页';
$template['css'] = array('css/public.css', 'css/show.css');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', 'error', '参数错误！');
}

$conn = connect();
$member_id = is_login($conn);

$query = "select ic.id cid,ic.module_id,ic.title,ic.content,ic.publish_time,ic.member_id,ic.times,im.username,im.photo from ibbs_content ic,ibbs_member im where ic.id={$_GET['id']} and ic.member_id=im.id";
$result_content = execute($conn, $query);
if (mysqli_num_rows($result_content) != 1) {
    skip('index.php', 'error', '该帖不存在!');
}

$query = "update ibbs_content set times=times+1 where id={$_GET['id']}";
execute($conn, $query);

$data_content = mysqli_fetch_assoc($result_content);
$data_content['title'] = htmlspecialchars($data_content['title']);
$data_content['content'] = nl2br(htmlspecialchars($data_content['content']));
$data_content['times'] = $data_content['times'] + 1;

$query = "select * from ibbs_son_module where id={$data_content['module_id']}";
$result_son = execute($conn, $query);
$data_son = mysqli_fetch_assoc($result_son);

$query = "select * from ibbs_father_module where id={$data_son['father_module_id']}";
$result_father = execute($conn, $query);
$data_father = mysqli_fetch_assoc($result_father);

$query = "select count(*) from ibbs_reply where content_id={$_GET['id']}";
$count_reply = get_num($conn, $query);

?>
<?php include 'inc/header.inc.php' ?>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id'] ?>"><?php echo $data_father['module_name'] ?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id'] ?>"><?php echo $data_son['module_name'] ?></a> &gt; <?php echo $data_content['title'] ?>
</div>

<div id="main" class="auto">

    <?php
    $query = "select count(*) from ibbs_reply where content_id={$_GET['id']}";
    $count_reply = get_num($conn, $query);
    $page_size = 5;
    $page = page($count_reply, $page_size, 5);
    if(isset($_GET['page']) and $_GET['page'] == 1) {
    ?>
        <div class="contentWrap">
            <div class="left">
                <div class="head_img">
                    <a href="member.php?id=<?php echo $data_content['member_id'] ?>">
                        <img width=120 height=120 src="<?php if ($data_content['photo'] != '') {
                            echo $data_content['photo'];
                        } else {
                            echo 'css/photo.jpg';
                        } ?>" alt="">
                    </a>
                </div>
                <div class="name">
                    <a href=""><?php echo $data_content['username'] ?></a>
                </div>
            </div>
            <div class="right">
                <div class="title">
                    <h2><?php echo $data_content['title'] ?></h2>
                    <span>阅读：<?php echo $data_content['times'] ?>&nbsp;|&nbsp;回复：<?php echo $count_reply ?></span>
                </div>
                <div class="pubdate">
                    <span class="date">发布于：<?php echo $data_content['publish_time'] ?></span>
                    <span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
                </div>
                <div class="content">
                    <?php echo $data_content['content'] ?>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    <?php
    }
    ?>

    <?php

    $query = "select im.username,ir.member_id,im.photo,ir.reply_time,ir.id,ir.quote_id,ir.content from ibbs_reply ir,ibbs_member im where ir.member_id=im.id and ir.content_id={$_GET['id']} order by reply_time asc {$page['limit']}";
    $result_reply = execute($conn, $query);
    $i = ($_GET['page'] - 1) * $page_size + 1;
    while ($data_reply = mysqli_fetch_assoc($result_reply)) {
        $data_reply['content'] = nl2br(htmlspecialchars($data_reply['content']));
    ?>
        <div class="contentWrap">
            <div class="left">
                <div class="head_img">
                    <a href="member.php?id=<?php echo $data_reply['member_id'] ?>">
                        <img width=120 height=120 src="<?php if ($data_reply['photo'] != '') {echo $data_reply['photo'];} else {echo 'css/photo.jpg';} ?>"/>
                    </a>
                </div>
                <div class="name">
                    <a href=""><?php echo $data_reply['username'] ?></a>
                </div>
            </div>
            <div class="right">
                <div class="pubdate">
                    <span class="date">回复时间：<?php echo $data_reply['reply_time'] ?></span>
                    <span class="floor"><?php echo $i++ ?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $_GET['id'] ?>&reply_id=<?php echo $data_reply['id'] ?>">引用</a></span>
                </div>
                <div class="content">
                    <?php
                    if($data_reply['quote_id']){
                        $query = "select count(*) from ibbs_reply where content_id={$_GET['id']} and id<={$data_reply['quote_id']}";
                        $floor = get_num($conn, $query);
                        $query = "select ibbs_reply.content,ibbs_member.username from ibbs_reply,ibbs_member where ibbs_reply.id={$data_reply['quote_id']} and ibbs_reply.content_id={$_GET['id']} and ibbs_reply.member_id=ibbs_member.id";
                        $result_quote = execute($conn, $query);
                        $data_quote = mysqli_fetch_assoc($result_quote);
                        ?>
                        <div class="quote">
                            <h2>引用 <?php echo $floor ?>楼 <?php echo $data_quote['username']?> 发表的: </h2>
                            <?php echo nl2br(htmlspecialchars($data_quote['content']))?>
                        </div>
                    <?php }?>
                    <?php
                        echo $data_reply['content'];
                    ?>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    <?php
    }
    ?>
    <a id="talk" href="reply.php?id=<?php echo $_GET['id']?>" class="btn publish">回复</a>
    <div class="pages_wrap_show">
        <div class="pages">
            <?php
                echo $page['html'];
            ?>
        </div>
    </div>
</div>
<?php include 'inc/footer.inc.php' ?>
