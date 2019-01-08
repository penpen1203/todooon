<?php
$title = 'トップページ';

require('function.php');
require('head.php');

?>
<!--ヘッド-->
<div class="main">
  <body>
    <div class="topPage">
      <h1 class="topPageTitle">toDoリスト</h1>
      <div class="topPageContainer">
        <a href="signup.php" class='topPageContent'>ユーザー登録</a>
        <a href="login.php" class='topPageContent'>ログイン</a>
      </div>
    </div>


    <?php require('footer.php') ?>
