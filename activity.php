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

    //设置时区为上海
    date_default_timezone_set('Asia/Shanghai');

    if(!isset($_GET["yid"]))
    {
        echo "没有活动号，找不到活动";
        return;
    }
    $yid=$_GET["yid"];
    if($yid==null)
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
    define("TABLE2","activity",false);
    define("TABLE3","agreeActivity",false);
    define("TABLE4","loveActivity",false);
    define("TABLE5","commentActivity",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }
    $sql="select startTime,endTime,maxNum,location,content,title,activityTime,type,readNumber,userName,".TABLE1.".uid from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid and yid=$yid;";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //找不到内容
        $db->close();
        echo "查询失败，找不到活动";
        return;
    }
    $row=$result->fetch_assoc();
    $startTime=$row["startTime"];
    $endTime=$row["endTime"];
    $location=$row["location"];
    $maxNum=$row["maxNum"];
    $content=$row["content"];
    $title=$row["title"];
    $activityTime=$row["activityTime"];
    $type=$row["type"];
    $userName=$row["userName"];
    $uid=$row["uid"];
    $readNumber=$row["readNumber"];
    $readNumber++;
    //更改阅读量
    $sql="update ".TABLE2." set readNumber=$readNumber where yid=$yid";
    $db->query($sql);
    $sql="select count(*) from ".TABLE3." where yid=$yid;";
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
    $sql="select count(*) from ".TABLE4." where yid=$yid;";
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
    $sql="select count(*) from ".TABLE5." where yid=$yid;";
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
    $sql="select * from ".TABLE3." where uid=$muid and yid=$yid;";
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
    $sql="select * from ".TABLE4." where uid=$muid and yid=$yid;";
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
        <title>活动查看</title>
        <link type="image/x-icon" rel="icon" href="images/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="css/activity.css" />
        <link type="text/css" rel="stylesheet" href="css/liuyan.css" />
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/popper.min.js"></script>
        <script src="bootstrap/bootstrap.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="js/activity.js"></script>
        <script src="js/liuyan.js"></script>
        <link rel="stylesheet" href="css/nav.css" />
    </head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="content">
                                    <div class="content-title"><?php echo $title;?></div>
                                    <div class="content-time"><?php echo substr($activityTime,0,-3);?></div>
                                    <div class="clear"></div>
                                    <hr class="first-hr" />
                                    <div class="content-word"><?php echo $content;?></div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="agree-row">
                                                <ul class="agree">
                                                    <li><?php if($myAgree) echo '<i class="fa fa-thumbs-up fa-lg" id="agree"></i>'; else echo '<i class="fa fa-thumbs-o-up fa-lg" id="agree"></i>';?>&nbsp;<span id="agree-num"><?php echo $agreeNumber;?></span></li><span class="sep"></span>
                                                    <li><i class="fa fa-eye fa-lg" id="eye"></i>&nbsp;<span id="eye-num"><?php echo $readNumber;?></span></li><span class="sep"></span>
                                                    <li><i class="fa fa-commenting fa-lg" id="comment"></i>&nbsp;<span id="comment-num"><?php echo $commentNumber;?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="talk-box">
                            <div class="talk-box-line">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="para-line">
                                            <span class="line-title">创建人</span>&nbsp;<a class="user-link" href="javascript:void(0)"><?php echo $userName;?></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="para-line">
                                            <span class="line-title">创建时间</span>&nbsp;<?php echo substr($activityTime,0,-3);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="talk-box-line">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6">
                                        <div class="para-line">
                                            <span class="line-title">开始时间</span>&nbsp;<?php echo substr($startTime,0,-3);?>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6">
                                        <div class="para-line">
                                            <span class="line-title">结束时间</span>&nbsp;<?php echo substr($endTime,0,-3);?>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12">
                                        <div class="para-line">
                                            <span class="line-title">人数</span>&nbsp;<span class="safe" id="present-num"><?php echo $loveNumber;?></span>&nbsp;/&nbsp;<span class="danger" id="max-num"><?php echo $maxNum;?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="talk-box-line">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="para-line">
                                            <span class="line-title">活动地点</span>&nbsp;<?php echo $location;?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="para-line">
                                            <span class="line-title">类型</span>&nbsp;<?php echo $type;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="attend-line">
                                <?php 
                                    $time=date("Y-m-d h:i");
                                    $outdate=false;
                                    if($time >= substr($endTime,0,-3))
                                    {
                                        echo "<div class='outdate'>已过期</div>";
                                        $outdate=true;
                                    }
                                    else if($myLove)
                                    {
                                        echo '<div class="attended">已加入</div>';
                                        echo '<div class="attend hidden">未加入</div>';
                                    }
                                    else if(!$myLove)
                                    {
                                        echo '<div class="attended hidden">已加入</div>';
                                        echo '<div class="attend">未加入</div>';
                                    }
                                ?>
                            </div>
                            <hr/>
                            <div class="liuyan-region">
                                <div class="liuyan-input">
                                    <textarea <?php if($outdate) echo "disabled";?>></textarea>
                                    <div class="liuyan-flex">
                                        <div class="byte-display"><span>0</span>&nbsp;/&nbsp;300</div><button <?php if($outdate) echo "disabled";?> class="btn btn-primary">留言</button>
                                    </div>
                                </div>
                                <hr/>
                                <div class="liuyan-display">
                                    <?php
                                        $sql="select ".TABLE1.".uid,userName,content,commentTime from ".TABLE1.",".TABLE5." where ".TABLE1.".uid=".TABLE5.".uid and yid=$yid order by cid desc;";
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
                                    <!-- <div class="liuyan-item">
                                        <img class="liuyan-head" src="images/head.jpg">
                                        <div class="liuyan-info">
                                            <div class="liuyan-name"><a href="www.baidu.com">bingo</a></div>
                                            <div class="liuyan-content">的撒afads受到政府就ask的风景奥斯卡卡垃圾是否考虑就爱上了对方看就爱看路上的风景克拉斯的风景可拉萨酱豆腐卡拉绝对是开发了阿喀琉斯附近卡拉的风景卡拉进度付款了氨基酸地方</div>
                                            <div class="liuyan-time">2019-1-3 11:30</div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="liuyan-item liuyan-myself">
                                        <img class="liuyan-head" src="images/head.jpg">
                                        <div class="liuyan-info">
                                            <div class="liuyan-name"><a href="www.baidu.com">bingo</a></div>
                                            <div class="liuyan-content">的撒afads受到政府就ask的风景奥斯卡卡垃圾是否考虑就爱上了对方看就爱看路上的风景克拉斯的风景可拉萨酱豆腐卡拉绝对是开发了阿喀琉斯附近卡拉的风景卡拉进度付款了氨基酸地方</div>
                                            <div class="liuyan-time">2019-1-3 11:30</div>
                                            <div class="clear"></div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="yid"><?php echo $yid;?></div>
        </div>
    </body>
</html>