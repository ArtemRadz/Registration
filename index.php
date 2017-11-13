<?

require_once 'db.php';

//Connect to db
$db = new Db();
$db = $db->connect();


if(isset($_POST['submit']))
{
    $err = array();

    if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login']))
    {
        $err[] = "The login can consist only of letters of the English alphabet and numbers";
    }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "Login must be at least 3 characters and not more than 30";
    }

    $query = "SELECT COUNT(user_id) FROM users WHERE login = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array($_POST[login]));
    $date = $stmt->fetchColumn();
    if($date > 0)
    {
        $err[] = "Login already exists";
    }

    if(count($err) == 0)
    { 
        $login = $_POST['login'];
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $query = "INSERT INTO users SET login = ?, password = ?";
        $stmt = $db->prepare($query);
        $stmt->execute(array($login, $password));
        header("Location: login.php"); exit();
    } else
    {
        foreach($err as $error)
        {
            print $error."<br>";
        }
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
    <input name="submit" type="submit" value="Registration">
</form>