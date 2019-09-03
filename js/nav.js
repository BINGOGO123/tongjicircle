function Nav(id)
{
    this.item=document.querySelector(id);
}

Nav.prototype.setInfo=function(leader)
{
    this.info=leader;
}

Nav.prototype.create=function()
{
    this.item.className="navbar navbar-expand-lg navbar-dark bg-dark fixed-top my-style";
    str=`<a class="navbar-brand" href="${this.info.logo.href}"><img src="${this.info.logo.src}" alt="logo"><span>${this.info.logo.str}</span></a>
        <div class="vertical-line"></div>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse navbar-collapse zhb-bar" id="navbarResponsive">
            <ul class="navbar-nav ul-1">
        `;
    for(let i = 0;i<this.info.list.length;i++)
    {
        str+=`
        <li class="nav-item nav-option" data-toggle="tooltip" data-placement="right">
        <a class="nav-link" href="${this.info.list[i].href}">
            <span class="nav-link-text">${this.info.list[i].str}</span>
        </a>
        </li>`;
    }
    str+=`</ul>
        <ul class="navbar-nav ml-auto zhb-ul">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-comment"></i>
            <span class="d-lg-none">通知</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">通知消息</h6>
            <div class="dropdown-divider"></div>`;
    
    for(let i = 0;i<this.info.alert.length;i++)
    {
        str+=`<a class="dropdown-item" href="${this.info.alert[i].href}">
        <span class="text-success">
        <strong>${this.info.alert[i].str}</strong>
        </span>
        <span class="small float-right text-muted">${this.info.alert[i].time}</span>
        <div class="dropdown-message small">${this.info.alert[i].content}</div>
        </a>`;
        if(i != this.info.alert.length-1)
            str+=`<div class="dropdown-divider"></div>`;
    }
    str+=`</div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-2 no-after" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img id="nav-head" src="${this.info.person.head}" alt="头像" />
                    <span class="d-lg-none">您的信息</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right my-modal" aria-labelledby="alertsDropdown">
                    <div class="user-info">
                    <div class="first-line">
                        <img class="nav-dropdown-head" src="${this.info.person.head}" alt="大头像" />
                        <div class="img-right">
                        <div>${this.info.person.name}</div>
                        <div><span>${this.info.person.email}</span></div>
                        <div>${this.info.person.time}</div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="second-line">
                        <div class="line">
                        <i class="fa fa-fw fa-user"></i>
                        <span>${this.info.person.trueName}</span>
                        </div>
                        <div class="line">
                        <i class="fa fa-fw fa-graduation-cap"></i>
                        <span>${this.info.person.major}</span>
                        </div>
                        <div class="line">
                        <i class="fa fa-fw fa-id-card"></i>
                        <span>${this.info.person.order}</span>
                        <span class="line-button"><a href="${this.info.person.edit}">编辑</a></span>
                        </div>
                    </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="${this.info.link[0]}">
                    <span class="text-dark">
                        <strong>个人主页</strong>
                    </span>
                    </a>
                    <a class="dropdown-item" href="${this.info.link[1]}">
                    <span class="text-dark">
                        <strong>我的社区</strong>
                    </span>
                    </a>
                    <a class="dropdown-item" href="${this.info.link[2]}">
                    <span class="text-dark">
                        <strong>发布文章</strong>
                    </span>
                    </a>
                    <a class="dropdown-item" href="${this.info.link[3]}">
                    <span class="text-dark">
                        <strong>发布活动</strong>
                    </span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="${this.info.link[4]}">
                    <span class="text-dark">
                        <strong>作者信息</strong>
                    </span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="${this.info.link[5]}">
                    <span class="text-danger">
                        <strong>登出</strong>
                    </span>
                    </a>
                </div> 
            </li>
        </ul>
        </div>`
    this.item.innerHTML=str;
}

const ERROR=1;
const SUCCESS=0;
function getNav(str)
{
    nav=new Nav(str);

    fetch("php/get-nav.php",{
        method:"post"
    }).then(function(response){
        return response.text();
    }).then(function(text){
        // console.log(text);
        // document.body.innerHTML=text;
        json=JSON.parse(text);

        //如果这些内容为空，则未填写
        if(json.person.order=="")
            json.person.order="未填写";
        if(json.person.trueName=="")
            json.person.trueName="未填写";
        if(json.person.major=="")
            json.person.major="未填写";
        if(json.manager)
        {
            header=[{
                    href:"article-display.php",
                    str:"社区"
                },{
                    href:"announcement-display.php",
                    str:"公告"
                },{
                    href:"recommend.php",
                    str:"推荐"
                },{
                    href:"search-user.php",
                    str:"用户"
                },{
                    href:"manager.php",
                    str:"信息管理"
                },{
                    href:"write-announcement.php",
                    str:"发布公告"
                }
            ];
        }
        else
        {
            header=[{
                href:"article-display.php",
                str:"社区"
            },{
                href:"announcement-display.php",
                str:"公告"
            },{
                href:"recommend.php",
                str:"推荐"
            },{
                href:"search-user.php",
                str:"用户"
            }];
        }
        if(json.status==ERROR)
        {
            alert("出现错误："+json.information);
            location.replace("login.html");
        }
        nav.setInfo({
            logo:{
                href:"article-display.php",
                src:"images/web.png",
                str:"同济大学小社区"
            },
            list:header,
            alert:[

            ],
            person:{
                name:json.person.name,
                head:json.person.head,
                order:json.person.order,
                time:json.person.time,
                email:json.person.email,
                trueName:json.person.trueName,
                major:json.person.major,
                edit:"user-information.php"
            },
            link:[
                "record.php",
                "my-article.php",
                "write.html",
                "act.html",
                "help.html",
                "php/logout.php"
            ]
        });
        nav.create();
    });
}
