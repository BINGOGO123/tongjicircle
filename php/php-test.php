<?php
    // date_default_timezone_set('Asia/Shanghai');
    // echo date("Y-m-d h:i:s");

    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);
    define("DEFAULT_IMAGE","../images/default.png",false);

    $email=$_REQUEST["email"];

    $db=new mysqli(URL,USER,PASSWORD,DATABASE);
    if($db->connect_error)
    {
        //变成默认头像
        header("location:".DEFAULT_IMAGE);
        return;
    }

    $sql="select headImage,imageType from ".TABLE." where email=\"$email\";";
    $result=$db->query($sql);
    if($result==false||$result->num_rows <= 0)
    {
        //变成默认头像
        header("location:".DEFAULT_IMAGE);
        $db->close();
        return;
    }

    $row=$result->fetch_assoc();
    echo $row["headImage"];
    echo $row["imageType"];
    var_dump($row["headImage"]);
    var_dump($row["imageType"]);
    // header("Content-Type:".$row["ImageType"]);

    $db->close();
?>