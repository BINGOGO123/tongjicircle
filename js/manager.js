const URL_DELETE="php/delete.php";

const DELETE_SUCCESS=0;
const DELETE_ERROR=1;

$(function () {
    getNav("#nav-bar");

    $(".move-in").click(function(){
        if($(this).attr("class").search("rotate")==-1)
        {
            $(".bar ul").animate({
                width:0
            },500,function(){
                $(".bar ul").css("display","none");
                $(".move-in").toggleClass("rotate");
            });
        }
        else
        {
            $(".bar ul").css("display","block");
            $(".bar ul").animate({
                width:"100px"
            },500,function(){
                $(".move-in").toggleClass("rotate");
            });
        }
    });

    $(".bar ul li").click(function(){
        if($(this).attr("class") != null && $(this).attr("class").search("clicked")!=-1)
            return;
        let parent=$(this).parent();
        let i = 0;
        for(;i<parent.children().length;i++)
        {
            if(parent.children()[i]==this)
                break;
        }
        $(this).siblings().removeClass("clicked");
        $(this).addClass("clicked");
        $(".table-region").removeClass("present");
        $(".table-region").eq(i).addClass("present");
    });

    $(".table-region button").click(function(){
        let para=$(this).parent().next().attr("type").split(" ");
        let value=$(this).parent().next().text().split(" ");
        let me=$(this).parent().parent();
        let submitObject=new FormData();
        for(let i = 0;i<para.length;i++)
        {
            submitObject.append(para[i],value[i]);
        }
        fetch(URL_DELETE,{
            method:"post",
            body:submitObject
        }).then(function(response){
            return response.text()
        }).then(function(text){
            // document.body.innerHTML=text;
            let json=JSON.parse(text);
            if(json.status==DELETE_SUCCESS)
            {
                me.slideUp(500,function(){
                    me.remove();
                });
            }
            else if(json.status=DELETE_ERROR)
            {
                alert("删除失败："+json.information);
            }
            else
            {
                alert("收到服务器为止消息");
            }
        });
    });
});