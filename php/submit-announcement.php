<?php
    //本脚本实现发布文章功能

    //设置时区为上海
    date_default_timezone_set('Asia/Shanghai');

    //状态定义
    define("ERROR",1);
    define("SUCCESS",0);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","userInformation",false);
    define("TABLE2","announcement",false);
    define("TABLE3","manager",false);

    require("data-deal.php");

    $final=array();
    if($_SERVER["REQUEST_METHOD"]!="POST")
    {
        $final["status"]=ERROR;
        $final["information"]="not POST!";
        echo json_encode($final);
        return;
    }

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];

    // $title=$_POST["title"];
    // $content=$_POST["content"];
    // $announcementTime=date("Y-m-d h:i:s");
    // $readNumber=0;
    $title=dataDeal($_POST["title"]);
    $content=$_POST["content"];
    $announcementTime=dataDeal(date("Y-m-d h:i:s"));
    $readNumber=0;

    //从数据库取得信息
    $sql=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($sql->connect_error)
    {
        $final["status"]=ERROR;
        $final["information"]="database linked failed!";
        echo json_encode($final);
        return;
    }

    $query="select mid from ".TABLE1.",".TABLE3." where email=\"$email\" and password=\"$password\" and ".TABLE1.".uid=".TABLE3.".uid;";
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
        $mid=$row["mid"];
    }
    else
    {
        $final["status"]=ERROR;
        $final["information"]="email and password error!";
        echo json_encode($final);
        $sql->close();
        return;
    }

    //插入公告
    $query='insert into '.TABLE2.'(mid,title,content,announcementTime,readNumber) value('.$mid.',\''.$title.'\',\''.$content.'\',\''.$announcementTime.'\',\''.$readNumber.'\');';
    if(!$sql->query($query))
    {
        $final["status"]=ERROR;
        $final["information"]="insert failed：".strval($sql->error);
        echo json_encode($final);
        $sql->close();
        return;
    }
    $final["status"]=SUCCESS;

    //返回公告nid
    $query="select last_insert_id(nid) from ".TABLE2." order by nid desc;";
    $result=$sql->query($query);
    if($result==false)
    {
        $final["nid"]=-1;
    }
    else if($result->num_rows<=0)
    {
        $final["nid"]=-1;
    }
    else {
        $row=$result->fetch_assoc();
        $final["nid"]=$row["last_insert_id(nid)"];
    }
    $sql->close();
    echo json_encode($final);
?>