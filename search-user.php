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
    define("TABLE","userInformation",false);

    $email=$_COOKIE["email"];

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
    <title>查找用户</title>
    <link type="image/x-icon" rel="icon" href="images/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/nav.css" />
    <link type="text/css" rel="stylesheet" href="css/search-user.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/user.css" />
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/search-user.js"></script>
</head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="row">
                    <div id="header">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <i class="fa fa-search fa-lg"></i>
                                    <input id="search-input" type="text" placeholder="输入查找的用户..."/>
                                    <button id="go-search">搜索</button>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="nothing">没有相关用户呢...</div> -->
                <?php
                    $sql="select uid,userName,major from ".TABLE." where email != \"$email\" limit 0,20;";
                    $result=$db->query($sql);
                    if($result!=false && $result->num_rows>=0)
                    {
                        echo '
                        <div class="recommend-box">
                        <div class="title-line">
                            <div class="recommend-title">
                                为您推荐
                            </div>
                            <i class="fa fa-lg fa-times"></i>
                        </div>
                        <div class="display-box">
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
                        </div></div>
                        ';
                    }
                    $db->close();
                ?>
                <!-- <div class="recommend-box">
                    <div class="title-line">
                        <div class="recommend-title">
                            为您推荐
                        </div>
                        <i class="fa fa-lg fa-times"></i>
                    </div>
                    <div class="display-box">
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
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                        <div class="user-search">
                            <img src="images/head.jpg" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">bingo</div>
                                <div class="user-search-major">计算机科学与技术</div>
                            </div>
                            <a href="javascript:void(0)" class="user-search-a"></a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </body>
</html>