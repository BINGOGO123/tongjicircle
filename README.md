## 同济小社区

一个论坛类社交网站

入口：<https://bingoz.cn/tongjicircle>

## 基本功能

1. 用户注册、登录以及各项个人信息的录入维护。
   
2. 用户发布文章、活动等社交信息。
   
3. 用户查看文章、活动、公告信息。
   
4. 用户可以对文章、活动、公告等进行点赞、收藏、评论等交流。
   
5. 对于其他用户发布的活动，用户可以在规定的时间限制以及人数范围内参与活动并与其他参与该活动的人进行线上互动。
   
6. 用户可以通过特定的检索方式寻找文章、活动和其他用户，对于检索得到的结果可以用多种方法进行排序。
   
7. 平台会针对当前热门的用户和最新的公告、文章、活动进行推荐。
   
8. 用户可以系统查看自己发布的文章、创建的活动以及收藏参与的活动文章公告等。
   
9. 用户可以查看自己的个人动态以及查看他人的动态等。
   
10. 管理员用户可以发布公告，并可以查看各项用户发布的信息并具有删除对应信息的权限。

## 数据库结构

数据库结构可查看`create-db.sql`

### ER图

[ER图](https://github.com/BINGOGO123/tongjicircle/blob/master/picture/ER%E5%9B%BE.vsdx)

![ER图](https://github.com/BINGOGO123/tongjicircle/blob/master/picture/ER.png)

### 逻辑结构合理性

1. 用户信息表（用户号，邮箱，用户昵称，密码，真实姓名，专业，学号，学号对应的密码，头像，用户注册日期（精确到日），头像图片类型）

   ```
   userInformation(uid,email,userName,password,trueName,major,studyNumber,studyPassword,headImage,userDate,imageType)
   ```

   > 候选码：`uid`
   >
   > 候选码：`email`

   证明：

   ````
   uid->userName,password,trueName,major,studyNumber,studyPassword,headImage,userDate,imageTypeemail->userName,password,trueName,major,studyNumber,studyPassword,headImage,userDate,imageType
   ````

   可得`userInformation`符合第二范式

   现在证明非主属性不传递依赖于任何一个码，首先找中间的Z，Z不可能包含码，因此这里Z只可能是非主属性的组合，而`userName`之外的所有非主属性组合起来也不能决定`userName`，同理其他的非主属性也是这样，所以任何一个非主属性不传递依赖于码，因此符合第三范式

2. 文章表（文章号，用户号，标题，简介，内容，封面图片，类型，发布时间（精确到分），阅读量，图片类型）

   ```
   article(aid,uid,title,introduction,content,articleImage,type,articleTime,readNumber,imageType)
   ```

   > 候选码：`aid`
   >
   > 外码：`uid--userInformation`

   证明：

   ```
   aid->uid,title,introduction,content,articleImage,type,articleTime,readNumber,imageType
   ```

   可得`article`符合第二范式

   对于任何一个非主属性，其他所有非主属性的组合都无法函数决定它，因此任何一个非主属性不传递依赖于码，符合第三范式

3. 点赞文章表（用户号，文章号，时间（精确到分））

   ```
   agreeArticle(uid,aid,agreeTime)
   ```

   > 候选码：`uid`,`aid`
   >
   > 外码：`uid--userInformation`
   >
   > 外码：`aid--article`

   证明：

   `agreeTime`不函数依赖于`uid`也不函数依赖于`aid`，因此符合第二范式

   中间的Z根本找不到，因此符合第三范式 

4. 收藏文章表（用户号，文章号，时间（精确到分））

   ```
   loveArticle(uid,aid,loveTime)
   ```

   > 候选码：`uid`,`aid`
   >
   > 外码：`uid--userInformation`
   >
   > 外码：`aid--article`

   证明：

   `loveTime`不函数依赖于`uid`也不函数依赖于`aid`，因此符合第二范式

   中间的Z根本找不到，因此符合第三范式

5. 评论文章表（文章号，评论号（每篇文章从0开始），用户号，评论对象号（-1表示对该文章的评论），评论内容，评论时间（精确到秒））

   ```
   commentArticle(aid,cid,uid,cidTarget,content,commentTime)
   ```

   > 候选码：`aid`,`cid`
   >
   > 外码：`uid--userInformation`
   >
   > 外码：`aid--article`

   证明：

   ```
   aid,cid->uid,cidTarget,content,commentTime
   ```

   且任意非主码均完全依赖于候选码，因此符合第二范式

   对于任何一个非主属性，其他所有属性的组合（不完全包含住候选码的那种）都无法函数决定它，因此任何一个非主属性不传递依赖于码，符合第三范式

6. 活动表（活动号，用户号，活动标题，活动内容，活动地点，封面图片，人数上限，活动类型，创建时间（精确到分），开始时间（精确到分），结束时间（精确到分），阅读量，图片类型）

   ```
   activity(yid,uid,title,content,location,activityImage,maxNum,type,activityTime,startTime,endTime,readNumber,imageType)
   ```

   > 候选码：`yid`
   >
   > 外码：`uid--userInformation`
   >

   证明：

   ```
   yid->uid,title,content,location,activityImage,maxNum,type,activityTime,startTime,endTime,readNumber,imageType
   ```

   因此是第二范式

   对于任何一个非主属性，其他所有非主属性的组合都无法函数决定它，因此任何一个非主属性不传递依赖于码，符合第三范式

7. 点赞活动表（活动号，用户号，点赞时间（精确到分））

   ```
   agreeActivity(yid,uid,agreeTime)
   ```

   > 候选码：`yid`,`uid`
   >
   > 外码：`uid--userInformation`
   >

   证明：

   与上面的点赞文章表相同，不赘述

8. 参加活动表（用户号，活动号，时间（精确到分））

   ```
   loveActivity(uid,yid,loveTime)
   ```

   > 候选码：`uid`,`aid`
   >
   > 外码：`uid--userInformation`
   >
   > 外码：`yid--activity`
   >

   证明：

   与上面的收藏文章表相同，不赘述

9. 评论活动表（活动号，评论号（每个活动从0开始），用户号，评论对象号（-1表示对该活动的评论），评论内容，评论时间（精确到秒））

   ```
   commentActivity(yid,cid,uid,cidTarget,content,commentTime)
   ```

   > 候选码：`yid`,`cid`
   >
   > 外码：`uid--userInformation`
   >
   > 外码：`yid--activity`
   >

   证明：

   与上面的评论文章表相同，不赘述

10. 管理员表（管理员号，用户号）

    ```
    manager(mid,uid)
    ```

    > 候选码：`mid`
    >
    > 外码：`uid--userInformation`

    证明：

    显然是第三范式，不需要证明

11. 公告表（公告号，管理员号，标题，内容，时间（精确到分））

    ```
    announcement(nid,mid,title,content,announcementTime)
    ```

    > 候选码：`nid`
    >
    > 外码：`mid`
    >

    证明：

    与上面文章和活动相同，不赘述

12. 收藏公告表（用户号，公告号，时间（精确到分）,阅读量）

    ```
    loveAnnouncement(uid,nid,loveTime,readNumber)
    ```

    > 候选码：`uid`,`nid`
    >
    > 外码：`uid--userInformation`
    >
    > 外码：`nid--announcement`
    >

    证明：

    与上面的收藏文章表相同，不赘述

13. 流水记录表（流水号，管理员号，公告号，文章号，活动号，文章评论号，活动评论号，类型，扼要信息（用以提示用户），原因，时间（精确到秒））

    ```
    water(wid,mid,nid,aid,yid,acid,ycid,type,information,reason,waterTime)
    ```

    > 候选码：`wid`
    >
    > 外码：`mid--manager`
    >
    > 外码：`nid--announcement`
    >
    > 外码：`aid--article`
    >
    > 外码：`yid--activity`
    >
    > 类型：
    >
    > 0-删除公告
    >
    > 1-删除文章
    >
    > 2-删除活动
    >
    > 3-删除文章评论
    >
    > 4-删除活动评论

    证明：

    显然是第三范式，无需证明
