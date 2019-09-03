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

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","userInformation",false);
    define("TABLE2","article",false);
    define("TABLE3","activity",false);
    define("TABLE4","announcement",false);
    define("TABLE5","loveAnnouncement",false);
    define("TABLE6","loveArticle",false);
    define("TABLE7","commentArticle",false);
    define("TABLE8","loveActivity",false);
    define("TABLE9","commentActivity",false); 

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf8" />
    <title>推荐内容</title>
    <link type="image/x-icon" rel="icon" href="images/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/nav.css" />
    <link type="text/css" rel="stylesheet" href="css/user.css" />
    <link type="text/css" rel="stylesheet" href="css/gonggao.css" />
    <link type="text/css" rel="stylesheet" href="css/article-display.css" />
    <link type="text/css" rel="stylesheet" href="css/recommend.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/recommend.js"></script>
</head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <?php
                    $sql="select title,announcementTime,content,nid from ".TABLE4." order by announcementTime desc limit 0,2;";
                    $result=$db->query($sql);
                    if($result && $result->num_rows>0)
                    {
                        echo '
                        <div class="big-title">最新公告&nbsp;<a href="announcement-display.php"><i class="fa fa-plus add"></i></a></div>
                        <div class="box-gonggao recommend-region">
                        ';
                        for($i=0;$i<$result->num_rows;$i++)
                        {
                            $row=$result->fetch_assoc();
                            $title=$row["title"];
                            $announcementTime=$row["announcementTime"];
                            $announcementTime=substr($announcementTime,0,-3);
                            $content=$row["content"];
                            $nid=$row["nid"];
                            if(strlen($content)>350)
                                $content=substr($content,0,350)."...";
                            $sql="select * from ".TABLE1.",".TABLE5." where ".TABLE1.".uid=".TABLE5.".uid and email=\"$email\" and password=\"$password\" and nid=$nid;";
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
                            echo "
                            <div class='gonggao-item $shoucang'>
                            <div class='gonggao-title-line'>
                                <div class='gonggao-title'><i class='fa fa-fw fa-bullhorn'></i>$title</div>
                                <button class='gonggao-about-button'><i class='fa fa-fw $star'></i></button>
                                <div class='gonggao-time'>$announcementTime</div>
                                <div class='clear'></div>
                            </div>
                            <div class='nid'>$nid</div>
                            </div>
                            ";
                        }
                        echo '
                        </div>
                        ';
                    }
                ?>
                <!-- <div class="big-title">最新公告</div>
                <div class="box-gonggao recommend-region">
                    <div class="gonggao-item">
                    <div class="gonggao-title-line">
                        <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                        <button class="gonggao-about-button"><i class="fa fa-fw fa-star-o"></i></button>
                        <div class="gonggao-time">2019-4-20</div>
                        <div class="clear"></div>
                    </div>
                    <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                    </div>
                    <div class="gonggao-item">
                    <div class="gonggao-title-line">
                        <div class="gonggao-title"><i class="fa fa-fw fa-bullhorn"></i>同济大学嘉定校区植树节活动</div>
                        <button class="gonggao-about-button"><i class="fa fa-fw fa-star-o"></i></button>
                        <div class="gonggao-time">2019-4-20</div>
                        <div class="clear"></div>
                    </div>
                    <div class="gonggao-introduction">近代最早设立植树节的是美国的内布拉斯加州。1872年4月10日，莫顿在内布拉斯加州园林协会举行的一次会议上，提出了设立植树节的建议。该州采纳了莫顿的建议，把4月的第3个星期三定为该州的植树节，并于1932年发行世界上首枚植树节邮票，画面为两个儿童在植树。</div>
                    </div>
                </div> -->
                <?php
                    $sql="select ".TABLE1.".uid,userName,major from ".TABLE1.",".TABLE2." where email != \"$email\" and ".TABLE1.".uid=".TABLE2.".uid group by ".TABLE1.".uid order by count(*) limit 0,20;";
                    $result=$db->query($sql);
                    if($result!=false && $result->num_rows>0)
                    {
                        echo '
                        <div class="big-title">人气用户&nbsp;<a href="search-user.php"><i class="fa fa-plus add"></i></a></div>
                        <div class="display-box recommend-region" id="box-user">
                        ';
                        for($i=0;$i<$result->num_rows;$i++)
                        {
                            $row=$result->fetch_assoc();
                            $userName=$row["userName"];
                            $uid=$row["uid"];
                            $major=$row["major"];
                            if($major=="")
                                $major="未填写";
                            echo "
                            <div class='user-search'>
                            <img src='php/head.php?uid=$uid' alt='用户头像' />
                            <div class='user-search-right'>
                                <div class='user-search-name'>$userName</div>
                                <div class='user-search-major'>$major</div>
                            </div>
                            <a href='record.php?uid=$uid' class='user-search-a'></a>
                            </div>
                            ";
                        }
                        echo '
                        </div>
                        ';
                    }
                ?>
                <!-- <div class="big-title">人气用户</div>
                <div class="display-box recommend-region" id="box-user">
                    <div class="user-search">
                        <img src="images/head.jpg" alt="用户头像" />
                        <div class="user-search-right">
                            <div class="user-search-name">bingo</div>
                            <div class="user-search-major">信息安全</div>
                        </div>
                        <a href="https://www.baidu.com" class="user-search-a"></a>
                    </div>
                    <div class="user-search">
                        <img src="images/head.jpg" alt="用户头像" />
                        <div class="user-search-right">
                            <div class="user-search-name">bingo</div>
                            <div class="user-search-major">计算机科学与技术</div>
                        </div>
                        <a href="https://www.baidu.com" class="user-search-a"></a>
                    </div>
                </div> -->
                <?php
                    $sql="select aid,title,introduction,articleTime,type,readNumber,userName,".TABLE1.".uid from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid order by articleTime desc limit 0,4;";
                    $result=$db->query($sql);
                    if($result && $result->num_rows>0)
                    {
                        echo '
                        <div class="big-title">最新文章&nbsp;<a href="article-display.php"><i class="fa fa-plus add"></i></a></div>
                        <div id="box-article" class="recommend-region">
                        ';
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

                            $sql="select count(*) from ".TABLE6." where aid=$aid;";
                            $result1=$db->query($sql);
                            $row1=$result1->fetch_assoc();
                            $loveNumber=$row1["count(*)"];
                            $sql="select count(*) from ".TABLE7." where aid=$aid;";
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
                    }
                ?>
                <!-- <div class="big-title">最新文章</div>
                <div id="box-article" class="recommend-region">
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
                </div> -->
                <?php
                    $sql="select yid,title,activityTime,startTime,endTime,location,maxNum,type,userName,readNumber,".TABLE1.".uid from ".TABLE1.",".TABLE3." where ".TABLE1.".uid=".TABLE3.".uid order by activityTime desc limit 0,4;";
                    $result=$db->query($sql);
                    if($result && $result->num_rows>0)
                    {
                        echo '
                        <div class="big-title">最新活动&nbsp;<a href="article-display.php"><i class="fa fa-plus add"></i></a></div>
                        <div id="box-activity" class="recommend-region">
                        ';
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
                    }
                    $db->close();
                ?>
                <!-- <div class="big-title">最新活动</div>
                <div id="box-activity" class="recommend-region">
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
                </div> -->
            </div>
        </div>
    </body>
</html>