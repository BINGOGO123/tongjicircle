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
    define("TABLE3","activity",false);
    define("TABLE4","announcement",false);
    define("TABLE5","commentArticle",false);
    define("TABLE6","commentActivity",false);

    $final=array();
    if($_SERVER["REQUEST_METHOD"]!="POST")
    {
        $final["status"]=ERROR;
        $final["information"]="not POST!";
        echo json_encode($final);
        return;
    }

    //查询数据库
    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        $final["status"]=ERROR;
        $final["information"]="database linked failed!";
        echo json_encode($final);
        return;
    }

    if(isset($_POST["cid"]))
    {
        $cid=$_POST["cid"];
        if(isset($_POST["yid"]))
        {
            $yid=$_POST["yid"];
            $sql="delete from ".TABLE6." where cid=$cid and yid=$yid;";
        }
        else if(isset($_POST["aid"])) {
            $aid=$_POST["aid"];
            $sql="delete from ".TABLE5." where cid=$cid and aid=$aid;";
        }
        else {
            $final["status"]=ERROR;
            $final["information"]="no parameter";
            echo json_encode($final);
            $db->close();
            return;
        }
    }
    else if(isset($_POST["aid"]))
    {
        $aid=$_POST["aid"];
        $sql="delete from ".TABLE2." where aid=$aid;";
    }
    else if(isset($_POST["yid"]))
    {
        $yid=$_POST["yid"];
        $sql="delete from ".TABLE3." where yid=$yid;";
    }
    else if(isset($_POST["nid"]))
    {
        $nid=$_POST["nid"];
        $sql="delete from ".TABLE4." where nid=$nid;";
    }
    else {
        $final["status"]=ERROR;
        $final["information"]="no parameter";
        echo json_encode($final);
        $db->close();
        return;
    }
    if(!$db->query($sql))
    {
        $final["status"]=ERROR;
        $final["information"]="delete error";
        echo json_encode($final);
        $db->close();
        return;
    }
    $final["status"]=SUCCESS;
    echo json_encode($final);
    $db->close();
?>