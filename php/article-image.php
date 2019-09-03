<?php
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","article",false);
    define("DEFAULT_IMAGE","../images/article-image.jpg",false);

    if(isset($_REQUEST["aid"]))
    {
        $aid=intval($_GET["aid"]);
        $db=new mysqli(URL,USER,PASSWORD,DATABASE);
        if($db->connect_error)
        {
            //变成默认图片
            header("location:".DEFAULT_IMAGE);
            return;
        }

        $sql="select articleImage,imageType from ".TABLE." where aid=\"$aid\";";
        $result=$db->query($sql);
        if($result==false||$result->num_rows <= 0)
        {
            //变成默认图片
            header("location:".DEFAULT_IMAGE);
            $db->close();
            return;
        }

        $row=$result->fetch_assoc();
        if($row["articleImage"]==null || $row["imageType"]==null)
        {
            //变成默认图片
            header("location:".DEFAULT_IMAGE);
            $db->close();
            return;
        }
        
        //显示文章图片
        echo $row["articleImage"];
        header("Content-Type:".$row["imageType"]);

        $db->close();
        return;
    }
    else {
        //变成默认图片
        header("location:".DEFAULT_IMAGE);
    }
?>