<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Online Quiz</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/style.css">
    </head>
    <body>
        <h1>Online Quiz</h1>
        <form action="index.php" method="post">
            <table>
                <?php
                $numOfQues = 0;
                $fptr = fopen("questions.txt", "r");
                if($fptr){
                    while($question = fgets($fptr)){
                        $numOfQues++;
                        $details = explode(",",$question);
                        $num = $details[0];
                        $ques = $details[1];
                        echo "<tr><td>{$num}</td><td>{$ques}</td></tr>";
                        $length = count($details);
                        for ($i = 2; $i < $length; $i++) {
                            echo "<tr><td><input type='radio' id='{$num}{$details[$i]}' name='{$num}' value='{$details[$i]}'></td><td><label for='{$num}{$details[$i]}'>{$details[$i]}</label></td><tr>";
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="2"><button type="submit">Submit Quiz</button></td>
                    </tr>
                    <?php
                }
                else{
                    echo "<p>File can't be opened.</p>";
                }
                fclose($fptr);
                ?>
            </table>
        </form>
        <?php
            $answers = array();
            if(isset($_POST)){
                for($i=1;$i<=$numOfQues;$i++){
                    if(isset($_POST[strval($i)])){
                        $answers[$i] = preg_replace("/\s+/", "", $_POST[strval($i)]);
                    }
                }
                if(count($answers)!=$numOfQues&&count($answers)!=0){
                    echo "<p>You must answer all questions.</p>";
                }
                else if(count($answers)==$numOfQues){
                    $correct = 0;
                    $fptr = fopen("answers.txt", "r");
                    if($fptr){
                        while($ans = fgets($fptr)){
                            $details = explode(",",$ans);
                            $num = $details[0];
                            $fileAns = $details[1];
                            $fileAns = substr($fileAns, 0, -2);
                            if(strcmp($answers[$num],$fileAns)==0){
                                $correct++;
                            }
                        }
                        $percentage = ($correct/$numOfQues)*100;
                        $class = "";
                        if($percentage>=80){
                            $class = "green";
                        }
                        else if(($percentage>=60)){
                            $class = "yellow";
                        }
                        else if(($percentage>=50)){
                            $class = "red";
                        }
                        else{
                            $class = "black";
                        }
                        echo "<p class='{$class}'>You scored a {$percentage}% in this quiz.</p>";
                    }
                    else{
                        echo "<p>File can't be opened.</p>";
                    }
                    fclose($fptr);
                }
            }
        ?>
        <p><a href="databaseVersion.php">Move to database version</a></p>        
    </body>
</html>