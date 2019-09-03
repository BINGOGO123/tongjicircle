const TEXT_CHANGE_URL="php/user-information-text.php";
const IMAGE_CHANGE_URL="php/user-information-image.php";

// 为啥说这俩变量已经被定义了呢，明明没有在别的地方定义过的
const CHANGE_SUCCESS=0;
const CHANGE_ERROR=1;

$(function(){
    getNav("#nav-bar");

    //填写初始信息
    let initialData=$(".input-initial-content");
    for(let i = 0;i<initialData.length;i++)
    {
        if(initialData.eq(i).text()!="未填写")
        {
            initialData.eq(i).prev().val(initialData.eq(i).text());
        }
    }

    //仅允许输入数字的位置
    $("input[name=studyNumber]").keyup(function() {
        $(this).val($(this).val().replace(/\D/g,""));
    });
    //不允许输入空白符
    $("input[name=studyPassword],input[name=password],input[name=userName],input[name=trueName],input[name=major]").keyup(function() {
        $(this).val($(this).val().replace(/\s/g,""));
    });

    //点击提交修改个人信息按钮
    $("#text-submit").bind("click",function(e){
        e.preventDefault();

        if($("input[name=userName]").val()=="")
        {
            alert("昵称不能为空");
            $("input[name=userName]").val($("input[name=userName]").next().text());
            return;
        }
        if($("input[name=password]").val()=="")
        {
            alert("密码不能为空");
            $("input[name=password]").val($("input[name=userName]").next().text());
            return;
        }
        // deal=$("#text-form input");
        // for(let i = 0;i<deal.length;i++)
        // {
        //     deal.eq(i).val(dataDeal(deal.eq(i).val()));
        // }
        //ajax获取信息
        let form=new FormData(document.querySelector("#text-form"));
        fetch(TEXT_CHANGE_URL,{
            method:"post",
            body:form
        }).then(function(response){
            return response.text();
        }).then(function(text){
            // console.log(text);
            // document.body.innerHTML=text;
            let json=JSON.parse(text);
            if(json.status==CHANGE_SUCCESS)
            {
                alert("修改信息成功");
                window.location.reload();
            }
            else if(json.status==CHANGE_ERROR)
            {
                alert("修改信息失败："+json.information);
            }
            else
            {
                alert("收到未知消息");
            }
        });
    });

    //点击选择图片
    $(".img-box").click(function(){
        $("#head-choose").click();
    });

    $(".img-box").hover(function(){
        $(".img-box .img-alert label").slideDown(500);
    });
    $(".img-box").mouseleave(function(){
        $(".img-box .img-alert label").slideUp(500);
    });

    $("#head-choose").bind("change",changeImg);

    //提交图片
    $("#submit-image").click(function(){
        //如果没有选图片，那么就组织提交
        if($("#head-choose").val()=="")
            return;
        let submitObject=new FormData();
        if($("#head-choose")[0].files[0] !== undefined)
            submitObject.append("headImage",$("#head-choose")[0].files[0]);
        fetch(IMAGE_CHANGE_URL,{
            method:"post",
            body:submitObject
        }).then(function(response){
            return response.text();
        }).then(function(text){
            // document.body.innerHTML=text;
            let json=JSON.parse(text);
            if(json.status==CHANGE_SUCCESS)
            {
                alert("修改头像成功");
                window.location.reload();
            }
            else if(json.status==CHANGE_ERROR)
            {
                alert("修改头像失败："+json.information);
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
        alert("请选择一张图片！格式jpg或jpeg或png！");
        this.value="";
        $("#head-display").attr("src","php/head.php");
        $("#img-alert").text("");
        return;
    }
    let oFReader=new FileReader();
    oFReader.readAsDataURL(file);
	oFReader.onloadend = function(oFRevent){
        $("#head-display").attr("src",oFRevent.target.result);
    }
    $("#img-alert").text("未保存");
}