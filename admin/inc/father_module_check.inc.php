<?php
/**
 * Created by PhpStorm.
 * User: jiangnan
 * Date: 2019/3/22
 * Time: 15:49
 */
?>
<?php
    //这里有一个问题，不论是add还是update，失败的话都跳转到添加页面，需要进行改进，update失败的话应该跳转到列表页或者修改页
    if (empty($_POST['module_name'])) {
        skip('father_module_add.php', 'error', '父版块名称不能为空！');
    }
    if (mb_strlen($_POST['module_name']) > 50) {
        skip('father_module_add.php', 'error', '父版块名称最长为50个字符！');
    }
    if (!is_numeric($_POST['sort'])) {
        skip('father_module_add.php', 'error', '请用数字来表示排序优先级！');
    }
    $_POST=escape($conn, $_POST);
    switch ($check_type) {
        case 'add':
            $query = "select * from ibbs_father_module where module_name='{$_POST['module_name']}'";
            break;
        case 'update':
            $query = "select * from ibbs_father_module where module_name='{$_POST['module_name']}' and id!={$_GET['id']}";
            break;
        default:
            skip('father_module_add.php', 'error', '内部参数发生错误！请联系上层管理员！');
    }
    $result = execute($conn, $query);
    if (mysqli_num_rows($result)) {
        skip('father_module_add.php', 'error', '该父版块已存在！');
    }
?>