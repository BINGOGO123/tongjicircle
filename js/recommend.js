const LOVE_URL="php/love-announcement.php";

const DO=0;
const UNDO=1;
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;

$(function () {
    getNav("#nav-bar");

    //关于公告收藏功能
    $(".gonggao-about-button").click(function() {
        if($(this).children("i").attr("class").search("fa-star-o")!=-1)
        {
            $(this).children("i").removeClass("fa-star-o");
            $(this).children("i").addClass("fa-star");
            $(this).parent().parent().addClass("shoucang");
            let submitObject=new FormData();
            submitObject.append("nid",$(this).parent().siblings(".nid").text());
            submitObject.append("action",DO);
            fetch(LOVE_URL,{
                method:"post",
                body:submitObject
            }).then(function(response){
                return response.text();
            }).then(function(text){
                let json=JSON.parse(text);
                if(json.status==CHANGE_SUCCESS)
                {
                }
                else if(json.status==CHANGE_ERROR)
                {
                    alert("收藏失败："+json.information+"\n"+json.sql);
                    $(this).children("i").removeClass("fa-star");
                    $(this).children("i").addClass("fa-star-o");
                    $(this).parent().parent().removeClass("shoucang");
                }
                else
                {
                    alert("收到服务器未知消息");
                    $(this).children("i").removeClass("fa-star");
                    $(this).children("i").addClass("fa-star-o");
                    $(this).parent().parent().removeClass("shoucang");
                }
            });
        }
        else
        {
            $(this).children("i").removeClass("fa-star");
            $(this).children("i").addClass("fa-star-o");
            $(this).parent().parent().removeClass("shoucang");
            let submitObject=new FormData();
            submitObject.append("nid",$(this).parent().siblings(".nid").text());
            submitObject.append("action",UNDO);
            fetch(LOVE_URL,{
                method:"post",
                body:submitObject
            }).then(function(response){
                return response.text();
            }).then(function(text){
                let json=JSON.parse(text);
                if(json.status==CHANGE_SUCCESS)
                {
                }
                else if(json.status==CHANGE_ERROR)
                {
                    alert("取消收藏失败："+json.information);
                    $(this).children("i").removeClass("fa-star-o");
                    $(this).children("i").addClass("fa-star");
                    $(this).parent().parent().addClass("shoucang");
                }
                else
                {
                    alert("收到服务器未知消息");
                    $(this).children("i").removeClass("fa-star-o");
                    $(this).children("i").addClass("fa-star");
                    $(this).parent().parent().addClass("shoucang");
                }
            });
        }
    });

    let activity=$(".recommend-region .article.activity");
    let i;
    //为过期活动添加类别
    for(i=0;i<activity.length;i++)
    {
        let timeAct=getStrDate(activity.eq(i).find(".article-time-end").text());
        let date=getDate();
        if(compareTime(timeAct,date)==-1)
        {
            activity.eq(i).addClass("activity-outdate");
            activity.eq(i).find(".display-type").text("活动已过期");
            activity.eq(i).find(".display-type").addClass("display-type-activity-outdate");
        }
    }

        $(".user-search").click(function(){
            window.open($(this).find(".user-search-a")[0].href);
    });

    //设置详细信息按钮悬浮效果
    setArticleHover();

    $(".gonggao-item").click(function(e){
        let target=e.target;
        if(e.target.className.search("gonggao-about-button")!=-1 || e.target.className.search("star")!=-1)
            return;
        window.open("announcement.php?nid="+$(this).find(".nid").text());
    });

    $(".add").hover(function(){
        $(this).removeClass("fa-plus");
        $(this).addClass("fa-plus-circle");
        $(this).css("color","red");
    });
    $(".add").mouseleave(function(){
        $(this).removeClass("fa-plus-circle");
        $(this).addClass("fa-plus");
        $(this).css("color","");
    });
});

//设置详细信息按钮悬浮效果
function setArticleHover()
{
    $(".article .article-info").hover(function(){
        $(this).parent().parent().parent().parent().parent().children(".detail-box").css("display","block");
    });

    for(let i = 0;i<$(".article .detail-box").length;i++)
        $(".article .detail-box")[i].onmouseleave=function() {
            $(this).css("display","none");
        }
}

//获取当前时间，返回一个类
function getDate()
{
    let date=new Date();
    let final=new Object();
    final.year=date.getFullYear();
    final.month=date.getMonth() + 1;
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