<?php
require('function.php');

debugStart();

debug('セッションを削除します');
session_destroy();
debug('ログインページに遷移します');
header('Location:login.php');
