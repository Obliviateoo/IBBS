<?php
/**
 * Created by PhpStorm.
 * User: jiangnan
 * Date: 2019/5/22
 * Time: 15:39
 */
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
include_once '../inc/page.inc.php';

$conn = connect();
include_once 'inc/is_manage_login.inc.php';

$template['title'] = "IBBS后台管理";
$template['css'] = array('css/index.css');

$query = "select count(*) from ibbs_member";
$count_reply = get_num($conn, $query);
$page_size = 8;
$page = page($count_reply, $page_size, 5);
?>
<?php include 'inc/header.inc.php' ?>

    <div id="main">
        <div class="title">用户列表</div>
        <table class="list">
            <tr>
                <th>用户名</th>
                <th>注册时间</th>
                <th>操作</th>
            </tr>

            <?php
            $query = "select * from ibbs_member {$page['limit']}";
            $result = execute($conn, $query);
            while($data = mysqli_fetch_assoc($result)) {
                $url = urlencode("user_delete.php?id={$data['id']}");
                $return_url = urlencode($_SERVER['REQUEST_URI']);
                $message = "确定要删除用户 {$data['username']} 吗？";
                $delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";

$html=<<<JN
                <tr>
                    <td>{$data['username']}&nbsp;[id:&nbsp;{$data['id']}]</td>
                    <td>{$data['register_time']}</td>
                    <td><a href="member.php?id={$data['id']}">[访问]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
                </tr>
JN;
                echo $html;
            }
            ?>
        </table>
        <div class="pages_wrap_show">
            <div class="pages">
                <?php
                echo $page['html'];
                ?>
            </div>
        </div>
    </div>

<?php include 'inc/footer.inc.php' ?>