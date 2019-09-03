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
    define("TABLE1","announcement",false);
    define("TABLE2","loveAnnouncement",false);
    define("TABLE3","userInformation",false);

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
        <title>公告</title>
        <link type="image/x-icon" rel="icon" href="images/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="css/announcement-display.css" />
        <link type="text/css" rel="stylesheet" href="css/gonggao.css" />
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="plugin/paging.css" />
        <!-- <link rel="stylesheet" href="plugin/reset.css" />
        <link rel="stylesheet" href="plugin/common.css" />
        <link rel="stylesheet" href="plugin/highlight.min.css" />
        <link rel="stylesheet" href="plugin/pagination.css" /> -->
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/popper.min.js"></script>
        <script src="bootstrap/bootstrap.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="plugin/paging.js"></script>
        <!-- <script src="plugin/jquery.pagination.js"></script> -->
        <script src="js/announcement-display.js"></script>
        <link rel="stylesheet" href="css/nav.css" />
    </head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="gonggao module">
                <?php
                    $sql="select nid,title,content,announcementTime from ".TABLE1." order by announcementTime desc;";
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
                            $sql="select * from ".TABLE2.",".TABLE3." where ".TABLE2.".uid=".TABLE3.".uid and email=\"$email\" and password=\"$password\" and nid=$nid;";
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
                    }
                    $db->close();
                ?>
                </div>
                <!-- <div class="gonggao module">
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
                <!-- <div class="page-item">
                    <div class="page-box" id="page-box"></div>
                </div> -->
            </div>
        </div>
    </body>
</html>