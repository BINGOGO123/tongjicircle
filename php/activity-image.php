<?php
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","activity",false);
    define("DEFAULT_IMAGE","../images/article-image.jpg",false);

    if(isset($_REQUEST["yid"]))
    {
        $yid=intval($_GET["yid"]);
        $db=new mysqli(URL,USER,PASSWORD,DATABASE);
        if($db->connect_error)
        {
            //变成默认图片
            header("location:".DEFAULT_IMAGE);
            return;
        }

        $sql="select activityImage,imageType from ".TABLE." where yid=\"$yid\";";
        $result=$db->query($sql);
        if($result==false||$result->num_rows <= 0)
        {
            //变成默认图片
            header("location:".DEFAULT_IMAGE);
            $db->close();
            return;
        }

        $row=$result->fetch_assoc();
        if($row["activityImage"]==null || $row["imageType"]==null)
        {
            //变成默认图片
            header("location:".DEFAULT_IMAGE);
            $db->close();
            return;
        }
        
        //显示活动图片
        echo $row["activityImage"];
        header("Content-Type:".$row["imageType"]);

        $db->close();
        return;
    }
    else {
        //变成默认图片
        header("location:".DEFAULT_IMAGE);
    }
?>