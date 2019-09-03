var editor;

const SUBMIT_URL="php/submit-announcement.php";
const SUBMIT_SUCCESS=0;
const SUBMIT_ERROR=1;

$(function(){
    getNav("#nav-bar");

    var E = window.wangEditor;
    editor = new E('#editor');
    editor.create();

    //点击发布公告
    $(".submit-article").click(function(){
        if($(".region .title").val()=="")
        {
            alert("请输入标题");
            return;
        }
        let content=editor.txt.html();
        if(content == "" || content=="<p><br></p>")
        {
            alert("请输入公告内容");
            return;
        }
        let submitObject=new FormData();
        submitObject.append("title",$(".region .title").val());
        submitObject.append("content",content);
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
                if(json.nid==-1)
                {
                    window.location="announcement-display.php";
                }
                else
                {
                    window.location="announcement.php?nid="+json.nid;
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