const LOVE_URL="php/love-article.php";
const AGREE_URL="php/agree-article.php";

const DO=0;
const UNDO=1;
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;

$(function(){
    var mySchedule = new Schedule({
		el: '#schedule-box'
    });

    getNav("#nav-bar");

    $("#love").click(function(){
        if($(this).attr("class").search("-o")==-1)
        {
            $("#love").removeClass("fa-heart");
            $("#love").addClass("fa-heart-o");
            $("#love + span").text(Number($("#love + span").text())-1);
            let submitObject=new FormData();
            submitObject.append("aid",$("#aid").text());
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
                    $("#love").removeClass("fa-heart-o");
                    $("#love").addClass("fa-heart");
                    $("#love + span").text(Number($("#love + span").text())+1);
                }
                else
                {
                    alert("收到服务器未知消息");
                    $("#love").removeClass("fa-heart-o");
                    $("#love").addClass("fa-heart");
                    $("#love + span").text(Number($("#love + span").text())+1);
                }
            });
        }
        else
        {
            $("#love").removeClass("fa-heart-o");
            $("#love").addClass("fa-heart");
            $("#love + span").text(Number($("#love + span").text())+1);
            let submitObject=new FormData();
            submitObject.append("aid",$("#aid").text());
            submitObject.append("action",DO);
            fetch(LOVE_URL,{
                method:"post",
                body:submitObject
            }).then(function(response){
                return response.text();
            }).then(function(text){
                // document.body.innerHTML=text;
                let json=JSON.parse(text);
                if(json.status==CHANGE_SUCCESS)
                {

                }
                else if(json.status==CHANGE_ERROR)
                {
                    alert("收藏失败："+json.information);
                    $("#love").removeClass("fa-heart");
                    $("#love").addClass("fa-heart-o");
                    $("#love + span").text(Number($("#love + span").text())-1);
                }
                else
                {
                    alert("收到服务器未知消息");
                    $("#love").removeClass("fa-heart");
                    $("#love").addClass("fa-heart-o");
                    $("#love + span").text(Number($("#love + span").text())-1);
                }
            });
        }
    });

    $("#agree").click(function(){
        if($(this).attr("class").search("-o")==-1)
        {
            $("#agree").removeClass("fa-thumbs-up");
            $("#agree").addClass("fa-thumbs-o-up");
            $("#agree + span").text(Number($("#agree + span").text())-1);
            let submitObject=new FormData();
            submitObject.append("aid",$("#aid").text());
            submitObject.append("action",UNDO);
            fetch(AGREE_URL,{
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
                    alert("取消点赞失败："+json.information);
                }
                else
                {
                    alert("收到服务器未知消息");
                }
            });
        }
        else
        {
            $("#agree").removeClass("fa-thumbs-o-up");
            $("#agree").addClass("fa-thumbs-up");
            $("#agree + span").text(Number($("#agree + span").text())+1);
            let submitObject=new FormData();
            submitObject.append("aid",$("#aid").text());
            submitObject.append("action",DO);
            fetch(AGREE_URL,{
                method:"post",
                body:submitObject
            }).then(function(response){
                return response.text();
            }).then(function(text){
                // document.body.innerHTML=text;
                let json=JSON.parse(text);
                if(json.status==CHANGE_SUCCESS)
                {
                }
                else if(json.status==CHANGE_ERROR)
                {
                    alert("点赞失败："+json.information);
                }
                else
                {
                    alert("收到服务器未知消息");
                }
            });
        }
    });
});