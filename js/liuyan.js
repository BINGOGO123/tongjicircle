const URL_LIUYAN_ARTICLE="php/liuyan-article.php";
const URL_LIUYAN_ACTIVITY="php/liuyan-activity.php";

const LIUYAN_SUCCESS=0;
const LIUYAN_ERROR=1;

$(function(){
    $(".liuyan-input button").click(function(){
        let input=$(".liuyan-input textarea").val();
        if(input=="")
            return;
        if(input.length>300)
        {
            alert("留言内容过长");
            return;
        }
        if($(".attended.hidden").length)
        {
            alert("您尚未加入该活动，无法留言");
            return;
        }
    
        let submitObject=new FormData();
        let submitUrl;
        submitObject.append("content",input);
        if($("#aid").text()!="")
        {
            submitObject.append("aid",$("#aid").text());
            submitUrl=URL_LIUYAN_ARTICLE;
        }
        else
        {
            submitObject.append("yid",$("#yid").text());
            submitUrl=URL_LIUYAN_ACTIVITY;
        }
        fetch(submitUrl,{
            method:"post",
            body:submitObject
        }).then(function(response){
            return response.text();
        }).then(function(text){
            // document.body.innerHTML=text;
            let json=JSON.parse(text);
            if(json.status==LIUYAN_SUCCESS)
            {
                $(".liuyan-display").prepend(
                `<div class="liuyan-item liuyan-myself">
                    <img class="liuyan-head" src="php/head.php">
                    <div class="liuyan-info">
                        <div class="liuyan-name"><a href="record.php">${json.userName}</a></div>
                        <div class="liuyan-content">${json.input}</div>
                        <div class="liuyan-time">${json.commentTime}</div>
                        <div class="clear"></div>
                    </div>
                </div>
                `);
                $(".liuyan-input textarea").val("");
                $(".byte-display span").text(0);
                $(".byte-display span").css("color","");
            }
            else if(json.status==LIUYAN_ERROR)
            {
                alert("留言失败："+json.information);
            }
            else
            {
                alert("收到服务器未知消息");
            }
        });
    });

    $(".liuyan-input textarea").bind("input",function(){
        $(".byte-display span").text($(this).val().length);
        if($(this).val().length>300)
        {
            $(".byte-display span").css("color","red");
        }
        else
        {
            $(".byte-display span").css("color","");
        }
    });
});
