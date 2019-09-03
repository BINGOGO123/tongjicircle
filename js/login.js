let cookie=document.cookie;
if(cookie.indexOf("email")!=-1&&cookie.indexOf("password")!=-1)
    window.location.replace("recommend.php");

//提交给的服务器脚本文件
const URL_REGISTER="php/register.php";
const URL_LOGIN="php/login.php";

//返回的状态值
const SUCCESS=0;
const EMAIL_ERROR=1;
const EMAIL_REPEAT=1;
const PASSWORD_ERROR=3;
const ERROR=2;

window.onload=function()
{
    //切换界面
    $(".select button").click(function() {
        $(this).siblings().removeClass("choose");
        $(this).addClass("choose");
        let siblings=$(this).parent().children("button");
        let i = 0;
        for(;i<siblings.length;i++)
        {
            if(siblings[i]==this)
                break;
        }
        if(i >= siblings.length)
        {
            console.log("切换界面部分代码出现错误");
        }
        else
        {
            $(".content").children("div").hide();
            $(".content").children("div").eq(i).show();
        }
        $(".back-trans-1,.back-trans-2").css("height",$("#display-region")[0].offsetHeight+"px");
    });

    $(".back-trans-1,.back-trans-2").css("height",$("#display-region")[0].offsetHeight+"px");

    $("input[name=reg-email]").bind("input",function(){
        if(!/[\w]+@[\w]+\.[a-zA-Z]+/.test($(this).val()) && $(this).val()!="")
        {
            $("#email-error").text("非法邮箱地址");
            $(this).addClass("error-input");
        }
        else
        {
            $("#email-error").text("");
            $(this).removeClass("error-input");
        }
    });

    $("input[name=reg-password]").bind("input",checkPassword);
    $("input#password-ensure").bind("input",checkPassword);

    $("#submit-reg").bind("click",register);
    $("#submit-login").bind("click",login);

    //凡是输入框均不接受空白符号
    $("input").keyup(function() {
        $(this).val($(this).val().replace(/\s/g,""));
    });
}

function checkPassword()
{
    if($("input[name=reg-password]").val()!=$("input#password-ensure").val())
    {
        $("#error").text("不一致");
        $("input[name=reg-password]").addClass("error-input");
        $("input#password-ensure").addClass("error-input");
    }
    else
    {
        $("#error").text("");
        $("input[name=reg-password]").removeClass("error-input");
        $("input#password-ensure").removeClass("error-input");
    }
}

//点击注册按钮
function register(e)
{
    //阻止表单提交
    e.preventDefault();

    let passwordValue=$("input[name=reg-password]").val();
    let passwordEnsureValue=$("input#password-ensure").val();
    let emailValue=$("input[name=reg-email]").val();
    let userNameValue=$("input[name=reg-userName]").val();

    if(!emailValue.length)
    {
        alert("请输入邮箱！");
        return;
    }
    if(!/[\w]+@[\w]+\.[a-zA-Z]+/.test(emailValue))
    {
        alert("邮箱格式错误！\n正确格式如：123@qq.com");
        return;
    }
    if(!userNameValue.length)
    {
        alert("请输入昵称！");
        return;
    }
    if(!passwordValue.length)
    {
        alert("请输入密码！");
        return;
    }
    if(passwordValue!=passwordEnsureValue)
    {
        alert("两次输入密码不一致！");
        return;
    }

    //ajax获取信息
    let form=new FormData(document.querySelector("#display-region .content div:nth-of-type(2) form"));
    fetch(URL_REGISTER,{
        method:"post",
        body:form
    }).then(function(response){
        return response.text();
    }).then(function(text){
        // console.log(text);
        // document.body.innerHTML=text;
        let json=JSON.parse(text);
        if(json.status==SUCCESS)
        {
            alert("注册成功！");
            //转到登陆界面
            $("input[name=reg-password]").val("");
            $("input#password-ensure").val("");
            $("input[name=reg-email]").val("");
            $("input[name=reg-userName]").val("");
            $(".select button:nth-of-type(1)").click();
        }
        else if(json.status==EMAIL_REPEAT)
        {
            alert("该邮箱已经被注册！请直接登陆！");
        }
        else if(json.status=ERROR)
        {
            alert("出现错误：\n"+json.information);
        }
        else
        {
            alert("收到服务器未知信息！");
        }
    });
}

//点击登录按钮
function login(e)
{
    //阻止表单提交
    e.preventDefault();

    let passwordValue=$("input[name=login-password]").val();
    let emailValue=$("input[name=login-email]").val();

    if(!emailValue.length)
    {
        alert("请输入邮箱！");
        return;
    }
    if(!passwordValue.length)
    {
        alert("请输入密码！");
        return;
    }

    //ajax获取信息
    let form=new FormData(document.querySelector("#display-region .content div:nth-of-type(1) form"));
    fetch(URL_LOGIN,{
        method:"post",
        body:form
    }).then(function(response){
        return response.text();
    }).then(function(text){
        // console.log(text);
        // document.body.innerHTML=text;
        let json=JSON.parse(text);
        if(json.status==SUCCESS)
        {
            //转到信息显示界面
            window.location.replace("article-display.php");
        }
        else if(json.status==EMAIL_ERROR)
        {
            alert("邮箱不存在！");
        }
        else if(json.status==PASSWORD_ERROR)
        {
            alert("密码错误！");
        }
        else if(json.status==ERROR)
        {
            alert("出现错误：\n"+json.information);
        }
        else
        {
            alert("收到服务器未知信息！");
        }
    });
}
