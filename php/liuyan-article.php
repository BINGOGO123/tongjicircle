<?php
    //这个脚本用于收藏和取消收藏文章

    //设置时区为上海
    date_default_timezone_set('Asia/Shanghai');

    //状态信息
    define("SUCCESS",0,false);
    define("ERROR",1,false);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE1","userInformation",false);
    define("TABLE2","commentArticle",false);

    require("data-deal.php");

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
    $sql="select uid,userName from ".TABLE1." where email=\"$email\" and password=\"$password\";";
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
    $userName=$row["userName"];
    // $aid=$_POST["aid"];
    // $content=$_POST["content"];
    $aid=dataDeal($_POST["aid"]);
    $content=dataDeal($_POST["content"]);
    $commentTime=date("Y-m-d h:i:s");
    $sql="select max(cid) from ".TABLE2." where aid=$aid;";
    $result=$db->query($sql);
    if(!$result || $result->num_rows<=0)
    {
        $cid=0;
    }
    else {
        $row=$result->fetch_assoc();
        $cid=$row["max(cid)"];
        if($cid==null)
            $cid=0;
        else {
            $cid++;
        }
    }
    $sql="insert into ".TABLE2." (uid,aid,cid,cidTarget,content,commentTime) value($uid,$aid,$cid,-1,\"$content\",\"$commentTime\");";
    // echo $sql;
    if(!$db->query($sql))
    {
        $final["status"]=ERROR;
        $final["information"]="insert error";
        echo json_encode($final);
        $db->close();
        return;
    }
    $final["status"]=SUCCESS;
    $final["commentTime"]=substr($commentTime,0,-3);
    $final["userName"]=$userName;
    $final["input"]=$content;
    $db->close();
    echo json_encode($final);
?>