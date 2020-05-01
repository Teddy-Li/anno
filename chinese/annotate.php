<!DOCTYPE html>
<html lang="en">
<head>

    <style type="text/css">

    body
    {background-image:url(body.jpeg);
    background-repeat:no-repeat;
    }
    h1 {background-image:url(header.png); text-align:center; line-height: 390%}
    p {font-size: 20px}
    content {background}
    </style>
    <meta charset="UTF-8">
    <title>Annotate</title>
    <script>
        function validateForm(){
            var stu_id=document.forms["annotation"]["stu_id"].value;
            if (stu_id.length != 10){
                alert("学号必须为10位数字！")
                return false;
            }
            for (var i=0;i<stu_id.length;i++)
            {
                if (stu_id[i] > '9' || stu_id[i] < '0'){
                    alert("学号必须为10位数字！")
                    return false
                }
            }
            return true
        }
    </script>
</head>

<body>
<h2>需要再看一眼教程？┏ (^ω^)=</h2>
<input type="button" value="返回说明&教程" onclick="javascrtpt:window.location.href='./index.html'" />
<hr>
<h1>以你自己的表达方式重写问题</h1>

<?php
function find_num_instances($lst, $key){
    if(! in_array($key, $lst)){
        return 0;
    }
    $cnt = 0;
    foreach($lst as $v){
        if($v == $key){
            $cnt = $cnt + 1;
        }
    }
    return $cnt;
}
$in_fp = "SPIDER_canonicals_train.csv";
$log_fp = "spider_canonicals_train_annotated.json";
$myfile = fopen($in_fp, "r");
$headers = fgets($myfile);
$annotated_file = fopen($log_fp, "r");
$annotated_str = fgets($annotated_file);
$annotated = json_decode($annotated_str, true);
$candidates = array();
$entry;
while(! feof($myfile))
{
    $temp_array = fgetcsv($myfile);
    $cnt = find_num_instances($annotated, $temp_array[6]);
    if($cnt < 2){
        $candidates[count($candidates)] = $temp_array;
    }
}

if(count($candidates) > 0){
    $max_idx = count($candidates)-1;
    $key = rand(0,$max_idx);
    $entry = $candidates[$key];
    $annotated[count($annotated)] = $entry[7];
    setcookie("entry_index",$entry[7]);
}

fclose($myfile);
fclose($annotated_file);

if(is_null($entry)){
    die("没有更多问题可以标注了TAT");
}
else{
    echo "<h2>请用你自己的表达方式问出下面的问题，然后勾选下面的单选框～</h2>";
    echo "<p><strong>主题：</strong>", $entry[0], "</p>";
    echo "<p><strong>问题序列：</strong><br>",$entry[1],"</p>";
    echo "<p><strong>来自数据库的答案示例</strong><br>",$entry[5],"</p>";
    echo "<hr>";
    echo "<h2>先放一个栗子🌰（供参考）：/h2>";
    echo "<p><strong>问题序列：</strong><br>", $entry[3], "</p>";
    echo "<p><strong>一个可能的改写方式：</strong>", $entry[4], "</p>";
    echo "<p><strong>来自数据库的答案示例：</strong><br>",$entry[6],"</p>";
}
?>

<hr>
<form action="./saveData.php" method="post" name="annotation" onsubmit="return validateForm()" id="annotation">
    <br>
    你对这个问题的重写：<input type="text" style="height:80px;width:700px" name="question" id="question" placeholder="请在这里输入你对这个问题的表达......" required>
    <br><br>
    <hr>
    <strong>请勾选你是否同意以下的观点：</strong>
    <p>1）这个由机器生成的问题序列中不包括<b>冗余</b>或<b>无意义</b>的部分。</p>
    <input type="radio" name="concise" value="yes" required>同意<br>
    <input type="radio" name="concise" value="no" required>不同意
    <p>2）这个问题序列所询问的内容是在现实世界中具有实际含义的。</p>
    <input type="radio" name="sensible" value="yes" required>同意<br>
    <input type="radio" name="sensible" value="no" required>不同意
    <p>3）人们可能在实际中问到这类内容。</p>
    <input type="radio" name="worldly" value="yes" required>同意<br>
    <input type="radio" name="worldly" value="no" required>不同意
    <p>4）这是一个复杂的问题。</p>
    <input type="radio" name="complex" value="yes" required>同意<br>
    <input type="radio" name="complex" value="no" required>不同意
    <hr>
    <strong>在你阅读原始问题序列的过程中，有几处地方遇到了困难？</strong>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="0" required>0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="1" required>1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="2" required>2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="3" required>3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="4more" required>4处或更多<br>

    <hr>
    <div style="text-align:center; vertical-align:middel;height:100px">
    请在此处输入你的学号：<input type="text" name="stu_id" id="stu_id" required><br>
    （我们将通过学校发放的农行借记卡向你发放补贴，如果你通过此方式接收补贴由困难或遇到其他问题，请通过以下的联系方式与我们联系～）<br>
    联系方式： litianyi01@pku.edu.cn<br>
    <input type="submit" value="确认无误、提交" style='font-size:30px'>
    </div>
</form>
</body>
</html>