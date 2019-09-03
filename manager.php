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
    define("TABLE3","activity",false);
    define("TABLE4","announcement",false);
    define("TABLE5","commentArticle",false);
    define("TABLE6","commentActivity",false); 
    define("TABLE7","manager",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }

    $sql="select * from ".TABLE1.",".TABLE7." where ".TABLE1.".uid=".TABLE7.".uid and password=\"$password\" and email=\"$email\";";
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
    <title>推荐内容</title>
    <link type="image/x-icon" rel="icon" href="images/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/nav.css" />
    <link type="text/css" rel="stylesheet" href="css/manager.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/manager.js"></script>
</head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="bar">
                <ul>
                    <li class="clicked">用户</li>
                    <li>文章</li>
                    <li>活动</li>
                    <li>公告</li>
                    <li>文章评论</li>
                    <li>活动评论</li>
                </ul>
                <div class="move-in rotate">
                    <i class="fa fa-lg fa-chevron-left"></i>
                </div>
            </div>

            <div class="user-box">
                    <!-- <div class="user-item item-up">
                            <div>头像</div>
                            <div>昵称</div>
                            <div>专业</div>
                            <div>邮箱</div>
                            <div>注册时间</div>
                            <div>真实姓名</div>
                            <div>学号</div>
                            <div>操作</div>
                        </div> -->
                <!-- <div class="user-item">
                    <img src="images/head.jpg" alt="用户头像" />
                    <div>bingo</div>
                    <div>计算机科学与技术</div>
                    <div>416778940@qq.com</div>
                    <div>2019-06-03</div>
                    <div>王宝强</div>
                    <div>1652241</div>
                    <button class="btn btn-primary btn-sm">查看</button>
                </div> -->
                <table class="table-region user-region present">
                    <thead>
                        <tr>
                            <th>头像</th>
                            <th>昵称</th>
                            <th>邮箱</th>
                            <th>专业</th>
                            <th>真实姓名</th>
                            <th>学号</th>
                            <th>注册时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="select uid,userName,email,major,trueName,studyNumber,userDate from ".TABLE1." order by uid desc;";
                            $result=$db->query($sql);
                            if($result)
                            {
                                for($i=0;$i<$result->num_rows;$i++)
                                {
                                    $row=$result->fetch_assoc();
                                    $uid=$row["uid"];
                                    $userName=$row["userName"];
                                    $email=$row["email"];
                                    $major=$row["major"];
                                    $trueName=$row["trueName"];
                                    $studyNumber=$row["studyNumber"];
                                    $userDate=$row["userDate"];
                                    echo "
                                    <tr>
                                        <td><img src='php/head.php?uid=$uid' alt='用户头像' /></td>
                                        <td>$userName</td>
                                        <td>$email</td>
                                        <td>$major</td>
                                        <td>$trueName</td>
                                        <td>$studyNumber</td>
                                        <td>$userDate</td>
                                        <td><a href='record.php?uid=$uid' target='_blank'>查看</a></td>
                                    </tr>
                                    ";
                                }
                            }
                        ?>
                        <!-- <tr>
                            <td><img src="images/head.jpg" alt="用户头像" /></td>
                            <td>bingo</td>
                            <td>416778940@qq.com</td>
                            <td>计算机科学与技术</td>
                            <td>臧海彬</td>
                            <td>1652241</td>
                            <td>2019-06-03</td>
                            <td><a href="www.baidu.com">查看</a></td>
                        </tr> -->
                    </tbody>
                </table>
                <table class="table-region article-region">
                    <thead>
                        <tr>
                            <th>文章题目</th>
                            <th>作者</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="select ".TABLE1.".uid,userName,title,articleTime,aid from ".TABLE1.",".TABLE2." where ".TABLE1.".uid=".TABLE2.".uid order by aid desc;";
                            $result=$db->query($sql);
                            if($result)
                            {
                                for($i=0;$i<$result->num_rows;$i++)
                                {
                                    $row=$result->fetch_assoc();
                                    $uid=$row["uid"];
                                    $userName=$row["userName"];
                                    $title=$row["title"];
                                    $articleTime=substr($row["articleTime"],0,-3);
                                    $aid=$row["aid"];
                                    echo "
                                    <tr>
                                        <td><a href='article.php?aid=$aid' target='_blank'>$title</a></td>
                                        <td><a href='record.php?uid=$uid' target='_blank'>$userName</a></td>
                                        <td>$articleTime</td>
                                        <td><button class='btn btn-primary'>删除</button></td>
                                        <td class='hidden-info' type='aid'>$aid</td>
                                    </tr>
                                    ";
                                }
                            }
                        ?>
                        <!-- <tr>
                            <td><a href="asdf">asdfasdf</a></td>
                            <td><a href="asdf">bingo</a></td>
                            <td>2019-3-2 12:35</td>
                            <td><button class="btn btn-primary">删除</button></td>
                            <td class="hidden-info">123</td>
                        </tr> -->
                    </tbody>
                </table>

                <table class="table-region activity-region">
                    <thead>
                        <tr>
                            <th>活动题目</th>
                            <th>作者</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="select ".TABLE1.".uid,userName,title,activityTime,yid from ".TABLE1.",".TABLE3." where ".TABLE1.".uid=".TABLE3.".uid order by yid desc;";
                            $result=$db->query($sql);
                            if($result)
                            {
                                for($i=0;$i<$result->num_rows;$i++)
                                {
                                    $row=$result->fetch_assoc();
                                    $uid=$row["uid"];
                                    $userName=$row["userName"];
                                    $title=$row["title"];
                                    $activityTime=substr($row["activityTime"],0,-3);
                                    $yid=$row["yid"];
                                    echo "
                                    <tr>
                                        <td><a href='activity.php?yid=$yid' target='_blank'>$title</a></td>
                                        <td><a href='record.php?uid=$uid' target='_blank'>$userName</a></td>
                                        <td>$activityTime</td>
                                        <td><button class='btn btn-primary'>删除</button></td>
                                        <td class='hidden-info' type='yid'>$yid</td>
                                    </tr>
                                    ";
                                }
                            }
                        ?>
                        <!-- <tr>
                            <td><a href="asdf">asdfasdf</a></td>
                            <td><a href="asdf">bingo</a></td>
                            <td>2019-3-2 12:35</td>
                            <td><button class="btn btn-primary">删除</button></td>
                            <td class="hidden-info">123</td>
                        </tr> -->
                    </tbody>
                </table>
                <table class="table-region announcement-region">
                    <thead>
                        <tr>
                            <th>公告题目</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="select nid,title,announcementTime from ".TABLE4." order by nid desc;";
                            $result=$db->query($sql);
                            if($result)
                            {
                                for($i=0;$i<$result->num_rows;$i++)
                                {
                                    $row=$result->fetch_assoc();
                                    $title=$row["title"];
                                    $announcementTime=substr($row["announcementTime"],0,-3);
                                    $nid=$row["nid"];
                                    echo "
                                    <tr>
                                        <td><a href='announcement.php?nid=$nid' target='_blank'>$title</a></td>
                                        <td>$announcementTime</td>
                                        <td><button class='btn btn-primary'>删除</button></td>
                                        <td class='hidden-info' type='nid'>$nid</td>
                                    </tr>
                                    ";
                                }
                            }
                        ?>
                        <!-- <tr>
                            <td><a href="asdf">asdfasdf</a></td>
                            <td>2019-3-2 12:35</td>
                            <td><button class="btn btn-primary">删除</button></td>
                            <td class="hidden-info">123</td>
                        </tr> -->
                    </tbody>
                </table>
                <table class="table-region article-comment-region">
                    <thead>
                        <tr>
                            <th>文章</th>
                            <th>评论人</th>
                            <th>时间</th>
                            <th>内容</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="select ".TABLE1.".uid,userName,title,commentTime,".TABLE2.".aid,".TABLE5.".content,cid from ".TABLE1.",".TABLE2.",".TABLE5." where ".TABLE1.".uid=".TABLE5.".uid and ".TABLE5.".aid=".TABLE2.".aid order by ".TABLE2.".aid desc,cid desc;";
                            $result=$db->query($sql);
                            if($result)
                            {
                                for($i=0;$i<$result->num_rows;$i++)
                                {
                                    $row=$result->fetch_assoc();
                                    $uid=$row["uid"];
                                    $aid=$row["aid"];
                                    $cid=$row["cid"];
                                    $userName=$row["userName"];
                                    $title=$row["title"];
                                    $content=$row["content"];
                                    $commentTime=substr($row["commentTime"],0,-3);
                                    echo "
                                    <tr>
                                        <td><a href='article.php?aid=$aid' target='_blank'>$title</a></td>
                                        <td><a href='record.php?uid=$uid' target='_blank'>$userName</a></td>
                                        <td>$commentTime</td>
                                        <td>$content</td>
                                        <td><button class='btn btn-primary'>删除</button></td>
                                        <td class='hidden-info' type='aid cid'>$aid $cid</td>
                                    </tr>
                                    ";
                                }
                            }
                        ?>
                        <!-- <tr>
                            <td><a href="asdf">asdfasdf</a></td>
                            <td><a href="asdf">bingo</a></td>
                            <td>2019-3-2 12:35</td>
                            <td>啊手动阀手动阀</td>
                            <td><button class="btn btn-primary">删除</button></td>
                            <td class="hidden-info">123</td>
                        </tr> -->
                    </tbody>
                </table>
                <table class="table-region activity-comment-region">
                    <thead>
                        <tr>
                            <th>活动</th>
                            <th>评论人</th>
                            <th>时间</th>
                            <th>内容</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="select ".TABLE1.".uid,userName,title,commentTime,".TABLE3.".yid,".TABLE6.".content,cid from ".TABLE1.",".TABLE3.",".TABLE6." where ".TABLE1.".uid=".TABLE6.".uid and ".TABLE6.".yid=".TABLE3.".yid order by ".TABLE3.".yid desc,cid desc;";
                            $result=$db->query($sql);
                            if($result)
                            {
                                for($i=0;$i<$result->num_rows;$i++)
                                {
                                    $row=$result->fetch_assoc();
                                    $uid=$row["uid"];
                                    $yid=$row["yid"];
                                    $cid=$row["cid"];
                                    $userName=$row["userName"];
                                    $title=$row["title"];
                                    $content=$row["content"];
                                    $commentTime=substr($row["commentTime"],0,-3);
                                    echo "
                                    <tr>
                                        <td><a href='activity.php?yid=$yid' target='_blank'>$title</a></td>
                                        <td><a href='record.php?uid=$uid' target='_blank'>$userName</a></td>
                                        <td>$commentTime</td>
                                        <td>$content</td>
                                        <td><button class='btn btn-primary'>删除</button></td>
                                        <td class='hidden-info' type='yid cids'>$yid $cid</td>
                                    </tr>
                                    ";
                                }
                            }
                            $db->close();
                        ?>
                        <!-- <tr>
                            <td><a href="asdf">asdfasdf</a></td>
                            <td><a href="asdf">bingo</a></td>
                            <td>2019-3-2 12:35</td>
                            <td>啊手动阀手动阀</td>
                            <td><button class="btn btn-primary">删除</button></td>
                            <td class="hidden-info">123</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>