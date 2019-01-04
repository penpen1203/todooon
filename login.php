<?php
require('function.php');


debugStart();

//ログイン認証
require('auth.php');

if (!empty($_POST)) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    debug('デバッグ：' . print_r($email, true));
    debug('デバッグ：' . print_r($pass, true));

    validEmpty($email, 'email');
    validEmpty($pass, 'pass');
    debug('デバッグ：' . print_r($err_msg, true));


    if (empty($err_msg)) {

        //Email形式チェック等
        validEmail($email, 'email');
        validMinLen($email, 'email');
        validMaxLen($email, 'email');

        //パスワードの半角英数字チェック
        validHalf($pass, 'pass');
        validMinLen($pass, 'pass');
        validMaxLen($pass, 'pass');

        if (empty($err_msg)) {
            try {
                $dbh = dbConnect();
                $sql = 'SELECT password,id FROM users WHERE email=:email AND delete_flag=0';
                $data = array(':email' => $email);
                $stmt = queryPost($dbh, $sql, $data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                debug('デバッグ：' . print_r($result, true));


                if (!empty($result) && password_verify($pass, array_shift($result))) {
                    //ログイン有効期限の設定
                    $sesLimit = 60 * 60;
                    $_SESSION['login_date'] = time();

                    if (!empty($pass_save)) {
                        $_SESSION['login_limit'] = $sesLimit * 24 * 30;
                    } else {
                        $_SESSION['login_limit'] = $sesLimit;
                    }
                    $_SESSION['user_id'] = $result['id'];

                    //セッション変数の中身
                    debug('セッション変数の中身' . print_r($_SESSION, true));
                    header('Location:mypage.php');
                } else {
                    debug('パスワードが違います');
                    $err_msg = MSG08;
                }
            } catch (Exception $e) {
                error_log("エラーが発生しました" . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
?>
<?php
$title = "ログイン";
require('head.php');
require('header.php');
?>

<body class="main">
  <form action="" method="post">
    <div class="formLogin">
      <div class="msg_area">
        <?php if (!empty($err_msg['common'])) {
            echo $err_msg['common'];
        } ?>
      </div>

      <!--メールアドレス-->
       <label class="form-title <?php if (!empty($err_msg['email'])) {
                                    echo 'err';
                                } ?>">
      メールアドレス
     <input type="text" class="formLogin_item" name='email' value="<?php if (!empty($_POST['email'])) {
                                                                        echo $_POST['email'];
                                                                    } ?>">
     </label>

      <div class="msg_area">
        <?php if (!empty($err_msg['email'])) {
            echo $err_msg['email'];
        } ?>
      </div>

       <!--パスワード-->
       <label class="form-title <?php if (!empty($err_msg['pass'])) {
                                    echo 'err';
                                } ?>">
      パスワード ※半角英数字６文字以上で記入してください
     <input type="password" class="formLogin_item" name="pass" >
     </label>

     <div class="msg_area">
       <?php if (!empty($err_msg['pass'])) {
            echo $err_msg['pass'];
        } ?>
     </div>

     <input type="checkbox" name="pass_save">次回ログインを省略する

     <input class='form-submit' type="submit" value="ログイン">

   </form>



<?php require('footer.php') ?>

