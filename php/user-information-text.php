<?php
    //用以修改用户文字信息的脚本

    //状态定义
    define("SUCCESS",0,false);
    define("ERROR",1,false);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);

    require("data-deal.php");

    $final=array();
    //如果未登录或者登录已经过期
    if(!(isset($_COOKIE["email"]) && isset($_COOKIE["password"])))
    {
        //清除所有cookie
        setcookie("email","",0);
        setcookie("password","",0);
        
        $final["status"]=ERROR;
        $final["information"]="login information is uncompleted, you need login again";
        echo json_encode($final);
        return;
    }

    if($_SERVER["REQUEST_METHOD"]!="POST")
    {
        $final["status"]=ERROR;
        $final["information"]="request is not post";
        echo json_encode($final);
        return;
    }

    //修改数据库
    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        $final["status"]=ERROR;
        $final["information"]="database linked failed!";
        echo json_encode($final);
        return;
    }

    // $email=$_COOKIE["email"];
    // $password=$_COOKIE["password"];
    // $userName=$_POST["userName"];
    // $trueName=$_POST["trueName"];
    // $newPassword=$_POST["password"];
    // $studyNumber=$_POST["studyNumber"];
    // $studyPassword=$_POST["studyPassword"];
    // $major=$_POST["major"];

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];
    $userName=dataDeal($_POST["userName"]);
    $trueName=dataDeal($_POST["trueName"]);
    $newPassword=dataDeal($_POST["password"]);
    $studyNumber=dataDeal($_POST["studyNumber"]);
    $studyPassword=dataDeal($_POST["studyPassword"]);
    $major=dataDeal($_POST["major"]);
    $sql="update ".TABLE." set userName=\"$userName\",trueName=\"$trueName\",password=\"$newPassword\",studyNumber=\"$studyNumber\",studyPassword=\"$studyPassword\",major=\"$major\" where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if(!$result)
    {
        $final["status"]=ERROR;
        $final["information"]="update failed";
        $db->close();
        echo json_encode($final);
        return;
    }
    $final["status"]=SUCCESS;
    echo json_encode($final);

    //需要修改cookie的密码
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
    setcookie("password",$newPassword,$second,$dir);
    $db->close();
?>