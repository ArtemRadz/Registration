<?

require_once 'db.php';

//Connect to db
$db = new Db();
$db = $db->connect();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{   
    $query = "SELECT * FROM users WHERE user_id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute(array(intval($_COOKIE['id'])));
    $userdata = $stmt->fetch();

    if(($userdata['hash'] !== $_COOKIE['hash']) 
        || ($userdata['user_id'] !== intval($_COOKIE['id'])) 
        || ((long2ip($userdata['ip']) !== $_SERVER['REMOTE_ADDR'])  
        && ($userdata['ip'] !== "0")))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print "Error";
    } else
    {
        print "Hello, ".$userdata['login'].". Welcome!";
    }
} else
{
    print "Please enable cookies";
}

?>