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
$myfile = fopen("SPIDER_canonicals_train.csv", "r");
$headers = fgets($myfile);
$annotated_file = fopen("spider_canonicals_train_annotated.json", "r");
$annotated_str = fgets($annotated_file);
$annotated = json_decode($annotated_str, true);
$entry;
while(! feof($myfile))
{
    $temp_array = fgetcsv($myfile);
    if(! in_array($temp_array[6], $annotated)){
        $entry = $temp_array;
        $annotated[count($annotated)] = $temp_array[6];
        setcookie("gold",$temp_array[2]);
        setcookie("entry_index",$temp_array[6]);
        break;
    }
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
    echo "<h2>Write down how you would ask this question and check on the radio boxes below</h2>";
    echo "<p><strong>Topic: </strong>", $entry[0], "</p>";
    echo "<p><strong>Question Sequence: </strong><br>",$entry[1],"</p>";
    echo "<p><strong>Answer Sample: </strong><br>",$entry[5],"</p>";
}
?>

<hr>
<form action="saveData.php" method="post" name="annotation" onsubmit="return validateForm()" id="annotation">
    <br>
    Question: <input type="text" style="height:80px;width:700px" name="question" id="question" placeholder="Type how you would ask this question here..." required>
    <br><br>
    <hr>
    <strong>Indicate whether you agree with the following statements:</strong>
    <p>1) The question sequence does not have redundant or meaningless parts.</p>
    <input type="radio" name="concise" value="yes" required>yes<br>
    <input type="radio" name="concise" value="no" required>no
    <p>2) The answer this question sequence asks for has sensible meanings.</p>
    <input type="radio" name="sensible" value="yes" required>yes<br>
    <input type="radio" name="sensible" value="no" required>no
    <p>3) People usually ask such questions in real world.</p>
    <input type="radio" name="worldly" value="yes" required>yes<br>
    <input type="radio" name="worldly" value="no" required>no
    <p>4) This is a complex question.</p>
    <input type="radio" name="complex" value="yes" required>yes<br>
    <input type="radio" name="complex" value="no" required>no
    <hr>

    <br>
    <div style="text-align:center; vertical-align:middel;height:100px">
    Your Student ID :<input type="text" name="stu_id" id="stu_id" required><br>
    (Payments will be made via your school ABC debit card linked to this ID, please contact us via e-mail if you have troubles on this) <br>
    Contact: litianyi01@pku.edu.cn<br>
    <input type="submit" value="Submit" style='font-size:60px'>
    </div>
</form>
</body>
</html>