<header>
  <h1 class="topTitle">ToDooon</h1>
  <h1 class="pageTitle"><?php echo $title; ?></h1>
  <div class="topNav">
    <?php if (empty($_SESSION['user_id'])) {
      ?>
      <span class="topNav-item"><a href="signup.php">ユーザー登録</a></span>
      <span class="topNav-item"><a href="login.php">ログイン</a></span>
    <?php

  } else {
    ?>
      <span class="topNav-item"><a href="mypage.php">マイページ</a></span>
      <span class="topNav-item"><a href="logout.php">ログアウト</a></span>
    <?php

  } ?>
  </div>
</header>
<div class="main">
