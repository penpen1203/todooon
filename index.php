<?php
require('function.php');
require('head.php');
 ?>
<!--ヘッド-->
<body>
  <h1 class="title">toDoリスト</h1>
  <form action="get" class="form">
    <input type="text" class="form-control" name="content" val="">
    <input type="submit" class="form-submit" value="送信">
  </form>

  <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
</body>
</html>
