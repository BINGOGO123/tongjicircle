let cookie=document.cookie;
if(cookie.indexOf("email")==-1 || cookie.indexOf("password")==-1)
    window.location.replace("login.html");

const SUBMIT_URL="php/submit-activity.php";
const SUBMIT_SUCCESS=0;
const SUBMIT_ERROR=1;

//编辑器
var editor;

$(function(){
    getNav("#nav-bar");

    var E = window.wangEditor;
    var editor = new E('#editor');
    editor.create();

    $(".img-box").hover(function(){
        $(".img-box .img-alert label").slideDown(500);
    });
    $(".img-box").mouseleave(function(){
        $(".img-box .img-alert label").slideUp(500);
    });

    $(".select-item").click(function(){
        $(this).siblings().removeClass("clicked");
        $(this).addClass("clicked");
    })

    //点击选择图片
    $(".img-box").click(function(){
        $("#image-choose").click();
    });

    $("#image-choose").bind("change",changeImg);
    
    //点击发布活动
    $(".submit-article").click(function(){
        if($("#image-choose").val()=="")
        {
            alert("请选择封面图片");
            return;
        }
        if($(".region .title").val()=="")
        {
            alert("请输入标题");
            return;
        }
        if($("#start-time").val()=="")
        {
            alert("请输入开始时间");
            return;
        }
        if($("#end-time").val()=="")
        {
            alert("请输入结束时间");
            return;
        }
        if($("#location").val()=="")
        {
            alert("请输入地点");
            return;
        }
        if($("#max-number").val()=="")
        {
            alert("请输入人数上限");
            return;
        }
        if(Number($("#max-number").val())<1)
        {
            alert("活动人数上限不能少于1");
            return;
        }
        let content=editor.txt.html();
        if(content == "" || content=="<p><br></p>")
        {
            alert("请输入活动内容");
            return;
        }
        let startTime=$("#start-time").val().replace("T"," ");
        let endTime=$("#end-time").val().replace("T"," ");
        if(compareTime(getDate(),getStrDate(startTime)) >= 0)
        {
            alert("活动开始时间不能在过去");
            return;
        }
        if(startTime>=endTime)
        {
            alert("活动开始时间晚于开始时间是不行的");
            return;
        }

        let submitObject=new FormData();
        if($("#image-choose")[0].files[0] !== undefined)
            submitObject.append("activityImage",$("#image-choose")[0].files[0]);
        submitObject.append("title",$(".region .title").val());
        submitObject.append("content",content);
        submitObject.append("type",$(".select-item.clicked").text());
        submitObject.append("startTime",startTime+":00");
        submitObject.append("endTime",endTime+":00");
        submitObject.append("location",$("#location").val());
        submitObject.append("maxNumber",$("#max-number").val());
        fetch(SUBMIT_URL,{
            method:"post",
            body:submitObject
        }).then(function(response){
            return response.text();
        }).then(function(text){
            // document.body.innerHTML=text;
            let json=JSON.parse(text);
            if(json.status==SUBMIT_SUCCESS)
            {
                if(json.yid==-1)
                {
                    window.location="article-display.php";
                }
                else
                {
                    window.location="activity.php?yid="+json.yid;
                }
            }
            else if(json.status==SUBMIT_ERROR)
            {
                alert("发布失败："+json.information);
            }
            else
            {
                alert("收到未知消息");
            }
        });
    });
});

//根据选择的图片更改显示效果
function changeImg()
{
    let file=this.files[0];
    let value=this.value;
    let array=value.split("\\");
    value=array[array.length-1];
    if(!(/.jpg$/i.test(value))&&!(/.png$/i.test(value))&&!(/.jpeg$/i.test(value)))
    {
        alert("请选择一张图片！格式jpg或jpeg或png！");
        this.value="";
        $(".img-box img").attr("src","images/article-image.jpg");
        return;
    }
    let oFReader=new FileReader();
    oFReader.readAsDataURL(file);
	oFReader.onloadend = function(oFRevent){
        $(".img-box img").attr("src",oFRevent.target.result);
    }
}

//获取当前时间，返回一个类
function getDate()
{
    let date=new Date();
    let final=new Object();
    final.year=date.getFullYear();
    final.month=date.getMonth()+1;
    final.day=date.getDate();
    final.minute=date.getMinutes();
    final.hour=date.getHours();
    final.second=date.getSeconds();
    return final;
}

//从2019-3-4 10:29中提取时间，返回一个类
function getStrDate(str)
{
    let final=new Object();
    let result=/(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2})/.exec(str);
    //如果str格式错误，那么我就随便返回一个时间好了，就返回当前时间好了
    if(result==null)
        return getDate();
    final.year=Number(result[1]);
    final.month=Number(result[2]);
    final.day=Number(result[3]);
    final.hour=Number(result[4]);
    final.minute=Number(result[5]);
    final.second=0;
    return final;
}

//比较时间大小t1>t2 return 1,t1<t2 return-1,t1==t2 return 0
function compareTime(t1,t2)
{
    if(t1.year>t2.year)
        return 1;
    else if(t1.year<t2.year)
        return -1;
    else
    {
        if(t1.month>t2.month)
            return 1;
        else if(t1.month<t2.month)
            return -1;
        else
        {
            if(t1.day>t2.day)
                return 1;
            else if(t1.day<t2.day)
                return -1;
            else
            {
                if(t1.hour>t2.hour)
                    return 1;
                else if(t1.hour<t2.hour)
                    return -1;
                else
                {
                    if(t1.minute>t2.minute)
                        return 1;
                    else if(t1.minute<t2.minute)
                        return -1;
                    else
                    {
                        return 0;
                    }
                }
            }
        }
    }
}