<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
    <form action="" method="post">
        <p>パスワードはチーム名（半角）です</p>
        <p>入力フォーム</p>
        <input type="string" name="name" value="名前"><br>
        <input type="string" name="comment" value="コメント"><br>
        <input type="string" name="passward2" value="passward">
        <input type="submit" name="submit">
        <p>削除番号指定フォーム</p>
        <input type="string" name="number" value="番号"><br>
        <input type="string" name="key" value="passward">
        <input type="submit" name="submit" value="削除">
        <p>編集番号指定ホーム</p>
        <input type="string" name="newnumber" value="番号"><br>
        <input type="string" name="newkey" value="passward">
        <input type="submit" name="submit" value="編集">
    </form>    
    
    <?php
         //4-2以降でも毎回接続は必要。
         //$dsnの式の中にスペースを入れないこと！

         // 【サンプル】
         // ・データベース名：*****
         // ・ユーザー名：*****
         // ・パスワード：*****
         // の学生の場合：

         // DB接続設定
         $dsn = *****;
         $user = *****;
         $password = *****;
         $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

         //4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
         $sqlgettb = "CREATE TABLE IF NOT EXISTS tbtest_m_5"
         ." ("
         . "id varchar(32),"
         . "name varchar(60),"
         . "comment TEXT,"
         . "date char(63),"
         . "passward varchar(33)"
         .");";
         $stmtgettb = $pdo->query($sqlgettb);
         
         $flaggettb =  "SELECT * FROM tbtest_m_5 WHERE passward IS NOT NULL";
         
         //フォーム入力データ取得
         $correctpassward = "";
         
         $count = 0;
         $numberpassward = 0;
         $numberinput = 0;
         
         $flagcorrectpassward = 0;
         $flagedit = 0;
         $flagshow = 0;
         $flagexists = 0;
         
         //正しいパスワードを格納
         if($flaggettb == TRUE){
                 $sqlpassward = 'SELECT passward FROM tbtest_m_5 LIMIT 1';
                 $stmtpassward = $pdo -> query($sqlpassward);
                 $resultspassward = $stmtpassward -> fetchAll();
                 
                 foreach($resultspassward as $rowpassward){
                    $correctpassward = $rowpassward['passward'];
                 }
     
                 $sqlid = 'SELECT id FROM tbtest_m_5';
                 $stmtid = $pdo -> query($sqlid);
                 $resultsid = $stmtid -> fetchAll();  
                 
                 foreach($resultsid as $rowid){
                    $count = $rowid['id'];
                 }
                 $flagcorrectpassward = 1;
         }
         
         //入力フォーム
         if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["passward2"])){
             $names = $_POST["name"];
             $comments = $_POST["comment"];
             $passwards = $_POST["passward2"];
             $newnumbers = $_POST["newnumber"];
             
             //2行目以降
             if($flagcorrectpassward == 1 && $names != "名前" && $comments != "コメント" && $newnumbers == "番号" && $passwards == $correctpassward && $count != 0){
                 $date = date("Y年m月d日 H時i分s秒");
                 $countinput = $count+1;
                 
                 $sqlinput = $pdo -> prepare("INSERT INTO tbtest_m_5 (id, name, comment, date) VALUES (:id, :name, :comment, :date)");
                 $sqlinput -> bindParam(':id', $countinput, PDO::PARAM_INT);
                 $sqlinput -> bindParam(':name', $names, PDO::PARAM_STR);
                 $sqlinput -> bindParam(':comment', $comments, PDO::PARAM_STR);
                 $sqlinput -> bindParam(':date', $date, PDO::PARAM_STR);
                 
                 $sqlinput ->execute();
                 $flagshow = 1;
             //1行目
             }elseif($names != "名前" && $comments != "コメント" && $newnumbers == "番号" && $correctpassward == "" && $count == 0){
                 $date = date("Y年m月d日 H時i分s秒");
                 $indent = 1;

                 $sqlinput = $pdo -> prepare("INSERT INTO tbtest_m_5 (id, name, comment, date, passward) VALUES (:id, :name, :comment, :date, :passward)");
                 $sqlinput -> bindParam(':id', $indent, PDO::PARAM_INT);
                 $sqlinput -> bindParam(':name', $names, PDO::PARAM_STR);
                 $sqlinput -> bindParam(':comment', $comments, PDO::PARAM_STR);
                 $sqlinput -> bindParam(':date', $date, PDO::PARAM_STR);
                 $sqlinput -> bindParam(':passward', $passwards, PDO::PARAM_STR);
                 
                 $sqlinput ->execute();      
                 $flagshow = 1;
             }
         }
         
         //削除番号指定フォーム
         if($flagcorrectpassward == 1 && !empty($_POST["number"]) && !empty($_POST["key"]) && $correctpassward != ""){
             $numbers = $_POST["number"];
             $passwards = $_POST["key"];
             $names = $_POST["name"];
             
             if($names == "名前" && $numbers != "番号" && $passwards == $correctpassward){
                 $numbers = intval($numbers);

                 $sqldelete = 'delete from tbtest_m_5 where id=:id';
                 $stmtdelete = $pdo -> prepare($sqldelete);
                 $stmtdelete -> bindParam(':id', $numbers, PDO::PARAM_INT);
                 $stmtdelete -> execute();
                 
                 $flagshow = 1;
             }
         }
         
         //編集番号指定フォーム
         if($flagcorrectpassward == 1 && !empty($_POST["newnumber"]) && !empty($_POST["newkey"]) && $correctpassward != ""){
             $newnumbers = $_POST["newnumber"];
             $names = $_POST["name"];
             $comments = $_POST["comment"];
             $passwards = $_POST["newkey"];
             
             $date = date("Y年m月d日 H時i分s秒");
             
             if($newnumbers != "番号" && $names != "名前" && $comments != "コメント" && $passwards == $correctpassward){
                 $newnumbers = intval($newnumbers);

                 $sqlflagedit = 'SELECT * FROM tbtest_m_5';
                 $stmtflagedit = $pdo -> query($sqlflagedit);
                 $resultflagedit = $stmtflagedit -> fetchAll();
                 foreach($resultflagedit as $rowflagedit){
                     if($rowflagedit['id'] == $newnumbers){
                         $flagedit = 1;
                     }
                 }
                 
                 //番号がある
                 if($flagedit == 1){
                     $sqledit = 'UPDATE tbtest_m_5 SET name=:name, comment=:comment, date=:date WHERE id=:id';
                     $stmtedit = $pdo -> prepare($sqledit);
                     $stmtedit -> bindParam(':name', $names, PDO::PARAM_STR);
                     $stmtedit -> bindParam(':comment', $comments, PDO::PARAM_STR);
                     $stmtedit -> bindParam(':date', $date, PDO::PARAM_STR);
                     $stmtedit -> bindParam(':id', $newnumbers, PDO::PARAM_STR);
                     $stmtedit -> execute();
                     
                     $flagshow = 1;
                 }
                 //番号がない
                 else{
                     $countedit = $count+1;
                     $sqledit = $pdo -> prepare("INSERT INTO tbtest_m_5 (id, name, comment, date) VALUES (:id, :name, :comment, :date)");
                     $sqledit -> bindParam(':id', $countedit, PDO::PARAM_INT);
                     $sqledit -> bindParam(':name', $names, PDO::PARAM_STR);
                     $sqledit -> bindParam(':comment', $comments, PDO::PARAM_STR);
                     $sqledit -> bindParam(':date', $date, PDO::PARAM_STR);
                 
                     $sqledit ->execute();
                     
                     $flagshow = 1;
                 }
             }
         }
         
         //表示
         if($flagshow == 1){
             $sqlshow ='SELECT * FROM tbtest_m_5';
             $stmtshow = $pdo -> query($sqlshow);
             $resultshow = $stmtshow -> fetchAll();
             foreach ($resultshow as $rowshow){
                 echo $rowshow['id'].' ';
                 echo $rowshow['name'].' ';
                 echo $rowshow['comment'].' ';
                 echo $rowshow['date'].'<br>';
                 echo "<hr>";
            }             
         }
         
          if($correctpassward != ""  && (!empty($_POST["passward2"]) || (!empty($_POST["key"])) || (!empty($_POST["newkey"])))){
             $passwards = $_POST["passward2"];
             $keys = $_POST["key"];
             $newkeys = $_POST["newkey"];
             
             if($passwards != $correctpassward && $keys != $correctpassward && $newkeys != $correctpassward){
                 echo "パスワードが間違っています";
             }
         }
    ?>
</body>
</html>
