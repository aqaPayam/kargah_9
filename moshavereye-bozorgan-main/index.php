<?php

$question = '';
$msg = 'سوال خود را بپرس!';
$en_name = 'hafez';
$fa_name = 'حافظ';


$text=fopen("messages.txt","r"); // text mishe kol jomalat
$temp=file_get_contents("people.json");
$arr_name=json_decode($temp);    /// listi az esm hakey = khareji value = farsi
$arr_jomle=array();      ///arraye ei 0 ta 15 shamel 16 jomle
$arr_backup_name=array();
$arr_question=array();
$q=0;
while (! feof($text))
{
    $arr_jomle[$q] = fgets($text);
    $q++;
}
$p=0;
foreach ($arr_name as $key => $val) {
    $arr_backup_name[$p]=$key;
    $p++;
  }

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $en_name = $_POST["person"];
    $question= $_POST["question"];
    $pass= hash("crc32" , $question." ".$en_name);
    $pass=hexdec($pass);
    $num=$pass % 16;
    $msg=$arr_jomle[$num];
    foreach ($arr_name as $key => $val)
        {
            if ($key==$en_name)
            {
                $fa_name=$val;
            }
        
        }
    $first="/^آیا/iu";
    $last="/\?$/i";
    $lastt="/؟$/u";
    if((!(preg_match($last,$question) || preg_match($lastt,$question))) || !(preg_match($first,$question)))
    {
        $msg="سوال درستی پرسیده نشده";
    }

}
else
{
    $tempran=array_rand($arr_backup_name);
    $en_name=$arr_backup_name[$tempran];
    foreach ($arr_name as $key => $val)
        {
            if ($key==$en_name)
            {
                $fa_name=$val;
            }
        
        }

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>


<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">
        <?php
                if($question != "")
                {
                    echo "پرسش:";
                }
        ?>
        </span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php 
            if ($question!="")
            {
                echo $msg;
            }
            else
            {
                echo "سوال خود را بپرس!";
            }
            ?>
             </p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <?php
                    $arr_name=json_decode($temp);
                    foreach ($arr_name as $key => $val)
                    {
                        if ($key==$en_name)
                        {
                        echo "<option value=$key selected> $val</option>";
                        }
                       else
                       {
                        echo "<option value=$key > $val</option>";
                       }
                    
                    }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>