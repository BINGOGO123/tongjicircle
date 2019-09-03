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

    if(!isset($_REQUEST["nid"]))
    {
        echo "公告不存在";
        return;
    }
    $nid=$_REQUEST["nid"];
    if($nid==null)
    {
        echo "公告不存在";
        return;
    }

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","announcement",false);
    define("TABLE2","loveAnnouncement",false);
    define("TABLE3","userInformation",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }

    $sql="select uid from ".TABLE3." where email=\"$email\" and password= \"$password\";";
    $result=$db->query($sql);
    if(!$result)
    {
        echo "数据库出问题了！";
        $db->close();
        return;
    }
    if($result->num_rows<=0)
    {
        $db->close();
        //清除所有cookie
        setcookie("email","",0);
        setcookie("password","",0);
        //然后跳到登录界面
        header("location:login.html");
        return;
    }
    $row=$result->fetch_assoc();
    $uid=$row["uid"];
    $sql="select nid,title,content,announcementTime,readNumber from ".TABLE1." where nid=$nid;";
    $result=$db->query($sql);
    if(!$result)
    {
        echo "数据库出问题了！";
        $db->close();
        return;
    }
    if($result->num_rows<=0)
    {
        echo "文章不存在";
        $db->close();
        return;
    }
    $row=$result->fetch_assoc();
    $title=$row["title"];
    $content=$row["content"];
    $announcementTime=substr($row["announcementTime"],0,-3);
    $readNumber=$row["readNumber"];
    $readNumber++;
    $sql="update ".TABLE1." set readNumber=$readNumber where nid=$nid;";
    if(!$db->query($sql))
        $readNumber--;
    $sql="select * from ".TABLE2." where uid=$uid and nid=$nid;";
    $result1=$db->query($sql);
    if(!$result1||$result1->num_rows<=0)
    {
        $star="fa-star-o";
        $shoucang="";
    }
    else {
        $star="fa-star";
        $shoucang="shoucang";
    }
    $sql="select count(*) from ".TABLE2." where nid=$nid;";
    $result=$db->query($sql);
    if(!$result || $result->num_rows<=0)
        $loveNumber=0;
    else
        $loveNumber=$result->fetch_assoc()["count(*)"];
    $db->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>公告查看</title>
        <link type="image/x-icon" rel="icon" href="images/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="css/anouncement.css" />
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/popper.min.js"></script>
        <script src="bootstrap/bootstrap.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="js/announcement.js"></script>
        <link rel="stylesheet" href="css/nav.css" />
    </head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="content">
                                    <div class="content-title"><?php echo $title;?></div>
                                    <div class="content-time"><?php echo $announcementTime;?></div>
                                    <div class="clear"></div>
                                    <hr class="first-hr" />
                                    <div class="content-word"><?php echo $content;?></div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="agree-row">
                                                <ul class="agree">
                                                    <li><i class="fa <?php echo $star;?> fa-lg" id="love"></i>&nbsp;<span id="love-num"><?php echo $loveNumber;?></span></li><span class="sep"></span>
                                                    <li><i class="fa fa-eye fa-lg" id="eye"></i>&nbsp;<span id="eye-num"><?php echo $readNumber;?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="nid"><?php echo $nid;?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>
    </body>
</html>