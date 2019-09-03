<?php

    //返回导航栏所必须的信息
    define("ERROR",1);
    define("SUCCESS",0);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);
    define("TABLE1","manager",false);

    $final=array();
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
        $final["information"]="not POST！";
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

    $email=$_COOKIE["email"];
    $password=$_COOKIE["password"];
    $sql="select userName,trueName,studyNumber,userDate,major,uid from ".TABLE." where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if(!$result || $result->num_rows <= 0)
    {
        $final["status"]=ERROR;
        $final["information"]="select failed or email + password error?";
        $db->close();
        echo json_encode($final);
        return;
    }
    $row=$result->fetch_assoc();

    $uid=$row["uid"];
    $final["status"]=SUCCESS;
    $final["alert"]=array();
    $final["alert"][0]=array("href"=>"javascript:void(0)","time"=>"2019-5-1","str"=>"公告1","content"=>"巴拉巴拉巴拉1");
    // $final["alert"][1]=array("href"=>"javascript:void(0)","time"=>"2019-4-25","str"=>"公告2","content"=>"巴拉巴拉巴拉2");
    // $final["alert"][2]=array("href"=>"javascript:void(0)","time"=>"2019-4-23","str"=>"公告3","content"=>"巴拉巴拉巴拉3");
    $final["person"]=array("name"=>$row["userName"],"trueName"=>$row["trueName"],"order"=>$row["studyNumber"],"email"=>$email,"head"=>"php/head.php","time"=>$row["userDate"],"major"=>$row["major"]);
    $sql="select * from ".TABLE1." where uid=$uid;";
    $result=$db->query($sql);
    if($result && $result->num_rows>0)
    {
        $final["manager"]=true;
    }
    else {
        $final["manager"]=false;
    }

    echo json_encode($final);
?>