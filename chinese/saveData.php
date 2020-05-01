<html>
<head>
<style type="text/css">
    body
    {background-image:url(body.jpeg);
    background-repeat:no-repeat;
    background-attachment:fixed
    }
    h1 {background-image:url(header.png); text-align:center; line-height: 390%}
    p {font-size: 20px}
    </style>
</head>
<body>
<h1>提交成功！</h1>
<?php
$log_file = "spider_canonicals_train_annotated.json";
$json = $_POST;
$json["entry_idx"] = $_COOKIE["entry_index"];
$annotated_file = fopen($log_file, "r");
$annotated_str = fgets($annotated_file);
fclose($annotated_file);
$annotated = json_decode($annotated_str, true);
$annotated[count($annotated)] = $_COOKIE["entry_index"];
#echo "!!!!!: ", $_COOKIE["entry_index"];
#echo "annotated: ", $annotated;
$new_annotated_str = json_encode($annotated);
#echo "str: ", $new_annotated_str;
$new_annotated_file = fopen($log_file, "w");
fwrite($new_annotated_file, $new_annotated_str);
fclose($new_annotated_file);

$txt = json_encode($json)."\n";
$fname = "./annotation_result_canonical_train/data_".$json["entry_idx"]."_".date("y_m_d_h_i_sa").".json";
$myfile = fopen($fname, "w");
fwrite($myfile, $txt);
fclose($myfile);
echo "<h2>数据已存入</h2>";
echo "您的提交：", $json["question"], "\n";
?>
<hr>
<input type="button" value="继续标注" onclick="javascrtpt:window.location.href='./annotate.php'" />
<input type="button" value="再看一眼说明 QwQ" onclick="javascrtpt:window.location.href='./index.html'" />
</body>
</html>