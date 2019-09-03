<?php
    //这个脚本用于收藏和取消收藏文章

    //设置时区为上海
    date_default_timezone_set('Asia/Shanghai');

    //状态信息
    define("SUCCESS",0,false);
    define("ERROR",1,false);
    define("AGREE",0,false);
    define("UNAGREE",1,false);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","userInformation",false);
    define("TABLE2","agreeActivity",false);

    //如果不是post方式直接error
    if($_SERVER["REQUEST_METHOD"]!="POST")
    {
        $final["status"]=ERROR;
        $final["information"]="not POST！";
        echo json_encode($final);
        return;
    }

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];

    //查询数据库
    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        $final["status"]=ERROR;
        $final["information"]="database linked failed!";
        echo json_encode($final);
        return;
    }
    $sql="select uid from ".TABLE1." where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if(!$result || $result->num_rows <= 0)
    {
        $final["status"]=ERROR;
        $final["information"]="select error";
        echo json_encode($final);
        $db->close();
        return;
    }
    $row=$result->fetch_assoc();
    $uid=$row["uid"];
    $yid=$_POST["yid"];
    if($_POST["action"]==AGREE)
    {
        $agreeTime=date("Y-m-d h:i:s");
        $sql="insert into ".TABLE2." (uid,yid,agreeTime) value($uid,$yid,\"$agreeTime\");";
        if(!$db->query($sql))
        {
            $final["status"]=ERROR;
            $final["information"]="insert error";
        }
        else {
            $final["status"]=SUCCESS;
        }
    }
    else {
        $sql="delete from ".TABLE2." where uid=$uid and yid=$yid;";
        if(!$db->query($sql))
        {
            $final["status"]=ERROR;
            $final["information"]="delete error";
        }
        else {
            $final["status"]=SUCCESS;
        }
    }
    $db->close();
    echo json_encode($final);
?>