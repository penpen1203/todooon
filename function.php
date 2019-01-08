<?php
ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

$debug_flg = true;
function debug($str)
{
    global $debug_flg;
    if (!empty($debug_flg)) {
        error_log("デバッグ:" . $str);
    }
}

session_save_path("/var/tmp");
ini_set("session.gc_maxlifetime", 60 * 60 * 24 * 30);
ini_set("session.cookie_lifetime", 60 * 60 * 24 * 30);
session_start();
session_regenerate_id();

function debugStart()
{
    debug("スタート");
}

define('MSG01', '入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03', 'パスワード（再入力）が合っていません');
define('MSG04', '半角英数字のみご利用いただけます');
define('MSG05', '6文字以上で入力してください');
define('MSG06', '256文字以内で入力してください');
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'パスワードが違います');

$err_msg = array();

function validEmpty($str, $key)
{
    if ($str === '') {
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}

function validEmail($str, $key)
{
    if (!preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|', $str)) {
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
function validMatch($str1, $str2, $key)
{
    if ($str1 !== $str2) {
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
function validHalf($str, $key)
{
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
function validMinLen($str, $key, $min = 6)
{
    if (mb_strlen($str, 'utf-8') < $min) {
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
function validMaxLen($str, $key, $max = 256)
{
    if (mb_strlen($str, 'utf-8') > $max) {
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
function validEmailDup($email)
{
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE email=:email AND delete_flg=0';
        $data = array(':email' => $email);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty(array_shift($result))) {
            $err_msg['email'] = MSG08;
        }
    } catch (Exeption $e) {
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
function validPass($str, $key)
{
    validHalf($str, $key);
    validMinLen($str, $key);
    validMaxLen($str, $key);
}

function getErrMsg($key)
{
    global $err_msg;
    if (!empty($err_msg[$key])) {
        return $err_msg[$key];
    }
}
function dbConnect()
{
    // if ($_SERVER['SERVER_NAME'] == 'localhost') {
        // $dsn = 'mysql:dbname=todo;host=localhost;charset=utf8';
        // $user = 'root';
        // $password = 'root';
        // $options = array(
        //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        // );
    // } else {
        // 本番環境用
    $dsn = 'mysql:dbname=heroku_422813c1929c427;host=us-cdbr-iron-east-01.cleardb.net;charset=utf8';
    $user = 'b7f81b50a1c068';
    $password = '1d2bc1fd';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    // }
    $dbh = new PDO($dsn, $user, $password, $options);

    return $dbh;
}

function queryPost($dbh, $sql, $data)
{
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}

function sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function postContent($content)
{
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO contents (content,create_date) VALUES(:content,:create_date)';
        $data = array(':content' => $content, ':create_date' => date('Y-m-d H:i:s'));

        $stmt = queryPost($dbh, $sql, $data);
    } catch (Exception $e) {
        error_log('エラーが発生しました' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

function indexContent()
{
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT id,content,delete_flg,create_date FROM contents WHERE delete_flg=0';
        $data = array();

        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt->fetchAll();
        return $result;
    } catch (Exception $e) {
        error_log('エラーが発生しました' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
function deleteContent($delete)
{
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'UPDATE contents SET delete_flg=1 WHERE id=:id';
        $data = array(':id' => $delete);

        $stmt = queryPost($dbh, $sql, $data);
        debug('デリート6：' . print_r($stmt, true));
    } catch (Exception $e) {
        error_log('エラーが発生しました' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

function detailContent($c_id)
{
    debug('パッチ１：' . print_r($c_id, true));

    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT id,content FROM contents WHERE id=:id AND delete_flg=0';
        $data = array(':id' => $c_id);

        $stmt = queryPost($dbh, $sql, $data);
        $result['data'] = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
        debug('パッチ１：' . print_r($result, true));
    } catch (Exeption $e) {
        error_log('エラーが発生しました' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
