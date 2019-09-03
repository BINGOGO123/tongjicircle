<?php
    //本脚本实现登陆功能

    //状态定义
    define("ERROR",2);
    define("EMAIL_ERROR",1);
    define("PASSWORD_ERROR",3);
    define("SUCCESS",0);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);

    require("data-deal.php");
    
    $final=array();
    if($_SERVER["REQUEST_METHOD"]!="POST")
    {
        $final["status"]=ERROR;
        $final["information"]="request is not post";
        echo json_encode($final);
        return;
    }

    // $email=$_POST["login-email"];
    // $password=$_POST["login-password"];
    $email=dataDeal($_POST["login-email"]);
    $password=dataDeal($_POST["login-password"]);

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        $final["status"]=ERROR;
        $final["information"]="database connect failed";
        echo json_encode($final);
        return;
    }

    $sql="select * from ".TABLE." where email=\"$email\";";
    $result=$db->query($sql);
    if($result==false)
    {
        $final["status"]=ERROR;
        $final["information"]="query failed";
        echo json_encode($final);
        $db->close();
        return;
    }

    if($result->num_rows <= 0)
    {
        $final["status"]=EMAIL_ERROR;
        echo json_encode($final);
        $db->close();
        return;
    }

    $sql="select count(*) from ".TABLE." where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if($result==false)
    {
        $final["status"]=ERROR;
        $final["information"]="query failed";
        echo json_encode($final);
        $db->close();
        return;
    }

    if($result->num_rows < 0)
    {
        $final["status"]=ERROR;
        $final["information"]="select count(*) query return nothing";
        echo json_encode($final);
        $db->close();
        return;
    }

    $row=$result->fetch_assoc();
    if(intval($row["count(*)"]) <= 0)
    {
        $final["status"]=PASSWORD_ERROR;
        echo json_encode($final);
        $db->close();
        return;
    }

    $final["status"]=SUCCESS;

    //将记录的结果存入cookie中
    //获取网站的根目录
    $root=$_SERVER["DOCUMENT_ROOT"];
    //将目录中的所有\变为/，这是为了linux上和windows上同样运行
    for($i = 0;$i < strlen($root);$i++)
    {
        if($root[$i]=="\\")
            $root[$i]="/";
    }
    //获取上一级目录的真实目录，因为cookie要存到上一级以便使用且cookie不支持相对路径
    $cookieDir=realpath("..");
    for($i = 0;$i < strlen($cookieDir);$i++)
    {
        if($cookieDir[$i]=="\\")
            $cookieDir[$i]="/";
    }
    //取得相对于网站根目录的绝对路径
    $dir=substr($cookieDir,strlen($root));
    if($dir=="" || $dir==null || $dir==false)
        $dir="/";
    //有效期为10天
    $second=time() + 60*60*24*10;
    setcookie("email",$email,$second,$dir);
    setcookie("password",$password,$second,$dir);

    echo json_encode($final);
    $db->close();
?> 