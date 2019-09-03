<?php
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
    define("TABLE","userInformation",false);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        echo "震惊，连接数据库失败了...";
        return;
    }

    $sql="select * from ".TABLE." where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if(!$result || $result->num_rows<=0)
    {
        //清除所有cookie
        setcookie("email","",0);
        setcookie("password","",0);
        //然后跳到登录界面
        $db->close();
        header("location:login.html");
        return;
    }
    $db->close();
    header("location:article-display.php");
?>