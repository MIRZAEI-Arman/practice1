<html>
<head>
<link rel="stylesheet" href="style.css">
    <title>کتابخانه</title>
</head>
<body>
<header>
<blockquote>
    <a href="index.php"><img src="image/logo.png"></a>
</blockquote>
</header>
<blockquote>
<div class="container">
<center><h1>ورود</h1></center>
<form action="checklogin.php" method="post">
    نام کاربری:<br><input type="text" name="username"/>
    <br><br>
    پسورد:<br><input type="password" name="pwd" />
    <br><br>
    <input class="button" type="submit" value="ورود"/>
    <input class="button" type="button" name="cancel" value="برگشت" onClick="window.location='index.php';" />
</form>
</div>
<blockquote>
<?php
if(isset($_GET['errcode'])){
    if($_GET['errcode']==1){
        echo '<span style="color: red;">نام کاربری یا پسورد اشتباه می باشد.</span>';
    }elseif($_GET['errcode']==2){
        echo '<span style="color: red;">لطفا وارد شوید.</span>';
    }
}

?>
</body>
</html>