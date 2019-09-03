let cookie=document.cookie;
if(cookie.indexOf("email")==-1 || cookie.indexOf("password")==-1)
    window.location.replace("login.html");

const SUBMIT_URL="php/submit-article.php";
const SUBMIT_SUCCESS=0;
const SUBMIT_ERROR=1;

//编辑器
var editor;

$(function(){
    getNav("#nav-bar");

    var E = window.wangEditor;
    editor = new E('#editor');
    editor.create();

    $(".img-box").hover(function(){
        $(".img-box .img-alert label").slideDown(500);
    });
    $(".img-box").mouseleave(function(){
        $(".img-box .img-alert label").slideUp(500);
    });

    $(".select-item").click(function(){
        $(this).siblings().removeClass("clicked");
        $(this).addClass("clicked");
    });

    //点击选择图片
    $(".img-box").click(function(){
        $("#image-choose").click();
    });

    $("#image-choose").bind("change",changeImg);

    //点击发布文章
    $(".submit-article").click(function(){
        if($("#image-choose").val()=="")
        {
            alert("请选择封面图片");
            return;
        }
        if($(".region .title").val()=="")
        {
            alert("请输入标题");
            return;
        }
        if($(".region .introduction").val()=="")
        {
            alert("请输入简介内容");
            return;
        }
        let content=editor.txt.html();
        if(content == "" || content=="<p><br></p>")
        {
            alert("请输入文章内容");
            return;
        }

        let submitObject=new FormData();
        if($("#image-choose")[0].files[0] !== undefined)
            submitObject.append("articleImage",$("#image-choose")[0].files[0]);
        submitObject.append("title",$(".region .title").val());
        submitObject.append("introduction",$(".region .introduction").val());
        submitObject.append("content",content);
        submitObject.append("type",$(".select-item.clicked").text());
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
                if(json.aid==-1)
                {
                    window.location="article-display.php";
                }
                else
                {
                    window.location="article.php?aid="+json.aid;
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

//根据选择的图片更改显示效果
function changeImg()
{
    let file=this.files[0];
    let value=this.value;
    let array=value.split("\\");
    value=array[array.length-1];
    if(!(/.jpg$/i.test(value))&&!(/.png$/i.test(value))&&!(/.jpeg$/i.test(value)))
    {
        alert("请选择一张图片！格式jpg或jpeg或png");
        this.value="";
        $(".img-box img").attr("src","images/article-image.jpg");
        return;
    }
    let oFReader=new FileReader();
    oFReader.readAsDataURL(file);
	oFReader.onloadend = function(oFRevent){
        $(".img-box img").attr("src",oFRevent.target.result);
    }
}