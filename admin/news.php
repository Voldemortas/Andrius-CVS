<?php
function add()
{
    global $db, $website;
    $bb_height = '400px';
    $bb_value = 'Čia jūsų naujiena';
    $bb_name = 'text';
    if(isset($_POST['title']) && $_POST['title'] != ''){
        if($_POST['roles'][0] != 0) {
            $added = array_reduce($_POST['roles'], function ($acc, $e) {
                return $acc + pow(2, $e-1);
            }, 0);
        }
        if(isset($_POST['no']) && $_POST['no'] == 'true'){
            $added *= -1;
        }
        if($_POST['url'] == ''){
            $_POST['url'] = time();
        }
        $_POST['nonprivate'] = $_POST['nonprivate'] == 'true'?0:1;
        $news = new News($_POST['text'], $_POST['url'], $_POST['title'], $added, $_POST['nonprivate']);
        $db->query($news->insert()) OR die('Nepavyko');
        header('Location: '.$website->url.'admin/news/');
    }
    ?>
    <form method="post">
        <fieldset>
            <legend>Rašyti naujieną</legend>
            <input type="text" name="title" placeholder="pavadinimas" required><br />
            <input type="text" name="url" placeholder="vietinis nuorodos adresas" /><br />
            <sup>*Palikite tuščią ir jis bus sugeneruotas automatiškai</sup><br />
            <select name="nonprivate" size="2" style="overflow: hidden;">
                <option value="true" selected>Viešas įrašas</option>
                <option value="false">Neviešas įrašas</option>
            </select><br />
            <sup>*Vieši įrašai bus matomi skiltyje <i>„Naujienos“</i></sup><hr />
            <?php
            Role::makeForm(0);
            ?>
            <hr />
            <?php
            include 'bb.php';
            ?><br />
            <input type="submit" />
        </fieldset>
    </form>
    <?php
}
function edit(){
    global $db, $website;
    if(isset($_POST['title']) && $_POST['title'] != ''){
        if($_POST['roles'][0] != 0) {
            $added = array_reduce($_POST['roles'], function ($acc, $e) {
                return $acc + pow(2, $e-1);
            }, 0);
        }
        if(isset($_POST['no']) && $_POST['no'] == 'true'){
            $added *= -1;
        }
        if($_POST['url'] == ''){
            $_POST['url'] = time();
        }
        $_POST['nonprivate'] = $_POST['nonprivate'] == 'true'?0:1;
        $news = new News($_POST['text'], $_POST['url'], $_POST['title'], $added, $_POST['nonprivate']);
        $news->id = $_GET['offset']*1;
        $db->query($news->edit('id = '.$news->id)) OR die('Nepavyko');
        header('Location: '.$website->url.'admin/news/');
    }
    /** @var News $news */
    $news = News::selectOne('id = '.$_GET['offset']*1);
    $bb_height = '400px';
    $bb_value = $news->text;
    $bb_name = 'text';
    ?>
    <form method="post">
        <fieldset>
            <legend>Keisti naujieną</legend>
            <input type="text" name="title" placeholder="pavadinimas" required value="<?php echo $news->title; ?>"><br />
            <input type="text" name="url" placeholder="vietinis nuorodos adresas" value="<?php echo $news->url; ?>" /><br />
            <sup>*Palikite tuščią ir jis bus sugeneruotas automatiškai</sup><br />
            <select name="nonprivate" size="2" style="overflow: hidden;">
                <option value="true"<?php echo $news->nonprivate == 0?' selected':''; ?>>Viešas įrašas</option>
                <option value="false"<?php echo $news->nonprivate == 1?' selected':'' ?>>Neviešas įrašas</option>
            </select><br />
            <sup>*Vieši įrašai bus matomi skiltyje <i>„Naujienos“</i></sup><hr />
            <?php
            Role::makeForm($news->roles);
            ?>
            <hr />
            <?php
            include 'bb.php';
            ?><br />
            <input type="submit" />
        </fieldset>
    </form>
    <?php
}
function show($size = 10){
    global $website, $db;
    if(!isset($_GET['offset'])){
        $_GET['offset'] = 0;
    }
    /** @var News[] $news */
    $news = [];
    if(is_numeric($_GET['offset'])){
        $news = News::selectMany('', 'LIMIT '.$_GET['offset']*$size .', '.$size);
    }
    if($news[0] != null){
        echo 'Galite <a href="'.$website->url.'admin/news/add/">pridėti naują</a> įrašą
<table><tr><th>Pavadinimas</th><th>url</th><th>Viešas</th><th>Rolės</th><th>Veiksmas</th></tr>';
        foreach($news as $new){
            echo '<tr><td>'.$new->title.'</td><td>'.$new->url.'</td>
        <td>'.(($new->nonprivate === 1)?'Neviešas':'Viešas').'</td><td>'.Role::toString($new->roles).'</td>
        <td><a href="'.$website->url.'admin/news/edit/'.$new->id.'/">Keisti</a><br />
        <a data-action="delete" href="'.$website->url.'admin/news/delete/'.$new->id.'/">Šalinti</a></td></tr>';
        }
        ?>
        </table>
        <?php
        $amount = ceil($db->query('SELECT count(id) as amount FROM news')->fetchArray()[0]/$size);
        if($amount == 1){
            //do nothing
        }else{
            echo '<br />';
            if($_GET['offset'] != 0){
                echo '<a href="'.$website->url.'admin/news/show/'. ($_GET['offset']-1) .'"><< Ankstesnis </a>';
            }
            echo '<input min="0" max="'.($amount-1).'" class="small-input" type="number" form="page" value="'.$_GET['offset'].'" />';
            if($_GET['offset'] != $amount-1){
                echo '<a href="'.$website->url.'admin/news/show/'. ($_GET['offset']+1) .'"> Kitas >></a>';
            }
        }
        ?>
        <script>
            let deleters = [...document.getElementsByTagName('a')].filter(e=> e.dataset.action == 'delete');
            for(let i = 0; i < deleters.length; i++){
                deleters[i].addEventListener('click', (event)=>{
                    if(!confirm('Ar tikrai norite ištrinti šį elementą?')){
                        event.preventDefault();
                    }
                });
            }
        </script>
        <?php
    }
}
function delete(){
    global $website, $db;
    $db->query(News::deleteAny('id = '.$_GET['offset']*1));
    header('Location: '.$website->url.'admin/news');
}
switch($_GET['action']){
    case 'show':
        show();
        break;
    case 'add':
        add();
        break;
    case 'edit':
        edit();
        break;
    case 'delete':
        delete();
        break;
    default:
        show();
        break;
}