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
    define("TABLE2","activity",false);
    define("TABLE3","loveActivity",false);

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
    if($_FILES["activityImage"]["error"] > 0)
    {
        $final["status"]=ERROR;
        $final["information"]="image upload error";
        echo json_encode($final);
        return;
    }
    //压缩图片
    $changeName=reNameFile($_FILES["activityImage"]["tmp_name"],$_FILES["activityImage"]["type"]);
    $fileName=getThumb($changeName,400,400);
    $file=fopen($fileName,"r");
    if(!$file)
    {
        $final["status"]=ERROR;
        $final["information"]="open image failed";
        echo json_encode($final);
        return;
    }
    $activityImage=addslashes(fread($file,filesize($fileName)));
    fclose($file);
    //删除压缩后的图片
    unlink($changeName);
    unlink($fileName);
    $imageType=$_FILES["activityImage"]["type"];
    //其他内容
    // $title=$_POST["title"];
    // $content=$_POST["content"];
    // $type=$_POST["type"];
    // $activityTime=date("Y-m-d h:i:s");
    // $startTime=$_POST["startTime"];
    // $endTime=$_POST["endTime"];
    // $location=$_POST["location"];
    // $maxNum=$_POST["maxNumber"];
    // $readNumber=0;

    $title=dataDeal($_POST["title"]);
    $content=$_POST["content"];
    $type=dataDeal($_POST["type"]);
    $activityTime=dataDeal(date("Y-m-d h:i:s"));
    $startTime=dataDeal($_POST["startTime"]);
    $endTime=dataDeal($_POST["endTime"]);
    $location=dataDeal($_POST["location"]);
    $maxNum=dataDeal($_POST["maxNumber"]);
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

    //插入活动
    $query='insert into '.TABLE2.'(uid,title,content,activityImage,type,activityTime,readNumber,imageType,startTime,endTime,location,maxNum) value('.$uid.',\''.$title.'\',\''.$content.'\',\''.$activityImage.'\',\''.$type.'\',\''.$activityTime.'\',\''.$readNumber.'\',\''.$imageType.'\',\''.$startTime.'\',\''.$endTime.'\',\''.$location.'\',\''.$maxNum.'\');';
    if(!$sql->query($query))
    {
        $final["status"]=ERROR;
        $final["information"]="insert failed：".strval($sql->error);
        echo json_encode($final);
        $sql->close();
        return;
    }
    $final["status"]=SUCCESS;

    //返回活动yid
    $query="select last_insert_id(yid) from ".TABLE2." order by yid desc;";
    $result=$sql->query($query);
    if($result==false)
    {
        $final["yid"]=-1;
    }
    else if($result->num_rows<=0)
    {
        $final["yid"]=-1;
    }
    else {
        $row=$result->fetch_assoc();
        $final["yid"]=$row["last_insert_id(yid)"];
        $yid=$final["yid"];
        //创建人自动加入活动
        $query="insert into ".TABLE3." (uid,yid,loveTime) value($uid,$yid,\"$activityTime\");";
        $sql->query($query);
    }
    $sql->close();
    echo json_encode($final);
?>