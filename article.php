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

    if(!isset($_GET["aid"]))
    {
        echo "没有文章号，找不到文章";
        return;
    }
    $aid=$_GET["aid"];
    if($aid==null)
    {
        echo "没有文章号，找不到文章";
        return;
    }

    $email=$_COOKIE["email"];

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","userInformation",false);
    define("TABLE2","article",false);
    define("TABLE3","agreeArticle",false);
    define("TABLE4","loveArticle",false);
    define("TABLE5","commentArticle",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }
    $sql="select content,title,introduction,articleTime,type,readNumber,userName,".TABLE1.".uid from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid and aid=$aid;";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //找不到内容
        $db->close();
        echo "查询失败，找不到文章";
        return;
    }
    $row=$result->fetch_assoc();
    $content=$row["content"];
    $title=$row["title"];
    $introduction=$row["introduction"];
    $articleTime=$row["articleTime"];
    $type=$row["type"];
    $userName=$row["userName"];
    $uid=$row["uid"];
    $readNumber=$row["readNumber"];
    $readNumber++;
    //更改阅读量
    $sql="update ".TABLE2." set readNumber=$readNumber where aid=$aid";
    $db->query($sql);
    $sql="select count(*) from ".TABLE3." where aid=$aid;";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //找不到内容
        $db->close();
        echo "查询失败，找不到点赞表";
        return;
    }
    $row=$result->fetch_assoc();
    $agreeNumber=$row["count(*)"];
    $sql="select count(*) from ".TABLE4." where aid=$aid;";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //找不到内容
        $db->close();
        echo "查询失败，找不到收藏表";
        return;
    }
    $row=$result->fetch_assoc();
    $loveNumber=$row["count(*)"];
    $sql="select count(*) from ".TABLE5." where aid=$aid;";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //找不到内容
        $db->close();
        echo "查询失败，找不到评论表";
        return;
    }
    $row=$result->fetch_assoc();
    $commentNumber=$row["count(*)"];
    $sql="select uid from ".TABLE1." where email=\"$email\";";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //找不到内容
        $db->close();
        echo "查询用户表失败";
        return;
    }
    $row=$result->fetch_assoc();
    $muid=$row["uid"];
    $sql="select * from ".TABLE3." where uid=$muid and aid=$aid;";
    $result=$db->query($sql);
    if(!$result)
    {
        //找不到内容
        $db->close();
        echo "查询点赞表失败";
        return;
    }
    if($result->num_rows <= 0)
    {
        $myAgree=false;
    }
    else {
        $myAgree=true;
    }
    $sql="select * from ".TABLE4." where uid=$muid and aid=$aid;";
    $result=$db->query($sql);
    if(!$result)
    {
        //找不到内容
        $db->close();
        echo "查询收藏表失败";
        return;
    }
    if($result->num_rows <= 0)
    {
        $myLove=false;
    }
    else {
        $myLove=true;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>文章查看</title>
        <link type="image/x-icon" rel="icon" href="images/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="plugin/canlender.css" />
        <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="css/article.css" />
        <link type="text/css" rel="stylesheet" href="css/liuyan.css" />
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/popper.min.js"></script>
        <script src="bootstrap/bootstrap.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="js/article.js"></script>
        <script src="js/liuyan.js"></script>
        <script src="plugin/schedule.js"></script>
        <link rel="stylesheet" href="css/nav.css" />
    </head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-12">
                            <div class="content">
                                <div class="title"><?php echo $title;?></div>
                                <div class="content-time"><?php echo substr($articleTime,0,-3);?></div>
                                <div class="clear"></div>
                                <hr class="first-hr" />
                                <div class="introduction"><?php echo $introduction;?></div>
                                <hr/>
                                <div class="content-word"><?php echo $content;?></div>
                                <hr/>
                                <div class="agree-row">
                                    <ul class="agree">
                                        <li><?php if($myAgree) echo '<i class="fa fa-thumbs-up fa-lg" id="agree"></i>'; else echo '<i class="fa fa-thumbs-o-up fa-lg" id="agree"></i>';?>&nbsp;<span id="agree-num"><?php echo $agreeNumber;?></span></li><span class="sep"></span>
                                        <li><?php if($myLove) echo '<i class="fa fa-heart fa-lg" id="love"></i>'; else echo '<i class="fa fa-heart-o fa-lg" id="love"></i>';?>&nbsp;<span id="love-num"><?php echo $loveNumber;?></span></li><span class="sep"></span>
                                        <li><i class="fa fa-eye fa-lg" id="eye"></i>&nbsp;<span id="eye-num"><?php echo $readNumber;?></span></li><span class="sep"></span>
                                        <li><i class="fa fa-commenting fa-lg" id="comment"></i>&nbsp;<span id="comment-num"><?php echo $commentNumber;?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="liuyan-box">
                                <div class="liuyan-region">
                                    <div class="liuyan-input">
                                        <textarea></textarea>
                                        <div class="liuyan-flex">
                                            <div class="byte-display"><span>0</span>&nbsp;/&nbsp;300</div><button class="btn btn-primary">留言</button>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="liuyan-display">
                                        <?php
                                            $sql="select ".TABLE1.".uid,userName,content,commentTime from ".TABLE1.",".TABLE5." where ".TABLE1.".uid=".TABLE5.".uid and aid=$aid order by cid desc;";
                                            $result=$db->query($sql);
                                            if($result)
                                            {
                                                for($i=0;$i<$result->num_rows;$i++)
                                                {
                                                    $row=$result->fetch_assoc();
                                                    $uid=$row["uid"];
                                                    $userName=$row["userName"];
                                                    $content=$row["content"];
                                                    $commentTime=substr($row["commentTime"],0,-3);
                                                    $myself="";
                                                    if($uid==$muid)
                                                        $myself="liuyan-myself";
                                                    echo "
                                                    <div class='liuyan-item $myself'>
                                                        <img class='liuyan-head' src='php/head.php?uid=$uid'>
                                                        <div class='liuyan-info'>
                                                            <div class='liuyan-name'><a href='record.php?uid=$uid' target='_blank'>$userName</a></div>
                                                            <div class='liuyan-content'>$content</div>
                                                            <div class='liuyan-time'>$commentTime</div>
                                                            <div class='clear'></div>
                                                        </div>
                                                    </div>
                                                    ";
                                                }
                                            }
                                            $db->close();
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="article-info">
                        <div class="author">
                            <img <?php echo 'src="php/head.php?uid='.$uid.'"';?> alt="用户头像" />
                            <a <?php echo 'href="record.php?uid='.$uid.'"';?> target="_blank"><?php echo $userName;?></a>
                        </div>
                        <hr />
                        <div class="info">
                            <span id="article-time"><?php echo $articleTime;?></span>
                            <div>
                                <span id="article-type">类型</span> <span><?php echo $type;?></span>
                            </div>
                        </div>
                    </div>
                    <div id='schedule-box' class="boxshaw"></div>
                </div>
            </div>
            <div id="aid"><?php echo $aid;?></div>
        </div>
    </body>
</html>