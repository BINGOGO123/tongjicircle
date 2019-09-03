<?php
    //首先我们查询数据库找到所有需要的信息

    //如果未登录或者登录已经过期
    if(!(isset($_COOKIE["email"]) && isset($_COOKIE["password"])))
    {
        //清除所有cookie
        setcookie("email","",0);
        setcookie("password","",0);
        //然后跳到登录界面
        header("location:login.html");
        return;
    }

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","userInformation",false);
    define("TABLE2","manager",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }

    $sql="select * from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid and password=\"$password\" and email=\"$email\";";
    $result=$db->query($sql);
    if(!$result || $result->num_rows<=0)
    {
        $db->close();
        header("location:article-display.php");
        return;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>发布文章</title>
        <link type="image/x-icon" rel="icon" href="images/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/write-announcement.css" />
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/popper.min.js"></script>
        <script src="bootstrap/bootstrap.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="plugin/wangEditor.js"></script>
        <script src="js/write-announcement.js"></script>
        <link rel="stylesheet" href="css/nav.css" />
    </head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <div class="region">
                            <div class="box">
                            <input type="text" class="title" placeholder="请输入公告标题（最多20字）" maxlength="20"/>
                            <div id="editor">
                                <p>请输入公告内容</p>
                            </div>
                            <button class="btn btn-primary submit-article">发布</button>
                            <div class="clear"></div>
                            <div class="back-trans-1"></div>
                            <div class="back-trans-2"></div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </div>
        </div>
    </body>
</html>