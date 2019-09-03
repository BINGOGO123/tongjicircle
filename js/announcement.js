const LOVE_URL="php/love-announcement.php";

const DO=0;
const UNDO=1;
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;

$(function(){
    getNav("#nav-bar");

    $("#love").click(function(){
        if($(this).attr("class").search("-o")==-1)
        {
            $("#love").removeClass("fa-star");
            $("#love").addClass("fa-star-o");
            $("#love-num").text(Number($("#love-num").text())-1);

            let submitObject=new FormData();
            submitObject.append("nid",$("#nid").text());
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
                    $("#love").removeClass("fa-star-o");
                    $("#love").addClass("fa-star");
                    $("#love-num").text(Number($("#love-num").text())+1);
                }
                else
                {
                    alert("收到服务器未知消息");
                    $("#love").removeClass("fa-star-o");
                    $("#love").addClass("fa-star");
                    $("#love-num").text(Number($("#love-num").text())+1);
                }
            });
        }
        else
        {
            $("#love").removeClass("fa-star-o");
            $("#love").addClass("fa-star");
            $("#love-num").text(Number($("#love-num").text())+1);
            let submitObject=new FormData();
            submitObject.append("nid",$("#nid").text());
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
                    $("#love").removeClass("fa-star");
                    $("#love").addClass("fa-star-o");
                    $("#love-num").text(Number($("#love-num").text())-1);
                }
                else
                {
                    alert("收到服务器未知消息");
                    $("#love").removeClass("fa-star");
                    $("#love").addClass("fa-star-o");
                    $("#love-num").text(Number($("#love-num").text())-1);
                }
            });
        }
    });
});