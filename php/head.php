<?php
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);
    define("DEFAULT_IMAGE","../images/default.png",false);

    // if(!$SERVER["REQUEST_METHOD"]=="GET")
    // {
    //     //变成默认头像
    //     header("location:".DEFAULT_IMAGE);
    //     return;
    // }

    if(isset($_REQUEST["uid"]))
    {
        $uid=intval($_GET["uid"]);
        $db=new mysqli(URL,USER,PASSWORD,DATABASE);
        if($db->connect_error)
        {
            //变成默认头像
            header("location:".DEFAULT_IMAGE);
            return;
        }

        $sql="select headImage,imageType from ".TABLE." where uid=\"$uid\";";
        $result=$db->query($sql);
        if($result==false||$result->num_rows <= 0)
        {
            //变成默认头像
            header("location:".DEFAULT_IMAGE);
            $db->close();
            return;
        }

        $row=$result->fetch_assoc();
        if($row["headImage"]==null || $row["imageType"]==null)
        {
            //变成默认头像
            header("location:".DEFAULT_IMAGE);
            $db->close();
            return;
        }
        
        //显示用户头像
        echo $row["headImage"];
        header("Content-Type:".$row["imageType"]);

        $db->close();
        return;
    }

    $email=$_COOKIE["email"];

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
    if($row["headImage"]==null || $row["imageType"]==null)
    {
        //变成默认头像
        header("location:".DEFAULT_IMAGE);
        $db->close();
        return;
    }

    //显示用户头像
    echo $row["headImage"];
    header("Content-Type:".$row["imageType"]);
    $db->close();
?>