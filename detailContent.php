<?php
require('function.php');

debugStart();

require('auth.php');



if (!empty($_GET['c_id'])) {
    $c_id=$_GET['c_id'];
    $detail=detailContent($c_id);
    debug('詳細１：'.print_r($detail, true));

    foreach ($detail as $key => $val);
    debug('パッチ１：'.print_r($key, true));
    debug('パッチ１：'.print_r($val, true));
}
debug('アップデート3：'.print_r($_POST, true));
if (!empty($_POST['content'])) {
    $content=$_POST['content'];
    debug('アップデート1：'.print_r($content, true));

    $detail=detailContent($c_id);

    debug('アップデート4：'.print_r($detail, true));

    global $err_msg;
    if ($val['content'] !== $content) {
        try {
            $dbh=dbConnect();
            $sql='UPDATE contents SET content=:content WHERE id=:id';
            $data=array(':content'=>$content,':id'=>$detail['data']['id']);
            $stmt=queryPost($dbh, $sql, $data);
            debug('アップデート2：'.print_r($stmt, true));
            header('Location:mypage.php');
        } catch (Exception $e) {
            error_log('エラーが発生しました'.$e->getMessage());
            $err_msg=MSG07;
        }
    }
}

$title="編集画面";
require('head.php');
require('header.php');

?>
<body>
    <form action="" method="post" class="patchContent">

    <textarea name="content" cols="80" rows="5" class="patchContent-item"><?php echo $val['content']; ?></textarea>

    <input type="submit" value='編集' class="patchContent-submit">

  </form>
</body>
