<?php
    //本脚本实现注册功能

    //设置时区为上海
    date_default_timezone_set('Asia/Shanghai');

    //状态定义
    define("ERROR",2);
    define("EMAIL_REPEAT",1);
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
        $final["information"]="not POST!";
        echo json_encode($final);
        return;
    }

    //从数据库取得信息
    $sql=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($sql->connect_error)
    {
        $final["status"]=ERROR;
        $final["information"]="database linked failed!";
        echo json_encode($final);
        return;
    }
    // $email=$_POST["reg-email"];
    // $password=$_POST["reg-password"];
    // $userName=$_POST["reg-userName"];
    $email=dataDeal($_POST["reg-email"]);
    $password=dataDeal($_POST["reg-password"]);
    $userName=dataDeal($_POST["reg-userName"]);

    $query="select count(*) from ".TABLE." where email="."\"".$email."\"".";";
    $result=$sql->query($query);
    if($result==false)
    {
        $final["status"]=ERROR;
        $final["information"]="select failed!";
        echo json_encode($final);
        $sql->close();
        return;
    }
    if($result->num_rows>0)
    {
        $row=$result->fetch_assoc();
        if(intval($row["count(*)"]) > 0)
        {
            $final["status"]=EMAIL_REPEAT;
            echo json_encode($final);
            $sql->close();
            return;
        }
        else
        {
            $final["status"]=SUCCESS;
        }
    }
    else
    {
        $final["status"]=SUCCESS;
    }

    //未指定的内容取默认值
    $trueName="";
    $major="";
    $studyNumber="";
    $studyPassword="";
    $userDate=date("Y-m-d");
    $query="insert into ".TABLE." (email,password,userName,trueName,major,studyNumber,studyPassword,userDate) value(\"$email\",\"$password\",\"$userName\",\"$trueName\",\"$major\",\"$studyNumber\",\"$studyPassword\",\"$userDate\");";
    if(!$sql->query($query))
    {
        $final["status"]=ERROR;
        $final["information"]="insert failed：".strval($sql->error);
    }
    $sql->close();
    echo json_encode($final);
?>