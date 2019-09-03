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
    define("TABLE2","article",false);
    define("TABLE3","agreeArticle",false);
    define("TABLE4","loveArticle",false);
    define("TABLE5","commentArticle",false);
    define("TABLE6","activity",false);
    define("TABLE7","agreeActivity",false);
    define("TABLE8","loveActivity",false);
    define("TABLE9","commentActivity",false);
    define("TABLE10","announcement",false);
    define("TABLE11","loveAnnouncement",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }

    $sql="select uid from ".TABLE1." where email=\"$email\" and password= \"$password\";";
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
    $muid=$row["uid"];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf8" />
    <title>我的社区</title>
    <link type="image/x-icon" rel="icon" href="images/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="css/article-display.css" />
    <link type="text/css" rel="stylesheet" href="css/my-article.css" />
    <link type="text/css" rel="stylesheet" href="css/gonggao.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/my-article.js"></script>
    <link rel="stylesheet" href="css/nav.css" />
    <link href="jquery/jquery.scrollbar.min.css" rel="stylesheet">
    <script src="jquery/jquery.scrollbar.min.js"></script>
    <link href="plugin/nth.tabs.css" rel="stylesheet">
    <script src="plugin/nth.tabs.js"></script>
</head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="row">
                    <!--水平选项卡放置位置-->
                    <div class="nth-tabs" id="editor-tabs"></div>
                </div>
                <?php
                    echo '<div id="box-module-my-article" class="box-module">';
                    $sql="select aid,title,introduction,articleTime,type,readNumber,userName,".TABLE1.".uid from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid and ".TABLE1.".uid=$muid;";
                    $result=$db->query($sql);
                    for($i=0;$i<$result->num_rows;$i++)
                    {
                        $row=$result->fetch_assoc();
                        $title=$row["title"];
                        $aid=$row["aid"];
                        $uid=$row["uid"];
                        $userName=$row["userName"];
                        $type=$row["type"];
                        $introduction=$row["introduction"];
                        $articleTime=substr($row["articleTime"],0,-3);
                        $readNumber=$row["readNumber"];

                        $sql="select count(*) from ".TABLE3." where aid=$aid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $agreeNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE4." where aid=$aid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $loveNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE5." where aid=$aid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $commentNumber=$row1["count(*)"];

                        if($i%2==0)
                        {
                            if($i!=0)
                                echo "</div>";
                            echo "<div class='row'>";
                        }
                        echo "
                        <div class='col-xl-6'>
                            <div class='article article-true'>
                                <div class='row'>
                                    <div class='col-5'>
                                        <div class='article-big-box'>
                                            <img class='article-big' src='php/article-image.php?aid=$aid' alt='文章图片' />
                                        </div>
                                    </div>
                                    <div class='col-7'>
                                        <div class='row'>
                                            <div class='col-md-10 col-9'>
                                                <div><a href='article.php?aid=$aid' target='_blank' class='article-title'>$title</a><span class='display-type display-type-article'>文章</span></div>
                                            </div>
                                            <div class='col-md-2 col-3'>
                                                <i class='fa fa-lg fa-info-circle article-info'></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class='row'>
                                            <div class='col-sm-6'>
                                                <img src='php/head.php?uid=$uid' class='article-writer-img' alt='作者头像' />
                                                <a class='article-writer' href='record.php?uid=$uid' target='_blank'>$userName</a>
                                            </div>
                                            <div class='col-sm-6'>
                                                <span class='article-time'>$articleTime</span>
                                            </div>
                                        </div>
                                        <div class='last-line'>
                                            <div class='row'>
                                                <div class='col-4'>
                                                    <span class='article-type'>$type</span>
                                                </div>
                                                <div class='col-8'>
                                                    <i class='fa fa-lg fa-heart'></i>
                                                    <span>$loveNumber</span>
                                                    <i class='fa fa-lg fa-comment'></i>
                                                    <span>$commentNumber</span>
                                                    <i class='fa fa-lg fa-eye'></i>
                                                    <span>$readNumber</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='detail-box'>
                                    <div class='detail-title'><a href='article.php?aid=$aid' target='_blank' class='article-title'>$title</a></div>
                                    <hr/>
                                    <div class='detail-content'>$introduction</div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    if($i!=0)
                        echo "</div>";
                    echo "</div>";
                    echo '<div id="box-module-my-activity" class="box-module">';
                    $sql="select yid,title,activityTime,startTime,endTime,location,maxNum,type,userName,readNumber,".TABLE1.".uid from ".TABLE1.",".TABLE6." where ".TABLE1.".uid=".TABLE6.".uid and ".TABLE1.".uid=$muid;";
                    $result=$db->query($sql);
                    for($i=0;$i<$result->num_rows;$i++)
                    {
                        $row=$result->fetch_assoc();
                        $title=$row["title"];
                        $yid=$row["yid"];
                        $uid=$row["uid"];
                        $userName=$row["userName"];
                        $type=$row["type"];
                        $activityTime=substr($row["activityTime"],0,-3);
                        $startTime=substr($row["startTime"],0,-3);
                        $endTime=substr($row["endTime"],0,-3);
                        $location=$row["location"];
                        $maxNum=$row["maxNum"];
                        $readNumber=$row["readNumber"];

                        $sql="select count(*) from ".TABLE7." where yid=$yid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $agreeNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE8." where yid=$yid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $loveNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE9." where yid=$yid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $commentNumber=$row1["count(*)"];

                        if($i%2==0)
                        {
                            if($i!=0)
                                echo "</div>";
                            echo "<div class='row'>";
                        }

                        echo "
                        <div class='col-xl-6'>
                            <div class='article activity'>
                                <div class='row'>
                                    <div class='col-5'>
                                        <div class='article-big-box'>
                                            <img class='article-big' src='php/activity-image.php?yid=$yid' alt='文章图片' />
                                        </div>
                                    </div>
                                    <div class='col-7'>
                                        <div class='row'>
                                            <div class='col-md-10 col-9'>
                                                <div><a href='activity.php?yid=$yid' target='_blank' class='article-title'>$title</a><span class='display-type display-type-activity'>活动</span></div>
                                            </div>
                                            <div class='col-md-2 col-3'>
                                                <i class='fa fa-lg fa-info-circle article-info'></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class='start-time'>
                                            <span class='article-attr'>开始时间</span>&nbsp;<span class='article-time-start'>$startTime</span>
                                        </div>
                                        <div class='end-time'>
                                            <span class='article-attr'>结束时间</span>&nbsp;<span class='article-time-end'>$endTime</span>
                                        </div>
                                        <div class='last-line-activity'>
                                            <div class='row'>
                                                <div class='col-8'>
                                                    <div class='locate'>
                                                        <span class='article-attr'>地点</span>&nbsp;<span class='article-location'>$location</span>
                                                    </div>
                                                </div>
                                                <div class='col-4'>
                                                    <div class='attendence'>
                                                        <span>$loveNumber</span>&nbsp;\&nbsp;<span>$maxNum</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='detail-box'>
                                    <div class='detail-title'><a href='activity.php?yid=$yid' target='_blank' class='article-title'>$title</a></div>
                                    <hr/>
                                    <div>
                                        <span class='article-attr'>类型</span>&nbsp;<span class='normal-span'>$type</span>
                                    </div>
                                    <div>
                                        <span class='article-attr'>创建人</span>&nbsp;<span class='normal-span'><a href='record.php?uid=$uid' target='_blank'>$userName</a></span>
                                    </div>
                                    <div>
                                        <span class='article-attr'>地点</span>&nbsp;<span class='article-location'>$location</span>
                                    </div>
                                    <div>
                                        <span class='article-attr'>参与情况</span>&nbsp;<span class='green'>$loveNumber</span>&nbsp;\&nbsp;<span class='red'>$maxNum</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    if($i!=0)
                        echo "</div>";
                    echo "</div>";
                    echo '<div id="box-module-love-article" class="box-module">';
                    $sql="select ".TABLE2.".aid,title,introduction,articleTime,type,readNumber,userName,".TABLE1.".uid from ".TABLE1.",".TABLE2.",".TABLE4." where ".TABLE1.".uid=".TABLE2.".uid and ".TABLE2.".aid=".TABLE4.".aid and ".TABLE4.".uid=$muid;";
                    $result=$db->query($sql);
                    for($i=0;$i<$result->num_rows;$i++)
                    {
                        $row=$result->fetch_assoc();
                        $title=$row["title"];
                        $aid=$row["aid"];
                        $uid=$row["uid"];
                        $userName=$row["userName"];
                        $type=$row["type"];
                        $introduction=$row["introduction"];
                        $articleTime=substr($row["articleTime"],0,-3);
                        $readNumber=$row["readNumber"];

                        $sql="select count(*) from ".TABLE3." where aid=$aid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $agreeNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE4." where aid=$aid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $loveNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE5." where aid=$aid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $commentNumber=$row1["count(*)"];

                        if($i%2==0)
                        {
                            if($i!=0)
                                echo "</div>";
                            echo "<div class='row'>";
                        }
                        echo "
                        <div class='col-xl-6'>
                            <div class='article article-true'>
                                <div class='row'>
                                    <div class='col-5'>
                                        <div class='article-big-box'>
                                            <img class='article-big' src='php/article-image.php?aid=$aid' alt='文章图片' />
                                        </div>
                                    </div>
                                    <div class='col-7'>
                                        <div class='row'>
                                            <div class='col-md-10 col-9'>
                                                <div><a href='article.php?aid=$aid' target='_blank' class='article-title'>$title</a><span class='display-type display-type-article'>文章</span></div>
                                            </div>
                                            <div class='col-md-2 col-3'>
                                                <i class='fa fa-lg fa-info-circle article-info'></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class='row'>
                                            <div class='col-sm-6'>
                                                <img src='php/head.php?uid=$uid' class='article-writer-img' alt='作者头像' />
                                                <a class='article-writer' href='record.php?uid=$uid' target='_blank'>$userName</a>
                                            </div>
                                            <div class='col-sm-6'>
                                                <span class='article-time'>$articleTime</span>
                                            </div>
                                        </div>
                                        <div class='last-line'>
                                            <div class='row'>
                                                <div class='col-4'>
                                                    <span class='article-type'>$type</span>
                                                </div>
                                                <div class='col-8'>
                                                    <i class='fa fa-lg fa-heart'></i>
                                                    <span>$loveNumber</span>
                                                    <i class='fa fa-lg fa-comment'></i>
                                                    <span>$commentNumber</span>
                                                    <i class='fa fa-lg fa-eye'></i>
                                                    <span>$readNumber</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='detail-box'>
                                    <div class='detail-title'><a href='article.php?aid=$aid' class='article-title' target='_blank'>$title</a></div>
                                    <hr/>
                                    <div class='detail-content'>$introduction</div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    if($i!=0)
                        echo "</div>";
                    echo "</div>";
                    echo '<div id="box-module-love-activity" class="box-module">';
                    $sql="select ".TABLE6.".yid,title,activityTime,startTime,endTime,location,maxNum,type,userName,readNumber,".TABLE1.".uid from ".TABLE1.",".TABLE6.",".TABLE8." where ".TABLE1.".uid=".TABLE6.".uid and ".TABLE6.".yid=".TABLE8.".yid and ".TABLE8.".uid=$muid;";
                    $result=$db->query($sql);
                    for($i=0;$i<$result->num_rows;$i++)
                    {
                        $row=$result->fetch_assoc();
                        $title=$row["title"];
                        $yid=$row["yid"];
                        $uid=$row["uid"];
                        $userName=$row["userName"];
                        $type=$row["type"];
                        $activityTime=substr($row["activityTime"],0,-3);
                        $startTime=substr($row["startTime"],0,-3);
                        $endTime=substr($row["endTime"],0,-3);
                        $location=$row["location"];
                        $maxNum=$row["maxNum"];
                        $readNumber=$row["readNumber"];

                        $sql="select count(*) from ".TABLE7." where yid=$yid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $agreeNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE8." where yid=$yid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $loveNumber=$row1["count(*)"];
                        $sql="select count(*) from ".TABLE9." where yid=$yid;";
                        $result1=$db->query($sql);
                        $row1=$result1->fetch_assoc();
                        $commentNumber=$row1["count(*)"];

                        if($i%2==0)
                        {
                            if($i!=0)
                                echo "</div>";
                            echo "<div class='row'>";
                        }

                        echo "
                        <div class='col-xl-6'>
                            <div class='article activity'>
                                <div class='row'>
                                    <div class='col-5'>
                                        <div class='article-big-box'>
                                            <img class='article-big' src='php/activity-image.php?yid=$yid' alt='文章图片' />
                                        </div>
                                    </div>
                                    <div class='col-7'>
                                        <div class='row'>
                                            <div class='col-md-10 col-9'>
                                                <div><a href='activity.php?yid=$yid' target='_blank' class='article-title'>$title</a><span class='display-type display-type-activity'>活动</span></div>
                                            </div>
                                            <div class='col-md-2 col-3'>
                                                <i class='fa fa-lg fa-info-circle article-info'></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class='start-time'>
                                            <span class='article-attr'>开始时间</span>&nbsp;<span class='article-time-start'>$startTime</span>
                                        </div>
                                        <div class='end-time'>
                                            <span class='article-attr'>结束时间</span>&nbsp;<span class='article-time-end'>$endTime</span>
                                        </div>
                                        <div class='last-line-activity'>
                                            <div class='row'>
                                                <div class='col-8'>
                                                    <div class='locate'>
                                                        <span class='article-attr'>地点</span>&nbsp;<span class='article-location'>$location</span>
                                                    </div>
                                                </div>
                                                <div class='col-4'>
                                                    <div class='attendence'>
                                                        <span>$loveNumber</span>&nbsp;\&nbsp;<span>$maxNum</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='detail-box'>
                                    <div class='detail-title'><a href='activity.php?yid=$yid' target='_blank' class='article-title'>$title</a></div>
                                    <hr/>
                                    <div>
                                        <span class='article-attr'>类型</span>&nbsp;<span class='normal-span'>$type</span>
                                    </div>
                                    <div>
                                        <span class='article-attr'>创建人</span>&nbsp;<span class='normal-span'><a href='record.php?uid=$uid' target='_blank'>$userName</a></span>
                                    </div>
                                    <div>
                                        <span class='article-attr'>地点</span>&nbsp;<span class='article-location'>$location</span>
                                    </div>
                                    <div>
                                        <span class='article-attr'>参与情况</span>&nbsp;<span class='green'>$loveNumber</span>&nbsp;\&nbsp;<span class='red'>$maxNum</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    if($i!=0)
                        echo "</div>";
                    echo "</div>";
                    echo '<div id="box-module-love-gonggao" class="box-module"><div class="gonggao module">';
                    $sql="select ".TABLE10.".nid,title,content,announcementTime from ".TABLE10.",".TABLE11." where ".TABLE10.".nid=".TABLE11.".nid and ".TABLE11.".uid=$muid;";
                    $result=$db->query($sql);
                    if($result)
                    {
                        for($i=0;$i<$result->num_rows;$i++)
                        {
                            $row=$result->fetch_assoc();
                            $content=$row["content"];
                            $title=$row["title"];
                            $nid=$row["nid"];
                            $announcementTime=$row["announcementTime"];
                            if(strlen($content)>350)
                                $content=substr($content,0,350)."...";
                            $announcementTime=substr($announcementTime,0,-3);

                            echo "
                            <div class='gonggao-item shoucang'>
                            <div class='gonggao-title-line'>
                                <div class='gonggao-title'><i class='fa fa-fw fa-bullhorn'></i>$title</div>
                                <button class='gonggao-about-button'><i class='fa fa-fw fa-star'></i></button>
                                <div class='gonggao-time'>$announcementTime</div>
                                <div class='clear'></div>
                            </div>
                            <div class='nid'>$nid</div>
                            </div>
                            ";
                        }
                    }
                    echo '</div></div>';
                    $db->close();
                ?>
                <!-- <div id="box-module-my-article" class="box-module">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="article article-true">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="article-big-box">
                                            <img class="article-big" src="images/background1.jpg" alt="文章图片" />
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="row">
                                            <div class="col-md-10 col-9">
                                                <div><a href="www.baidu.com" class="article-title">文章标题</a><span class="display-type display-type-article">文章</span></div>
                                            </div>
                                            <div class="col-md-2 col-3">
                                                <i class="fa fa-lg fa-info-circle article-info"></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img src="images/background1.jpg" class="article-writer-img" alt="作者头像" />
                                                <a class="article-writer" href="www.baid.com">作者</a>
                                            </div>
                                            <div class="col-sm-6">
                                                <span class="article-time">2018-3-19 10:25</span>
                                            </div>
                                        </div>
                                        <div class="last-line">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="article-type">学习</span>
                                                </div>
                                                <div class="col-8">
                                                    <i class="fa fa-lg fa-heart"></i>
                                                    <span>110</span>
                                                    <i class="fa fa-lg fa-comment"></i>
                                                    <span>100</span>
                                                    <i class="fa fa-lg fa-eye"></i>
                                                    <span>100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-title"><a href="www.baidu.com" class="article-title">文章标题</a></div>
                                    <hr/>
                                    <div class="detail-content">文章内容</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="article article-true">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="article-big-box">
                                            <img class="article-big" src="images/background1.jpg" alt="文章图片" />
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="row">
                                            <div class="col-md-10 col-9">
                                                <div><a href="www.baidu.com" class="article-title">文章标题</a><span class="display-type display-type-article">文章</span></div>
                                            </div>
                                            <div class="col-md-2 col-3">
                                                <i class="fa fa-lg fa-info-circle article-info"></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-6 col-12">
                                                <img src="images/background1.jpg" class="article-writer-img" alt="作者头像" />
                                                <a class="article-writer" href="www.baidu.com">作者</a>
                                            </div>
                                            <div class="col-sm-6 col-12">
                                                <span class="article-time">2018-3-19 10:20</span>
                                            </div>
                                        </div>
                                        <div class="last-line">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="article-type">学习</span>
                                                </div>
                                                <div class="col-8">
                                                    <i class="fa fa-lg fa-heart"></i>
                                                    <span>50</span>
                                                    <i class="fa fa-lg fa-comment"></i>
                                                    <span>100</span>
                                                    <i class="fa fa-lg fa-eye"></i>
                                                    <span>100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-title"><a href="www.baidu.com" class="article-title">文章标题</a></div>
                                    <hr/>
                                    <div class="detail-content">文章内容</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="box-module-my-activity" class="box-module">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="article activity">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="article-big-box">
                                            <img class="article-big" src="images/background1.jpg" alt="文章图片" />
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="row">
                                            <div class="col-md-10 col-9">
                                                <div><a href="www.baidu.com" class="article-title">文章标题</a><span class="display-type display-type-activity">活动</span></div>
                                            </div>
                                            <div class="col-md-2 col-3">
                                                <i class="fa fa-lg fa-info-circle article-info"></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="start-time">
                                            <span class="article-attr">开始时间</span>&nbsp;<span class="article-time-start">2018-3-21 10:20</span>
                                        </div>
                                        <div class="end-time">
                                            <span class="article-attr">结束时间</span>&nbsp;<span class="article-time-end">2018-3-22 10:19</span>
                                        </div>
                                        <div class="last-line-activity">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="locate">
                                                        <span class="article-attr">地点</span>&nbsp;<span class="article-location">同济大学嘉定校区新天地</span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="attendence">
                                                        <span>100</span>&nbsp;\&nbsp;<span>100</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-title"><a href="www.baidu.com" class="article-title">文章标题</a></div>
                                    <hr/>
                                    <div>
                                        <span class="article-attr">类型</span>&nbsp;<span class="normal-span">社交</span>
                                    </div>
                                    <div>
                                        <span class="article-attr">创建人</span>&nbsp;<span class="normal-span"><a href="www.bingoz.cn">bingo</a></span>
                                    </div>
                                    <div>
                                        <span class="article-attr">地点</span>&nbsp;<span class="article-location">同济大学嘉定校区新天地</span>
                                    </div>
                                    <div>
                                        <span class="article-attr">参与情况</span>&nbsp;<span class="green">100</span>&nbsp;\&nbsp;<span class="red">100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="box-module-love-activity" class="box-module">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="article activity">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="article-big-box">
                                            <img class="article-big" src="images/background1.jpg" alt="文章图片" />
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="row">
                                            <div class="col-md-10 col-9">
                                                <div><a href="www.baidu.com" class="article-title">文章标题</a><span class="display-type display-type-activity">活动</span></div>
                                            </div>
                                            <div class="col-md-2 col-3">
                                                <i class="fa fa-lg fa-info-circle article-info"></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="start-time">
                                            <span class="article-attr">开始时间</span>&nbsp;<span class="article-time-start">2018-3-21 10:20</span>
                                        </div>
                                        <div class="end-time">
                                            <span class="article-attr">结束时间</span>&nbsp;<span class="article-time-end">2018-3-22 10:19</span>
                                        </div>
                                        <div class="last-line-activity">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="locate">
                                                        <span class="article-attr">地点</span>&nbsp;<span class="article-location">同济大学嘉定校区新天地</span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="attendence">
                                                        <span>100</span>&nbsp;\&nbsp;<span>100</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-title"><a href="www.baidu.com" class="article-title">文章标题</a></div>
                                    <hr/>
                                    <div>
                                        <span class="article-attr">类型</span>&nbsp;<span class="normal-span">社交</span>
                                    </div>
                                    <div>
                                        <span class="article-attr">创建人</span>&nbsp;<span class="normal-span"><a href="www.bingoz.cn">bingo</a></span>
                                    </div>
                                    <div>
                                        <span class="article-attr">地点</span>&nbsp;<span class="article-location">同济大学嘉定校区新天地</span>
                                    </div>
                                    <div>
                                        <span class="article-attr">参与情况</span>&nbsp;<span class="green">100</span>&nbsp;\&nbsp;<span class="red">100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="box-module-love-article" class="box-module">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="article article-true">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="article-big-box">
                                            <img class="article-big" src="images/background1.jpg" alt="文章图片" />
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="row">
                                            <div class="col-md-10 col-9">
                                                <div><a href="www.baidu.com" class="article-title">文章标题</a><span class="display-type display-type-article">文章</span></div>
                                            </div>
                                            <div class="col-md-2 col-3">
                                                <i class="fa fa-lg fa-info-circle article-info"></i>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img src="images/background1.jpg" class="article-writer-img" alt="作者头像" />
                                                <a class="article-writer" href="www.baid.com">作者</a>
                                            </div>
                                            <div class="col-sm-6">
                                                <span class="article-time">2018-3-19 10:25</span>
                                            </div>
                                        </div>
                                        <div class="last-line">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="article-type">学习</span>
                                                </div>
                                                <div class="col-8">
                                                    <i class="fa fa-lg fa-heart"></i>
                                                    <span>110</span>
                                                    <i class="fa fa-lg fa-comment"></i>
                                                    <span>100</span>
                                                    <i class="fa fa-lg fa-eye"></i>
                                                    <span>100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-title"><a href="www.baidu.com" class="article-title">文章标题</a></div>
                                    <hr/>
                                    <div class="detail-content">文章内容</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="box-module-love-gonggao" class="box-module">
                    <div class="gonggao module">
                        <div class="gonggao-item shoucang">
                        <div class="gonggao-title-line">
                            <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                            <button class="gonggao-about-button"><i class="fa fa-fw fa-star"></i></button>
                            <div class="gonggao-time">2019-4-23</div>
                            <div class="clear"></div>
                        </div>
                        <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                        </div>
                        <div class="gonggao-item shoucang">
                        <div class="gonggao-title-line">
                            <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                            <button class="gonggao-about-button"><i class="fa fa-fw fa-star"></i></button>
                            <div class="gonggao-time">2019-4-22</div>
                            <div class="clear"></div>
                        </div>
                        <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                        </div>
                        <div class="gonggao-item shoucang">
                        <div class="gonggao-title-line">
                            <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                            <button class="gonggao-about-button"><i class="fa fa-fw fa-star"></i></button>
                            <div class="gonggao-time">2019-4-15</div>
                            <div class="clear"></div>
                        </div>
                        <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                        </div>
                        <div class="gonggao-item shoucang">
                        <div class="gonggao-title-line">
                            <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                            <button class="gonggao-about-button"><i class="fa fa-fw fa-star"></i></button>
                            <div class="gonggao-time">2019-4-19</div>
                            <div class="clear"></div>
                        </div>
                        <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                        </div>
                        <div class="gonggao-item shoucang">
                        <div class="gonggao-title-line">
                            <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                            <button class="gonggao-about-button"><i class="fa fa-fw fa-star"></i></button>
                            <div class="gonggao-time">2019-4-21</div>
                            <div class="clear"></div>
                        </div>
                        <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </body>
</html>