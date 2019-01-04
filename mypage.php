<?php
require('function.php');

debugStart();



if (!empty($_POST['content'])) {
  $content = $_POST['content'];
  debug('テキストエリア：' . print_r($content, true));
  postContent($content);
}

if (!empty($_POST['delete'])) {
  $delete = $_POST['delete'];
  deleteContent($delete);
}

$index = indexContent();




$title = "マイページ";
require('head.php');
require('header.php');

?>

<body>
  <form action="" method="post" enctype="multipart/form-data" class="formContent" >
    <textarea cols="80" rows="5" name="content" value="" class="formContent-text js-formContent-text"></textarea>
    <span class="js-countView">0</span>
    <input type="submit" class="form-submit" value="登録">
  </form>
  <div class="contentIndex">
    <?php foreach ($index['data'] as $key => $val) : ?>
    <div class="contentIndex-item">
      <p class="contentValue"><?php echo sanitize($val['content']); ?></p>
      <form action="" method="post" class="contentDelete">
        <input type="submit" value="削除" class="contentDelete-item">
        <input type="hidden" name="delete" value="<?php echo sanitize($val['id']) ?>">
      </form>
      <a href="detailContent.php<?php echo "?c_id=" . $val['id'] ?>" class="contentDetail">編集</a>
      <p class="contentDate"><?php echo sanitize($val['create_date']); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
  <?php require('footer.php') ?>

