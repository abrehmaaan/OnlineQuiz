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
        <form action="databaseVersion.php" method="post">
            <table>
                <?php
                $numOfQues = 0;
                require_once('config.inc.php');
                $conn_str = DB_SYS.':host='.DB_HOST.';dbname='.DB_NAME;
                try {
                    $pdo = new PDO($conn_str,DB_USER,DB_PASS);
                    $sql = "SELECT * FROM questions";
                    $stat = $pdo->prepare($sql);
                    $stat->execute();
                    while($result = $stat->fetch()){
                        $numOfQues++;
                        $ids[] = $result['id'];
                        $questions[] = $result['question'];
                        $options[] = $result['options'];
                        echo "<tr><td>{$result['id']}</td><td>{$result['question']}</td></tr>";
                        $details = explode(",",$result['options']);
                        $length = count($details);
                        for ($i = 0; $i < $length; $i++) {
                            echo "<tr><td><input type='radio' id='{$result['id']}{$details[$i]}' name='{$result['id']}' value='{$details[$i]}'></td><td><label for='{$result['id']}{$details[$i]}'>{$details[$i]}</label></td><tr>";
                        }
                    }
                }
                catch (PDOException $e) {
                    die('Server error');
                }
                ?>
                    <tr>
                        <td colspan="2"><button type="submit">Submit Quiz</button></td>
                    </tr>
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
                    $conn_str = DB_SYS.':host='.DB_HOST.';dbname='.DB_NAME;
                    try {
                        $pdo = new PDO($conn_str,DB_USER,DB_PASS);
                        $sql = "SELECT * FROM answers";
                        $stat = $pdo->prepare($sql);
                        $stat->execute();
                        while($result = $stat->fetch()){
                            if(strcmp($answers[$result['id']],$result['answer'])==0){
                                $correct++;
                            }
                        }
                    }
                    catch (PDOException $e) {
                        die('Server error');
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
            }
        ?>
        <p><a href="index.php">Move to textfile version</a></p>        
    </body>
</html>