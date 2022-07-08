<!DOCTYPE html>
<html lang ='ja'>
    <head>
        <meta charset = 'UTF-8'>
        <title>mission_5-1</title>
    </head>
    <body>
        <form action = '' method = 'POST'>
            <input type = 'text'   name = 'name'    value = '名前'><br>
            <input type = 'text'   name = 'pass'    placeholder = 'パスワード'><br>
            <input type = 'text'   name = 'comment' value = 'コメント'>
            <input type = 'submit' name = 'submit'  value = '新規投稿'><br>
            <input type = 'number' name = 'editnum'>
            <input type = 'submit' name = 'edit'    value = '編集'><br>
            ↑編集番号（半角）<br>
            <input type = 'number' name = 'delnum'>
            <input type = 'submit' name = 'del'     value = '削除'><br>
            ↑削除対象番号（半角）
        </form>
        <?php
        $dsn = 'mysql:dbname="データベース名";host=localhost';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = 'CREATE TABLE IF NOT EXISTS mission_fiveone'
        .' ('
        . 'id INT AUTO_INCREMENT PRIMARY KEY,'
        . 'name char(32),'
        . 'comment TEXT,'
        . 'date TEXT,'
        . 'pass TEXT'
        .');';
        $stmt = $pdo->query($sql);
        
        
        if(!empty($_POST['comment']) && empty ($_POST['editnum']) && !empty($_POST['pass'] && empty ($_POST['delnum']))){
            $sql = $pdo -> prepare('INSERT INTO mission_fiveone (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)');
            $sql -> bindParam(':name',    $name,    PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date',    $date,    PDO::PARAM_STR);
            $sql -> bindParam(':pass',    $pass,    PDO::PARAM_STR);
            $name    = $_POST['name'];
            $comment = $_POST['comment'];
            $date    = date("Y/m/d H:i:s");
            $pass    = $_POST['pass'];//好きな名前、好きな言葉は自分で決めること
            $sql -> execute();
        }
        
        
        if(!empty($_POST['delnum']) && !empty($_POST['pass'])){
        $id = $_POST['delnum'];
        $passin = $_POST['pass'];
        $sql = 'SELECT pass FROM mission_fiveone WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach($results as $result){
            $pass = $result['pass'];
            if($pass == $passin){
            $sql = 'delete from mission_fiveone where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }}
            
        }
        
        
        if(!empty($_POST['editnum'])){
            $passin = $_POST['pass'];
            $id = $_POST['editnum']; //変更する投稿番号
            $sql =" SELECT pass FROM mission_fiveone WHERE id = :id ";
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach($results as $result){
            $pass = $result['pass'];
            if($pass == $passin){
            $comment = $_POST['comment']; //変更したい名前、変更したいコメントは自分で決めること
            $date = date("Y/m/d H:i:s");
            $sql = 'UPDATE mission_fiveone SET comment=:comment ,date=:date WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
                
            }
            
        }
        
        
        $sql = 'SELECT * FROM mission_fiveone';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']."<br>";
            echo "<hr>";
            
        }
            
        ?>
    </body>
</html>