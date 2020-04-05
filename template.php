<?php
if(!file_exists('config/config.php')){
    header('location: install.php');
}
require_once 'config/config.php';
require_once 'classes/Property.php';
require_once 'classes/BasicInfo.php';
require_once 'classes/DatabaseLike.php';
foreach (glob("classes/*.php") as $filename)
{
    require_once $filename;
}

session_start();
/** @var Website $website */
$website = Website::selectOne();
$user = new User();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}
$url = "http" . (($_SERVER['HTTPS'] == "on") ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$url = str_replace($website->url, '', $url);
$time = $_SERVER["REQUEST_TIME_FLOAT"];
if(strlen(explode('.', $time)[1]) == 2){
    $time .= '0';
}
$log = new Log($_SERVER['REMOTE_ADDR'], $url, 0, $user->id, $time);
$db->query($log->insert());
/** @var Page $page */
$page = Page::selectOne('url = "'.SQLite3::escapeString($_GET['page']).'"');
$title = ' | '.$page->title;
$allowed = file_exists('pages/'.$_GET['page'].'.php') && Role::allowed($page->roles, $user);
if($allowed && $_GET['page'] == 'admin' && $_GET['id'] == 'database'){
    include_once 'pages/' . $_GET['page'] . '.php';
}
if($page == null || !$allowed){
    $title = ' | 404';
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $website->logo; ?>" />
    <title><?php echo $website->name.$title; ?></title>
    <link href="/scripts/basic.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
</head>
<body>
<?php
if($_GET['page'] != 'admin' || !$allowed) {
    include 'menu.php';
}
?>
<main>
    <?php
    if($allowed) {
        include_once 'pages/' . $_GET['page'] . '.php';
    }else{
        include_once 'pages/404.php';
    }?>
    </main>
<!--<footer>
    <hr />Andrius Simanaitis IFF-6/2
</footer>-->
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>
</html>
