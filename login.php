<?php

require_once 'db.php';

function generateHash($length) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) 
    {
        $code .= $chars[mt_rand(0, $clen)];  
    }

    return $code;
}

//Connect to db
$db = new Db();
$db = $db->connect();

if(isset($_POST['submit']))
{
    $query = "SELECT user_id, password FROM users WHERE login = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array($_POST[login]));
    $data = $stmt->fetch();

    if (password_verify($_POST['password'], $data['password']))
    {
        $hash = md5(generateHash(10));   
        $insip = ip2long($_SERVER['REMOTE_ADDR']);

        $query = "UPDATE users SET hash = :hash, ip = :insip WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":hash", $hash);
        $stmt->bindValue(":insip", $insip);
        $stmt->bindValue(":user_id", $data['user_id']);
        $stmt->execute();

        setcookie("id", $data['user_id'], time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30);

        header("Location: check.php"); exit();
    } else
    {
        print "Incorrect login or password";
    }

}

?>

<form method="POST">
    <label> Login 
        <input name="login" type="text">
    </label>
    <label> Password
        <input name="password" type="password">
    </label>
    <input name="submit" type="submit" value="Login">
</form>