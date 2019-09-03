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
    define("TABLE3","loveArticle",false);
    define("TABLE4","commentArticle",false);
    define("TABLE5","activity",false);
    define("TABLE6","loveActivity",false);
    define("TABLE7","commentActivity",false);
    define("TABLE8","announcement",false);
    define("TABLE9","loveAnnouncement",false);

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }
    
    //获取查看用户的用户号
    if(isset($_REQUEST["uid"]) && $_REQUEST["uid"] != null)
    {
        $muid=$_REQUEST["uid"];
        $sql="select userName,major from ".TABLE1." where uid=$muid;";
        $result=$db->query($sql);
        if(!$result || $result->num_rows <= 0)
        {
            echo "无该用户";
            $db->close();
            return;
        }
        $row=$result->fetch_assoc();
        $userName=$row["userName"];
        $major=$row["major"];
    }
    else {
        $sql="select uid,userName,major from ".TABLE1." where email=\"$email\" and password=\"$password\";";
        $result=$db->query($sql);
        if(!$result || $result->num_rows <= 0)
        {
            echo "您的邮箱密码好像不对";
            $db->close();
            return;
        }
        $row=$result->fetch_assoc();
        $muid=$row["uid"];
        $userName=$row["userName"];
        $major=$row["major"];
    }
    if($major=="")
        $major="未填写";

    $sql="select title,aid,articleTime from ".TABLE2." where uid=$muid order by articleTime desc;";
    $result=$db->query($sql);
    $article=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $article[$i]=array("aid"=>$row["aid"],"time"=>$row["articleTime"],"title"=>$row["title"]);
        }
    }
    $sql="select title,yid,activityTime from ".TABLE5." where uid=$muid order by activityTime desc;";
    $result=$db->query($sql);
    $activity=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $activity[$i]=array("yid"=>$row["yid"],"time"=>$row["activityTime"],"title"=>$row["title"]);
        }
    }
    $sql="select loveTime,".TABLE2.".aid,title from ".TABLE2.",".TABLE3." where ".TABLE2.".aid=".TABLE3.".aid and ".TABLE3.".uid=$muid order by loveTime desc;";
    $result=$db->query($sql);
    $loveArticle=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $loveArticle[$i]=array("aid"=>$row["aid"],"time"=>$row["loveTime"],"title"=>$row["title"]);
        }
    }
    $sql="select commentTime,".TABLE2.".aid,title,".TABLE4.".content from ".TABLE2.",".TABLE4." where ".TABLE2.".aid=".TABLE4.".aid and ".TABLE4.".uid=$muid order by commentTime desc;";
    $result=$db->query($sql);
    $commentArticle=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $commentArticle[$i]=array("aid"=>$row["aid"],"time"=>$row["commentTime"],"title"=>$row["title"],"content"=>$row["content"]);
        }
    }
    $sql="select loveTime,".TABLE5.".yid,title from ".TABLE5.",".TABLE6." where ".TABLE5.".yid=".TABLE6.".yid and ".TABLE6.".uid=$muid order by loveTime desc;";
    $result=$db->query($sql);
    $loveActivity=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $loveActivity[$i]=array("yid"=>$row["yid"],"time"=>$row["loveTime"],"title"=>$row["title"]);
        }
    }
    $sql="select commentTime,".TABLE5.".yid,title,".TABLE7.".content from ".TABLE5.",".TABLE7." where ".TABLE5.".yid=".TABLE7.".yid and ".TABLE7.".uid=$muid order by commentTime desc;";
    $result=$db->query($sql);
    $commentActivity=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $commentActivity[$i]=array("yid"=>$row["yid"],"time"=>$row["commentTime"],"title"=>$row["title"],"content"=>$row["content"]);
        }
    }
    $sql="select loveTime,".TABLE8.".nid,title from ".TABLE8.",".TABLE9." where ".TABLE8.".nid=".TABLE9.".nid and ".TABLE9.".uid=$muid order by loveTime desc;";
    $result=$db->query($sql);
    $loveAnnouncement=array();
    if($result)
    {
        for($i=0;$i<$result->num_rows;$i++)
        {
            $row=$result->fetch_assoc();
            $loveAnnouncement[$i]=array("nid"=>$row["nid"],"time"=>$row["loveTime"],"title"=>$row["title"]);
        }
    }
    //获取所有记录完毕
    $db->close();

    // var_dump($article);
    // var_dump($loveArticle);
    // var_dump($commentArticle);
    // var_dump($activity);
    // var_dump($loveActivity);
    // var_dump($commentActivity);
    // var_dump($loveAnnouncement);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf8" />
    <title>个人主页</title>
    <link type="image/x-icon" rel="icon" href="images/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/nav.css" />
    <link type="text/css" rel="stylesheet" href="css/record.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/record.js"></script>
</head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="person-region">
                    <div class="person-info">
                        <div class="person-fixed">
                            <img src="<?php echo "php/head.php?uid=$muid";?>" alt="用户头像" />
                            <div class="person-text">
                                <div class="person-name"><?php echo $userName;?></div>
                                <div class="person-major"><?php echo $major;?></div>
                            </div>
                        </div>
                        <div class="data">
                            <div class="flex-item">
                                <div class="data-item">
                                    <span class="badge badge-secondary left data-title">文章</span>&nbsp;<span class="data-num"><?php echo count($article); ?></span>
                                </div>
                                <div class="data-item">
                                    <span class="badge badge-dark left data-title">活动</span>&nbsp;<span class="data-num"><?php echo count($activity); ?></span>
                                </div>
                            </div>
                            <div class="flex-item">
                                <div class="data-item">
                                    <span class="badge badge-primary left data-title">评论</span>&nbsp;<span class="data-num"><?php echo count($commentArticle)+count($commentActivity); ?></span>
                                </div>
                                <div class="data-item">
                                    <span class="badge badge-danger left data-title">收藏</span>&nbsp;<span class="data-num"><?php echo count($loveArticle)+count($loveActivity)+count($loveAnnouncement); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    function maxTime($timeList)
                    {
                        $length=count($timeList);
                        $presentMax=null;
                        $order=null;
                        for($i=0;$i<$length;$i++)
                        {
                            if($presentMax==null || $timeList[$i]["time"] > $presentMax)
                            {
                                $presentMax=$timeList[$i]["time"];
                                $order=$timeList[$i]["order"];
                            }
                        }
                        return array("order"=>$order,"max"=>$presentMax);
                    }
                    for(;;)
                    {
                        $para=array();
                        if(count($article))
                            array_push($para,array("time"=>$article[0]["time"],"order"=>0));
                        if(count($loveArticle))
                            array_push($para,array("time"=>$loveArticle[0]["time"],"order"=>1));
                        if(count($commentArticle))
                            array_push($para,array("time"=>$commentArticle[0]["time"],"order"=>2));
                        if(count($activity))
                            array_push($para,array("time"=>$activity[0]["time"],"order"=>3));
                        if(count($loveActivity))
                            array_push($para,array("time"=>$loveActivity[0]["time"],"order"=>4));
                        if(count($commentActivity))
                            array_push($para,array("time"=>$commentActivity[0]["time"],"order"=>5));
                        if(count($loveAnnouncement))
                            array_push($para,array("time"=>$loveAnnouncement[0]["time"],"order"=>6));
                        if(!count($para))
                            break;
                        $result=maxTime($para);
                        if($result["order"]==0) //发布文章
                        {
                            $fill=array_shift($article);
                            $time=substr($fill["time"],0,-3);
                            $aid=$fill["aid"];
                            $title=$fill["title"];
                            echo "
                            <div class='record-line record-write'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-book'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>发表了文章：</div>
                                    <div class='record-content'>$title</div>
                                    <div class='record-href'><a href='article.php?aid=$aid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                        else if($result["order"]==1) //喜欢文章
                        {
                            $fill=array_shift($loveArticle);
                            $time=substr($fill["time"],0,-3);
                            $aid=$fill["aid"];
                            $title=$fill["title"];
                            echo "
                            <div class='record-line record-love'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-heart'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>收藏了文章：</div>
                                    <div class='record-content'>$title</div>
                                    <div class='record-href'><a href='article.php?aid=$aid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                        else if($result["order"]==2) //评论文章
                        {
                            $fill=array_shift($commentArticle);
                            $time=substr($fill["time"],0,-3);
                            $aid=$fill["aid"];
                            $title=$fill["title"];
                            $content=$fill["content"];
                            echo "
                            <div class='record-line record-comment'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-comment'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>评论了文章：</div>
                                    <div class='record-content'>$content</div>
                                    <div class='record-href'><a href='article.php?aid=$aid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                        else if($result["order"]==3) //发布活动
                        {
                            $fill=array_shift($activity);
                            $time=substr($fill["time"],0,-3);
                            $yid=$fill["yid"];
                            $title=$fill["title"];
                            echo "
                            <div class='record-line record-write'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-book'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>创建了活动：</div>
                                    <div class='record-content'>$title</div>
                                    <div class='record-href'><a href='activity.php?yid=$yid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                        else if($result["order"]==4) //喜欢活动
                        {
                            $fill=array_shift($loveActivity);
                            $time=substr($fill["time"],0,-3);
                            $yid=$fill["yid"];
                            $title=$fill["title"];
                            echo "
                            <div class='record-line record-love'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-heart'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>参加了活动：</div>
                                    <div class='record-content'>$title</div>
                                    <div class='record-href'><a href='activity.php?yid=$yid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                        else if($result["order"]==5)  //评论活动
                        {
                            $fill=array_shift($commentActivity);
                            $time=substr($fill["time"],0,-3);
                            $yid=$fill["yid"];
                            $title=$fill["title"];
                            $content=$fill["content"];
                            echo "
                            <div class='record-line record-comment'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-comment'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>评论了活动：</div>
                                    <div class='record-content'>$content</div>
                                    <div class='record-href'><a href='activity.php?yid=$yid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                        else if($result["order"]==6)  //喜欢公告
                        {
                            $fill=array_shift($loveAnnouncement);
                            $time=substr($fill["time"],0,-3);
                            $nid=$fill["nid"];
                            $title=$fill["title"];
                            echo "
                            <div class='record-line record-love'>
                                <div class='record-left'>
                                    <i class='fa fa-3x fa-heart'></i>
                                </div>
                                <div class='record-right'>
                                    <div class='record-title'>收藏了公告：</div>
                                    <div class='record-content'>$title</div>
                                    <div class='record-href'><a href='announcement.php?nid=$nid'>$title</a></div>
                                    <div class='record-time'>$time</div>
                                </div>
                            </div>
                            ";
                        }
                    }
                ?>
                <!-- <div class="record-line record-write">
                    <div class="record-left">
                        <i class="fa fa-3x fa-book"></i>
                    </div>
                    <div class="record-right">
                        <div class="record-title">发表了文章：</div>
                        <div class="record-content">关于嘉定校区图书馆空调须知</div>
                        <div class="record-href"><a href="javascript:void(0)">关于嘉定校区图书馆空调须知</a></div>
                        <div class="record-time">2019-5-30 11:30</div>
                    </div>
                </div>
                <div class="record-line record-comment">
                    <div class="record-left">
                        <i class="fa fa-3x fa-comment"></i>
                    </div>
                    <div class="record-right">
                        <div class="record-title">发表了评论：</div>
                        <div class="record-content">关于嘉定校区图书馆空调须知</div>
                        <div class="record-href"><a href="javascript:void(0)">关于嘉定校区图书馆空调须知</a></div>
                        <div class="record-time">2019-5-30 11:30</div>
                    </div>
                </div>
                <div class="record-line record-love">
                    <div class="record-left">
                        <i class="fa fa-3x fa-heart"></i>
                    </div>
                    <div class="record-right">
                        <div class="record-title">收藏了文章：</div>
                        <div class="record-content">关于嘉定校区图书馆空调须知</div>
                        <div class="record-href"><a href="javascript:void(0)">关于嘉定校区图书馆空调须知</a></div>
                        <div class="record-time">2019-5-30 11:30</div>
                    </div>
                </div> -->
            </div>
        </div>
    </body>
</html>