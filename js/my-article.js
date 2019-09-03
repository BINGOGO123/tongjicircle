var nthTabs;

const LOVE_URL="php/love-announcement.php";

const DO=0;
const UNDO=1;
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;

const DEC=1;
const ASC=2;
const NONE=0;
//是否显示过期活动
var showOutdate=true;
//表示时间排序状况
var timeRange=NONE;
//表示热度排序状况
var hotRange=NONE;

//记录标签数目
let counter=5;

$(function () {
    getNav("#nav-bar");

    let activity=$(".box-module .article.activity");
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

    nthTabs = $("#editor-tabs").nthTabs();
    nthTabs.addTab({
        id:"a0",
        title:"我发布的文章",
        content:$("#box-module-my-article").html(),
        allowClose:false
    }).addTab({
        id:"a1",
        title:"我创建的活动",
        content:$("#box-module-my-activity").html(),
        allowClose:false
    }).addTab({
        id:"a2",
        title:"我收藏的文章",
        content:$("#box-module-love-article").html(),
        allowClose:false
    }).addTab({
        id:"a3",
        title:"我收藏的活动",
        content:$("#box-module-love-activity").html(),
        allowClose:false
    }).addTab({
        id:"a4",
        title:"我收藏的公告",
        content:$("#box-module-love-gonggao").html(),
        allowClose:false
    });
    nthTabs.setActTab("#a0");

    //关于公告收藏功能
    $(".gonggao-about-button").click(function() {
        if(confirm("是否取消收藏？\n将从该列表中删除这条公告。"))
        {
            $(this).parents(".gonggao-item").slideUp(500);
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
                    $(this).parents(".gonggao-item").slideDown(500);
                }
                else
                {
                    alert("收到服务器未知消息");
                    $(this).parents(".gonggao-item").slideDown(500);
                }
            });
        }
    });

    //设置悬浮效果
    setArticleHover();

    //按照时间排序
    $(".tab-location").click(sortByTime);
    //按照热度排序
    $(".tab-close-current").click(sortByHot);

    $(".gonggao-item").click(function(e){
        let target=e.target;
        if(e.target.className.search("gonggao-about-button")!=-1 || e.target.className.search("star")!=-1)
            return;
        window.open("announcement.php?nid="+$(this).find(".nid").text());
    });
});

//设置详细信息按钮悬浮效果
function setArticleHover()
{
    $(".tab-list toggle-tab").click(function(){
        $(".presentation.actve a").addClass("active");
    });

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
    //如果str格式错误，那么考虑不要时和分的情况，如果还是不行那么我就随便返回一个时间好了，就返回当前时间好了
    if(result==null)
    {
        let result=/(\d{4})-(\d{1,2})-(\d{1,2})/.exec(str);
        if(result==null)
            return getDate();
        final.year=Number(result[1]);
        final.month=Number(result[2]);
        final.day=Number(result[3]);
        final.hour=0;
        final.minute=0;
        return final;
    }
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

//比较时间，反向
function compareTimeSub(time1,time2)
{
    return compareTime(time2,time1);
}

//通过时间排序
function sortByTime()
{
    let sortFun;
    if(timeRange==NONE || timeRange==ASC)
    {
        sortFun=compareTime;
        timeRange=DEC;
        hotRange=NONE;
    }
    else
    {
        sortFun=compareTimeSub;
        timeRange=ASC;
        hotRange=NONE;
    }

    for(let i = 0;i<counter;i++)
    {
        let module=$("#a"+i.toString());
        if(module.length==0)
        {
            continue;
        }
        if(module.children().length==0)
        {
            continue;
        }
        if(module.children().eq(0).attr("class").search("gonggao")!=-1)
        {
            let article=module.find(".gonggao-item");
            let array=new Array();
            for(let j = 0;j<article.length;j++)
            {
                let time;
                time=getStrDate(article.eq(j).find(".gonggao-time").text());
                time.order=j;
                array.push(time);
            }
            array.sort(sortFun);

            for(let k = 0;k<array.length;k++)
            {
                module.children().append(article.eq(array[k].order));
            }
        }
        else
        {
            let article=module.find(".article");
            let array=new Array();
            for(let j = 0;j<article.length;j++)
            {
                let time;
                if(article.eq(j).attr("class").search("activity")==-1)
                    time=getStrDate(article.eq(j).find(".article-time").text());
                else
                    time=getStrDate(article.eq(j).find(".article-time-start").text());
                time.order=j;
                array.push(time);
            }
            array.sort(sortFun);

            for(let k = 0;k<array.length;k++)
            {
                module.children().eq(Math.floor(k/2)).children().eq(k%2).append(article.eq(array[k].order));
            }
        }
    }
}

//比较大小
function compareNumber(num1,num2)
{
    if(num1.number>num2.number)
        return 1;
    else if(num1.number<num2.number)
        return -1;
    return 0;
}

//反向比较大小
function compareNumberSub(num1,num2)
{
    return compareNumber(num2,num1);
}

//通过热度排序
function sortByHot()
{
    let sortFun;
    if(hotRange==NONE || hotRange==ASC)
    {
        sortFun=compareNumber;
        hotRange=DEC;
        timeRange=NONE;
    }
    else
    {
        sortFun=compareNumberSub;
        hotRange=ASC;
        timeRange=NONE;
    }

    for(let i = 0;i<counter;i++)
    {
        let module=$("#a"+i.toString());
        if(module.length==0)
        {
            continue;
        }
        if(module.children().length==0)
        {
            continue;
        }

        //如果说是公告模块则直接略过
        if(module.children().eq(0).attr("class").search("gonggao")!=-1)
            continue;

        let article=module.find(".article");
        let array=new Array();
        for(let j = 0;j<article.length;j++)
        {
            let num=new Object();
            if(article.eq(j).attr("class").search("activity")==-1)
                num.number=Number(article.eq(j).find(".fa-heart + span").text());
            else
                num.number=Number(article.eq(j).find(".attendence span:nth-of-type(1)").text());
            num.order=j;
            array.push(num);
        }
        array.sort(sortFun);

        for(let k = 0;k<array.length;k++)
        {
            module.children().eq(Math.floor(k/2)).children().eq(k%2).append(article.eq(array[k].order));
        }
    }
}
