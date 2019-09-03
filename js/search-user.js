const URL_SEARCH_USER="php/user-find.php";

const SEARCH_SUCCESS=0;
const SEARCH_ERROR=1;

$(function () {
    getNav("#nav-bar");

    $(".title-line i").hover(function(){
        $(this).removeClass("fa-times");
        $(this).addClass("fa-times-circle");
    });
    $(".title-line i").bind("mouseleave",function(){
        $(this).removeClass("fa-times-circle");
        $(this).addClass("fa-times");
    });
    $(".title-line i").bind("click",function(){
        $(this).parents(".recommend-box").height($(this).parents(".recommend-box").height());
        $(this).parents(".recommend-box").css({
            "margin-left":"auto",
            "margin-right":"auto"
        });
        $(this).parents(".recommend-box").animate({
            width:0
        },800,function(){
            $(this).remove();
        });
    });

    //点击整个框进行跳转
    setGo();

    //点击查询按钮
    $("#go-search").click(function(){
        let value=$("#search-input").val();
        if(value=="")
            return;
        let submitObject=new FormData();
        submitObject.append("value",value);
        fetch(URL_SEARCH_USER,{
            method:"post",
            body:submitObject
        }).then(function(response){
            return response.text();
        }).then(function(text){
            // document.body.innerHTML=text;
            let json=JSON.parse(text);
            if(json.status==SEARCH_SUCCESS)
            {
                $(".recommend-box").remove();
                $(".display-box").remove();
                $(".nothing").remove();
                let newUser;
                if(json.result.length<=0)
                    newUser=`<div class="nothing">没有相关用户呢...</div>`;
                else
                {
                    newUser='<div class="display-box">';
                    for(let i = 0;i<json.result.length;i++)
                    {
                        if(json.result[i].major=="")
                            json.result[i].major="未填写";
                        newUser+=`
                        <div class="user-search">
                            <img src="php/head.php?uid=${json.result[i].uid}" alt="用户头像" />
                            <div class="user-search-right">
                                <div class="user-search-name">${json.result[i].userName}</div>
                                <div class="user-search-major">${json.result[i].major}</div>
                            </div>
                            <a href="record.php?uid=${json.result[i].uid}" class="user-search-a"></a>
                        </div>
                        `;
                    }
                    newUser+="</div>";
                }
                $(".container-fluid").append(newUser);
                setGo();
            }
            else if(json.status==SEARCH_ERROR)
            {
                alert("查询失败："+json.information);
                return;
            }
            else
            {
                alert("收到服务器未知消息");
                return;
            }
        });
    });
});

function setGo()
{
    $(".user-search").click(function(){
        window.open($(this).find(".user-search-a")[0].href);
    });
}