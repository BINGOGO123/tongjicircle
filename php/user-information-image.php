<?php
    //用以修改用户头像的脚本

    //状态定义
    define("SUCCESS",0,false);
    define("ERROR",1,false);

    //服务器信息
    define("URL","localhost",false);
    define("USER","root",false);
    define("PASSWORD","mydb",false);
    define("DATABASE","tongjiCircle",false);
    define("TABLE","userInformation",false);

    require("yasuo.php");

    $final=array();
    //如果未登录或者登录已经过期
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
        $final["information"]="request is not post";
        echo json_encode($final);
        return;
    }

    //读取图片信息
    if($_FILES["headImage"]["error"] > 0)
    {
        $final["status"]=ERROR;
        $final["information"]="image upload error";
        echo json_encode($final);
        return;
    }
    /*
        image/jpeg 保存为jpg文件
        image/png  保存为png文件
        image/x-icon 保存为ico文件
        image/gif   保存为gif文件
    */
    //先压缩图片
    // echo $_FILES["headImage"]["tmp_name"];
    // echo "<br />";
    // echo $_FILES["headImage"]["type"];
    $changeName=reNameFile($_FILES["headImage"]["tmp_name"],$_FILES["headImage"]["type"]);
    $fileName=getThumb($changeName,100,100);

    // var_dump($fileName);
    $file=fopen($fileName,"r");
    if(!$file)
    {
        $final["status"]=ERROR;
        $final["information"]="open image failed";
        echo json_encode($final);
        return;
    }
    $headImage=addslashes(fread($file,filesize($fileName)));
    fclose($file);

    //删除压缩后的图片
    unlink($changeName);
    unlink($fileName);
    $imageType=$_FILES["headImage"]["type"];

    //修改数据库
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
    $sql="update ".TABLE." set headImage=\"$headImage\",imageType=\"$imageType\" where email=\"$email\" and password=\"$password\";";
    $result=$db->query($sql);
    if(!$result)
    {
        $final["status"]=ERROR;
        $final["information"]="update failed";
        $db->close();
        echo json_encode($final);
        return;
    }
    $final["status"]=SUCCESS;
    echo json_encode($final);
    $db->close();
?>