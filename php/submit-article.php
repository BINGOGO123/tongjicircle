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
    define("TABLE2","article",false);

    require("yasuo.php");
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

    //读取图片信息
    if($_FILES["articleImage"]["error"] > 0)
    {
        $final["status"]=ERROR;
        $final["information"]="image upload error";
        echo json_encode($final);
        return;
    }
    $changeName=reNameFile($_FILES["articleImage"]["tmp_name"],$_FILES["articleImage"]["type"]);
    $fileName=getThumb($changeName,400,400);
    $file=fopen($fileName,"r");
    if(!$file)
    {
        $final["status"]=ERROR;
        $final["information"]="open image failed";
        echo json_encode($final);
        return;
    }
    $articleImage=addslashes(fread($file,filesize($fileName)));
    fclose($file);
    //删除压缩后的图片
    unlink($changeName);
    unlink($fileName);
    $imageType=$_FILES["articleImage"]["type"];
    //其他内容
    // $title=$_POST["title"];
    // $introduction=$_POST["introduction"];
    // $content=$_POST["content"];
    // $type=$_POST["type"];
    // $articleTime=date("Y-m-d h:i:s");
    // $readNumber=0;
    $title=dataDeal($_POST["title"]);
    $introduction=dataDeal($_POST["introduction"]);
    $content=$_POST["content"];
    $type=dataDeal($_POST["type"]);
    $articleTime=dataDeal(date("Y-m-d h:i:s"));
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

    $query="select uid from ".TABLE1." where email=\"$email\" and password=\"$password\";";
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
        $uid=$row["uid"];
    }
    else
    {
        $final["status"]=ERROR;
        $final["information"]="email and password error!";
        echo json_encode($final);
        $sql->close();
        return;
    }

    //插入文章
    $query='insert into '.TABLE2.'(uid,title,introduction,content,articleImage,type,articleTime,readNumber,imageType) value('.$uid.',\''.$title.'\',\''.$introduction.'\',\''.$content.'\',\''.$articleImage.'\',\''.$type.'\',\''.$articleTime.'\',\''.$readNumber.'\',\''.$imageType.'\');';
    if(!$sql->query($query))
    {
        $final["status"]=ERROR;
        $final["information"]="insert failed：".strval($sql->error);
        echo json_encode($final);
        $sql->close();
        return;
    }
    $final["status"]=SUCCESS;

    //返回文章aid
    $query="select last_insert_id(aid) from ".TABLE2." order by aid desc;";
    $result=$sql->query($query);
    if($result==false)
    {
        $final["aid"]=-1;
    }
    else if($result->num_rows<=0)
    {
        $final["aid"]=-1;
    }
    else {
        $row=$result->fetch_assoc();
        $final["aid"]=$row["last_insert_id(aid)"];
    }
    $sql->close();
    echo json_encode($final);
?>