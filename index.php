<?php
$title = 'トップページ';

require('function.php');
require('head.php');
require('header.php');

indexContent();
print_r($result);
?>
<!--ヘッド-->
<body>
  <h1 class="title">toDoリスト</h1>
  <form action="get" class="form">
    <input type="text" class="form-control" name="content" val="">
    <input type="submit" class="form-submit" value="送信">
  </form>

  <?php require('footer.php') ?>
