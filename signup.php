<?php
require('function.php');


debugStart();

if (!empty($_POST)) {
    $name=$_POST['name'];
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $re_pass=$_POST['re_pass'];

    debug('デバッグ：'.print_r($name, true));
    debug('デバッグ：'.print_r($email, true));
    debug('デバッグ：'.print_r($pass, true));
    debug('デバッグ：'.print_r($re_pass, true));

    validEmpty($name, 'name');
    validEmpty($email, 'email');
    validEmpty($pass, 'pass');
    validEmpty($re_pass, 're_pass');
    debug('デバッグ：'.print_r($err_msg, true));


    if (empty($err_msg)) {
        //ユーザーネーム形式チェック
        validMaxLen($name, 'name');

        //Email形式チェック等
        validEmail($email, 'email');
        validMinLen($email, 'email');
        validMaxLen($email, 'email');

        //パスワードの半角英数字チェック
        validHalf($pass, 'pass');
        validMinLen($pass, 'pass');
        validMaxLen($pass, 'pass');

        if (empty($err_msg)) {
            //パスワードとパスワード再入力の一致チェック
            validMatch($pass, $re_pass, 'pass');

            if (empty($err_msg)) {
                try {
                    $dbh=dbConnect();
                    $sql='INSERT INTO users (email,name,password,create_date,login_time) VALUES(:email,:name,:pass,:create_date,:login_time)';
                    $data=array(':email'=>$email,':name'=>$name,':pass'=>password_hash($pass, PASSWORD_DEFAULT),':create_date'=>date('Y-m-d H:i:s'),':login_time'=>date('Y-m-d H:i:s'));
                    $stmt=queryPost($dbh, $sql, $data);
                    debug('デバッグ：'.print_r($stmt, true));


                    if (!empty($stmt)) {
                        //ログイン有効期限の設定
                        $sesLimit=60*60;
                        $_SESSION['login_time']=time();
                        $_SESSION['login_limit']=$sesLimit;

                        $_SESSION['user_id']=$dbh->lastInsertId();

                        //セッション変数の中身
                        debug('セッション変数の中身'.$_SESSION, true);
                        header('Location:mypage.php');
                    }
                } catch (Exception $e) {
                    error_log("エラーが発生しました".$e->getMessage());
                    $err_msg['common']=MSG07;
                }
            }
        }
    }
}





 ?>
 <?php
 require('head.php');
 require('header.php');
  ?>
 <body class="main">
   <h1 class="title">ユーザー登録</h1>
   <form action="" method="post">
     <div class="formLogin">
       <div class="msg_area">
         <?php if (!empty($err_msg['common'])) {
      echo $err_msg['common'];
  } ?>
       </div>
       <!--ユーザーネーム-->
       <label class="form-title <?php if (!empty($err_msg['name'])) {
      echo 'err';
  } ?>">
      ユーザーネーム
     <input type="text" class="formLogin_item" name='name' value="<?php if (!empty($_POST['name'])) {
      echo $_POST['name'];
  } ?>">
      </label>

      <div class="msg_area">
        <?php if (!empty($err_msg['name'])) {
      echo $err_msg['name'];
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

     <!--パスワード再入力-->
     <label class="form-title <?php if (!empty($err_msg['re_pass'])) {
      echo 'err';
  } ?>">
    パスワード再入力
     <input type="password" class="formLogin_item" name="re_pass">
     </label>

     <div class="msg_area">
       <?php if (!empty($err_msg['re_pass'])) {
      echo $err_msg['re_pass'];
  } ?>
     </div>

     <!--サブミット-->
     <input class='form-submit' type="submit" value="登録">
     </div>
   </form>
 </body>
