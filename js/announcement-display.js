const LOVE_URL="php/love-announcement.php";

const DO=0;
const UNDO=1;
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;

$(function(){
    getNav("#nav-bar");

    //暂时不要这个分页功能
    // $('#page-box').paging({
    //     initPageNo: 3, // 初始页码
    //     totalPages: 30, //总页数
    //     // totalCount: '合计' + setTotalCount + '条数据', // 条目总数
    //     slideSpeed: 600, // 缓动速度。单位毫秒
    //     jump: true, //是否支持跳转
    //     callback: function(page) { // 回调函数
    //         console.log(page);
    //     }
    // });

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

    $(".gonggao-item").click(function(e){
        let target=e.target;
        if(e.target.className.search("gonggao-about-button")!=-1 || e.target.className.search("star")!=-1)
            return;
        window.open("announcement.php?nid="+$(this).find(".nid").text());
    });
});