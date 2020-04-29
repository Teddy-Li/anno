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
<h2>Want to return to Instructions?</h2>
<input type="button" value="Return to Instructions" onclick="javascrtpt:window.location.href='./index.html'" />
<hr>
<h1>Rewrite Questions in Your Words</h1>

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
$myfile = fopen("SPIDER_canonicals_train.csv", "r");
$headers = fgets($myfile);
$annotated_file = fopen("spider_canonicals_train_annotated.json", "r");
$annotated_str = fgets($annotated_file);
$annotated = json_decode($annotated_str, true);
$candidates = array();
$entry;
while(! feof($myfile))
{
    $temp_array = fgetcsv($myfile);
    $cnt = find_num_instances($annotated, $temp_array[6]);
    if($cnt < 2){
        if(count($candidates) < 10){
            $candidates[count($candidates)] = $temp_array;
        }
        else{
            break;
        }
    }
}

if(count($candidates) > 0){
    $max_idx = count($candidates)-1;
    $key = rand(0,$max_idx);
    $entry = $candidates[$key];
    $annotated[count($annotated)] = $entry[6];
    setcookie("entry_index",$entry[6]);
}

fclose($myfile);
fclose($annotated_file);

if(is_null($entry)){
    die("Files exausted!");
}
else{
    echo "<h2>Example for Reference</h2>";
    echo "<p><strong>Sequence: </strong><br>", $entry[3], "</p>";
    echo "<p><strong>Expected Output: </strong>", $entry[4], "</p>";
    echo "<hr>";
    echo "<h2>Write down how you would ask the following question in your own words, then check on the radio boxes below</h2>";
    echo "<p><strong>Topic: </strong>", $entry[0], "</p>";
    echo "<p><strong>Question Sequence: </strong><br>",$entry[1],"</p>";
    echo "<p><strong>Database Response: </strong><br>",$entry[5],"</p>";
}
?>

<hr>
<form action="saveData.php" method="post" name="annotation" onsubmit="return validateForm()" id="annotation">
    <br>
    Question: <input type="text" style="height:80px;width:700px" name="question" id="question" placeholder="Type the question in your own words here..." required>
    <br><br>
    <hr>
    <strong>Indicate whether you agree with the following statements:</strong>
    <p>1) The computer-generated question sequence does not have redundant or pointless parts.</p>
    <input type="radio" name="concise" value="yes" required>agree<br>
    <input type="radio" name="concise" value="no" required>disagree
    <p>2) The answer this computer-generated question sequence asks for makes sense.</p>
    <input type="radio" name="sensible" value="yes" required>agree<br>
    <input type="radio" name="sensible" value="no" required>disagree
    <p>3) People actually ask such questions in real world.</p>
    <input type="radio" name="worldly" value="yes" required>agree<br>
    <input type="radio" name="worldly" value="no" required>disagree
    <p>4) This is a complex question.</p>
    <input type="radio" name="complex" value="yes" required>agree<br>
    <input type="radio" name="complex" value="no" required>disagree
    <hr>
    <strong>Please tell us at how many places did you find our <b>computer-generated</b> question sequence hard to understand:</strong>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="1" required>1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="2" required>2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="3" required>3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="trouble_understand" value="4more" required>4 or more<br>

    <hr>
    <div style="text-align:center; vertical-align:middel;height:100px">
    Your 10-digit Student ID :<input type="text" name="stu_id" id="stu_id" required><br>
    (Payments will be made via your school ABC debit card linked to this ID, please contact us via e-mail if you have troubles on this) <br>
    Contact: litianyi01@pku.edu.cn<br>
    <input type="submit" value="Submit" style='font-size:30px'>
    </div>
</form>
</body>
</html>