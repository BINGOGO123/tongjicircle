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
    $password=$_COOKIE["password"];

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }
    $sql="select trueName,userName,userDate,major,studyNumber,studyPassword from ".TABLE." where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if($result==false || $result->num_rows <= 0)
    {
        //保存在cookie中的邮箱和密码居然是错的...
        $db->close();
        //清除所有cookie
        setcookie("email","",0);
        setcookie("password","",0);
        header("location:login.html");
        return;
    }
    $row=$result->fetch_assoc();
    $trueName=$row["trueName"];
    $userName=$row["userName"];
    $userDate=$row["userDate"];
    $major=$row["major"];
    $studyNumber=$row["studyNumber"];
    $studyPassword=$row["studyPassword"];
    $db->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>个人信息修改</title>
        <link type="image/x-icon" rel="icon" href="images/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/user-information.css" />
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/popper.min.js"></script>
        <script src="bootstrap/bootstrap.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="js/user-information.js"></script>
        <link rel="stylesheet" href="css/nav.css" />
    </head>
    <body>
        <nav id="nav-bar"></nav>
        <div class="container-region">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        <div class="card" id="img-card">
                            <div class="card-header">头像</div>
                            <div class="card-body img-box">
                                <!--@改@ 填写用户原头像-->
                                <img id="head-display" src="php/head.php" alt="您的头像居然无法显示了！" />
                                <div class="img-alert">
                                    <label>点击选择头像</label>
                                    <i class="fa fa-fw fa-camera"></i>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input id="head-choose" type="file" /><span id="img-alert"></span><button class="btn btn-primary" type="button" id="submit-image">确认</button>
                                <div class="clear"></div>
                            </div>
                            <div class="back-trans-1"></div>
                            <div class="back-trans-2"></div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="sep"></div>
                    </div>
                    <div class="col-md-5">
                        <div class="card" id="img-card">
                            <div class="card-header">个人信息</div>
                            <div class="card-body">
                                <form id="text-form">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="line">
                                                <div class="badge badge-light left">邮箱</div>&nbsp;
                                                <input class="right readonly-input" disabled type="text"/>
                                                <span class="input-initial-content"><?php echo $email;?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="line">
                                                <div class="badge badge-light left">注册时间</div>&nbsp;
                                                <input class="right readonly-input" disabled type="text"/>
                                                <span class="input-initial-content"><?php echo $userDate;?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="line">
                                                <div class="badge badge-light left">昵称</div>&nbsp;
                                                <input class="right" type="text" name="userName" maxlength="15"/>
                                                <span class="input-initial-content"><?php echo $userName;?></span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="line">
                                                <div class="badge badge-light left">姓名</div>&nbsp;
                                                <input class="right" type="text" name="trueName" maxlength="15"/>
                                                <span class="input-initial-content"><?php echo $trueName;?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="line">
                                                <div class="badge badge-light left">专业</div>&nbsp;
                                                <input class="right" type="text" name="major" maxlength="30" />
                                                <span class="input-initial-content"><?php echo $major;?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="line">
                                                <div class="badge badge-light left">一卡通号</div>&nbsp;
                                                <input class="right" type="text" name="studyNumber" maxlength="20" />
                                                <span class="input-initial-content"><?php echo $studyNumber;?></span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="line">
                                                <div class="badge badge-light left">一卡通密码</div>&nbsp;
                                                <input class="right" type="password" name="studyPassword" autocomplete="new-password" maxlength="30"/>
                                                <span class="input-initial-content"><?php echo $studyPassword;?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="line">
                                                <div class="badge badge-danger left">密码</div>&nbsp;
                                                <input class="right" type="password" name="password" maxlength="30"/>
                                                <span class="input-initial-content"><?php echo $password;?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary info-button" type="submit" id="text-submit">确认</button>
                                </form>
                                <div class="clear"></div>
                            </div>
                            <div class="back-trans-1"></div>
                            <div class="back-trans-2"></div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
    </body>
</html>