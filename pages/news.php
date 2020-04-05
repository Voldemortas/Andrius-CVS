<?php
/** @var News[] $news */
include "BBCode.php";
$news = [];
if(isset($_GET['id'])){
    $news[] = News::selectOne('url = "'.SQLite3::escapeString($_GET['id']).'"');
}else{
    $news = News::selectMany('nonprivate <> 1','ORDER BY id DESC');
}
?>
<nav class="sideNav" style="float: right">
    <h2>Naudingos nuorodos</h2>
    <p>Naršyklė <a href="https://brave.com/">Brave</a> - naršyklė, kuri tavęs neseka!</p>
    <p>Kažkas kieto</p>
</nav>
<?php
if($news[0] == null){
    echo "<article>\n\t<h2>Nieko nerasta.</h2>\n</article>";
    return;
}
foreach($news as $new){
    $A0 = '';
    $A1 = '';
    if(!isset($_GET['id'])){
        $A0 = '<a href="'.$website->url.'news/'.$new->url.'/">';
        $A1 = '</a>';
    }
    if(Role::allowed($new->roles, $user)){
        echo "<article>\n\t<h2>" . $A0 . $new->title . $A1 . "</h2>\n\t" . BBCode::parse($new->text) . "\n</article>";
    }elseif(isset($_GET['id'])){
        echo "<article>\n\t<h2>Deja!<br />Šis įrašas egzistuoja, bet jo pasiekti jums negalima.</h2>\n</article>";
    }
}
?>