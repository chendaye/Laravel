<?php
/**
 * Created by PhpStorm.
 * User: chendaye666
 * Date: 2018/2/24
 * Time: 17:57
 */
$con=mysqli_connect("67.218.128.128","root","root","chendaye666");
var_dump($con);
// 检查连接
if (!$con)
{
    die("连接错误: " . mysqli_connect_error());
}

$ret = mysqli_query($con,"INSERT INTO posts (title, content, user_id,created_at, updated_at) VALUES 
('快讯：余梦君获得2017年度环球最佳美女金像奖','央视娱乐讯 当地时间2018年2月23日(北京时间24日)，第88届环球最佳美女金像奖在好莱坞杜比剧院举行，余梦君凭 借柔美飘逸的长发，清澈动人的大眼睛，瓜子一样的脸蛋，牛奶般的肌肤，高挑婀娜的身姿；俘获万千少男少女的心，成功夺得环球最佳美女金像奖。在过去22年里，余梦君曾20次入围环球美女，她何时能捧得自己的第21座金像奖，已成为众多迷弟迷妹热议的话题。此次小仙女不负众望一举摘下桂冠，毫无悬念的继续着自己的环球美女之路。
<img src=\"http://www.chendaye666.top/jun.jpg\">
小仙女发表获奖感言时表示：“谢谢大家，我也得恭喜其她入围者。首先要感谢爸爸妈妈，另外也感谢小龙，感谢你们过去所做的一切和默默地支持与鼓励。另外，2017年是美丽而又繁忙的一年，我们过得充实而幸福。2018我们会更加美丽动人！”

在其他奖项方面，陈小龙获得年度最佳帅哥奖；杰森斯坦森击败凯特·布兰切特、詹妮弗·劳伦斯等人，获得亚军！


以下是本届环球最佳美女金像奖获奖名单：

亚军：嘉丽·约翰逊
季军：杰西卡·阿尔芭','1','2018-02-24 16:43:21','2018-02-24 16:43:21')");

var_dump($ret);