<?php
    //本脚本实现登出功能
    //获取绝对目录
    $root=$_SERVER["DOCUMENT_ROOT"];
    //将目录中的所有\变为/，这是为了linux上和windows上同样运行
    for($i = 0;$i < strlen($root);$i++)
    {
        if($root[$i]=="\\")
            $root[$i]="/";
    }
    //获取上一级目录的真实目录，因为cookie要存到上一级以便使用且cookie不支持相对路径
    $cookieDir=realpath("..");
    for($i = 0;$i < strlen($cookieDir);$i++)
    {
        if($cookieDir[$i]=="\\")
            $cookieDir[$i]="/";
    }
    //取得相对于网站根目录的绝对路径
    $dir=substr($cookieDir,strlen($root));
    if($dir=="" || $dir==null || $dir==false)
        $dir="/";

    //清除所有cookie
    setcookie("email","",0,$dir);
    setcookie("password","",0,$dir);

    //跳转到登录界面
    header("location:../login.html");
?>