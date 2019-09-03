const LOVE_URL="php/love-activity.php";
const AGREE_URL="php/agree-activity.php";

const DO=0;
const UNDO=1;
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;
const CHANGE_OVERFLOW=2;

$(function(){
    getNav("#nav-bar");

    // $("#love").click(function(){
    //     if($(this).attr("class").search("-o")==-1)
    //     {
    //         $("#love").removeClass("fa-heart");
    //         $("#love").addClass("fa-heart-o");
    //         $("#love + span").text(Number($("#love + span").text())-1);
    //         let submitObject=new FormData();
    //         submitObject.append("yid",$("#yid").text());
    //         submitObject.append("action",UNDO);
    //         fetch(LOVE_URL,{
    //             method:"post",
    //             body:submitObject
    //         }).then(function(response){
    //             return response.text();
    //         }).then(function(text){
    //             let json=JSON.parse(text);
    //             if(json.status==CHANGE_SUCCESS)
    //             {
    //             }
    //             else if(json.status==CHANGE_ERROR)
    //             {
    //                 alert("退出失败："+json.information);
    //                 $("#love").removeClass("fa-heart-o");
    //                 $("#love").addClass("fa-heart");
    //                 $("#love + span").text(Number($("#love + span").text())+1);
    //             }
    //             else
    //             {
    //                 alert("收到服务器未知消息");
    //                 $("#love").removeClass("fa-heart-o");
    //                 $("#love").addClass("fa-heart");
    //                 $("#love + span").text(Number($("#love + span").text())+1);
    //             }
    //         });
    //     }
    //     else
    //     {
    //         if($("#present-num").text() >= $("#max-num").text())
    //         {
    //             alert("人数已满，无法加入");
    //             return;
    //         }
    //         $("#love").removeClass("fa-heart-o");
    //         $("#love").addClass("fa-heart");
    //         $("#love + span").text(Number($("#love + span").text())+1);
    //         let submitObject=new FormData();
    //         submitObject.append("yid",$("#yid").text());
    //         submitObject.append("action",DO);
    //         fetch(LOVE_URL,{
    //             method:"post",
    //             body:submitObject
    //         }).then(function(response){
    //             return response.text();
    //         }).then(function(text){
    //             // document.body.innerHTML=text;
    //             let json=JSON.parse(text);
    //             if(json.status==CHANGE_SUCCESS)
    //             {

    //             }
    //             else if(json.status==CHANGE_ERROR)
    //             {
    //                 alert("加入失败："+json.information);
    //                 $("#love").removeClass("fa-heart");
    //                 $("#love").addClass("fa-heart-o");
    //                 $("#love + span").text(Number($("#love + span").text())-1);
    //             }
    //             else if(json.status==CHANGE_OVERFLOW)
    //             {
    //                 alert("人数已满，无法加入");
    //                 $("#love").removeClass("fa-heart");
    //                 $("#love").addClass("fa-heart-o");
    //                 $("#love + span").text(Number($("#love + span").text())-1);
    //             }
    //             else
    //             {
    //                 alert("收到服务器未知消息");
    //                 $("#love").removeClass("fa-heart");
    //                 $("#love").addClass("fa-heart-o");
    //                 $("#love + span").text(Number($("#love + span").text())-1);
    //             }
    //         });
    //     }
    // });

    $("#agree").click(function(){
        if($(this).attr("class").search("-o")==-1)
        {
            $("#agree").removeClass("fa-thumbs-up");
            $("#agree").addClass("fa-thumbs-o-up");
            $("#agree + span").text(Number($("#agree + span").text())-1);
            let submitObject=new FormData();
            submitObject.append("yid",$("#yid").text());
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
            submitObject.append("yid",$("#yid").text());
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

    $(".attend").hover(function(){
        $(this).text("加入");
    });
    $(".attend").mouseleave(function(){
        $(this).text("未加入");
    });
    $(".attended").hover(function(){
        $(this).text("退出");
    });
    $(".attended").mouseleave(function(){
        $(this).text("已加入");
    });
    $(".attend").click(function(){
        if(Number($("#present-num").text()) >= Number($("#max-num").text()))
        {
            alert("人数已满，无法加入");
            return;
        }
        $(".attend").addClass("hidden");
        $(".attended").removeClass("hidden");
        $("#present-num").text(Number($("#present-num").text())+1);
        let submitObject=new FormData();
        submitObject.append("yid",$("#yid").text());
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
                alert("加入失败："+json.information);
                $(".attended").addClass("hidden");
                $(".attend").removeClass("hidden");
                $("#present-num").text(Number($("#present-num").text())-1);
            }
            else if(json.status==CHANGE_OVERFLOW)
            {
                alert("人数已满，无法加入");
                $(".attended").addClass("hidden");
                $(".attend").removeClass("hidden");
                $("#present-num").text(Number($("#present-num").text())-1);
            }
            else
            {
                alert("收到服务器未知消息");
                $(".attended").addClass("hidden");
                $(".attend").removeClass("hidden");
                $("#present-num").text(Number($("#present-num").text())-1);
            }
        });
    });
    $(".attended").click(function(){
        $(".attended").addClass("hidden");
        $(".attend").removeClass("hidden");
        $("#present-num").text(Number($("#present-num").text())-1);
        let submitObject=new FormData();
        submitObject.append("yid",$("#yid").text());
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
                alert("退出失败："+json.information);
                $(".attend").addClass("hidden");
                $(".attended").removeClass("hidden");
                $("#present-num").text(Number($("#present-num").text())+1);
            }
            else
            {
                alert("收到服务器未知消息");
                $(".attend").addClass("hidden");
                $(".attended").removeClass("hidden");
                $("#present-num").text(Number($("#present-num").text())+1);
            }
        });
    });
});