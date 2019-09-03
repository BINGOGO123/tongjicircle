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
    define("TABLE3","agreeArticle",false);
    define("TABLE4","loveArticle",false);
    define("TABLE5","commentArticle",false);
    define("TABLE6","activity",false);
    define("TABLE7","agreeActivity",false);
    define("TABLE8","loveActivity",false);
    define("TABLE9","commentActivity",false);

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
    <title>活动查看</title>
    <link type="image/x-icon" rel="icon" href="images/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="css/article-display.css" />
    <link type="text/css" rel="stylesheet" href="css/article-header.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/article-display.js"></script>
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
                <!--这里加一个搜索框-->
                <div class="row">
                    <div id="header">
                        <h1>寻找你最爱的分享吧</h1>
                        <p>这里可以找到所有用户的分享内容</p>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <i class="fa fa-search fa-lg"></i>
                                    <input id="search-input" type="text" placeholder="输入查询内容..."/>
                                    <button id="go-search">走起</button>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!--水平选项卡放置位置-->
                    <div class="nth-tabs" id="editor-tabs"></div>
                </div>

                <?php
                    echo '<div class="box-module">';
                    $sql="select aid,title,introduction,articleTime,type,readNumber,userName,".TABLE1.".uid from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid;";
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
                    $k=$i;
                    $sql="select yid,title,activityTime,startTime,endTime,location,maxNum,type,userName,readNumber,".TABLE1.".uid from ".TABLE1.",".TABLE6." where ".TABLE1.".uid=".TABLE6.".uid;";
                    $result=$db->query($sql);
                    for(;$i-$k<$result->num_rows;$i++)
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
                    $db->close();
                ?>

                <!--写的参照格式-->
                <!-- <div class="box-module">
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
