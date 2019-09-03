<?php
    //这个脚本用于搜索用户

    //状态信息
    define("SUCCESS",0,false);
    define("ERROR",1,false);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);

    require("data-deal.php");

    //如果不是post方式直接error
    if($_SERVER["REQUEST_METHOD"]!="POST")
    {
        $final["status"]=ERROR;
        $final["information"]="not POST！";
        echo json_encode($final);
        return;
    }

    if(isset($_POST["value"]))
        $value=dataDeal($_POST["value"]);
    else {
        $final["status"]=ERROR;
        $final["information"]="parameter error！";
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
    $sql="select uid,userName,major from ".TABLE." where userName like \"%$value%\" or major like \"%$value%\";";
    $result=$db->query($sql);
    if(!$result)
    {
        $final["status"]=ERROR;
        $final["information"]="select error";
        echo json_encode($final);
        $db->close();
        return;
    }
    $final["status"]=SUCCESS;
    $final["result"]=array();
    for($i=0;$i<$result->num_rows;$i++)
    {
        $row=$result->fetch_assoc();
        $final["result"][$i]=array("uid"=>$row["uid"],"userName"=>$row["userName"],"major"=>$row["major"]);
    }
    $db->close();
    echo json_encode($final);
?>