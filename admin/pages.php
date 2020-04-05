<?php
function add(){
    global $db, $website;
    /** @var Page $page */
    $page = new Page();
    if(isset($_POST['title']) && $_POST['title'] != '' && isset($_POST['url']) && $_POST['url'] != '' && isset($_POST['code'])){
        $added = 0;
        if($_POST['roles'][0] != 0) {
            $added = array_reduce($_POST['roles'], function ($acc, $e) {
                return $acc + pow(2, $e-1);
            }, 0);
        }
        if(isset($_POST['no']) && $_POST['no'] == 'true'){
            $added *= -1;
        }
        $page = new Page($_POST['url'], $_POST['title'], $added);
        $db->query($page->insert()) OR DIE ('Nepavyko pridėti');
        $url = fopen('pages/'.$page->url.'.php', 'w') OR DIE ('Nepavyko atverti failo');
        fwrite($url, $_POST['code']) OR DIE ('Nepavyko pakeisti failo');
        chmod('pages/'.$page->url,0774) OR die ('Nepavyko pakeisti prieigos teisių, tačiau failas sukurtas sėkmingai.
        <br />Galite grįžti į <a href="'.$website->url.'admin/pages">puslapių sąrašą</a>.');
        header('Location: '.$website->url.'admin/pages');
    }
    $url = fopen('pages/'.$page->url.'.php', 'r');
    $contents = fread($url, filesize('pages/'.$page->url.'.php'));
    fclose($url);
    ?>
    <form method="post">
        <fieldset>
            <legend>Pridėti statinį puslapį</legend>
            <input type="text" name="title" placeholder="pavadinimas" required value="<?php echo $page->title;?>" /><br />
            <input type="text" name="url" placeholder="url" value="<?php echo $page->url;?>" required /><br />
            <?php
            Role::makeForm($page->roles);
            ?>
            <textarea required name="code" style="width: 100%; height: 500px"><?php echo $contents;?></textarea>
            <br /><sup>*Čia rašomas PHP (ir HTML) kodas, jeigu norite naudotis BBcode,<br />
                <a href="<?php echo $website->url; ?>admin/news/add/">sukurkite naują įrašą</a> su savybe <i>„Neviešas“</i> ir naudokite jo adresą.</sup><br />
            <input type="submit" />
        </fieldset>
    </form>
    <?php
}
function edit(){
    global $db, $website;
    /** @var Page $page */
    $page = new Page();
    if(isset($_POST['title']) && $_POST['title'] != '' && isset($_POST['url']) && $_POST['url'] != '' && isset($_POST['code'])){
        $added = 0;
        if($_POST['roles'][0] != 0) {
            $added = array_reduce($_POST['roles'], function ($acc, $e) {
                return $acc + pow(2, $e-1);
            }, 0);
        }
        if(isset($_POST['no']) && $_POST['no'] == 'true'){
            $added *= -1;
        }
        $page = new Page($_POST['url'], $_POST['title'], $added);
        $page->id = $_GET['offset']*1;
        $db->query($page->edit('id = '.$_GET['offset']*1)) OR DIE ('Nepavyko pridėti');
        $url = fopen('pages/'.$page->url.'.php', 'w') OR DIE ('Nepavyko atverti failo');
        fwrite($url, $_POST['code']) OR DIE ('Nepavyko pakeisti failo');
        header('Location: '.$website->url.'admin/pages');
    }
    $page = Page::selectOne('id = '.$_GET['offset'] * 1);
    $url = fopen('pages/'.$page->url.'.php', 'r');
    $contents = fread($url, filesize('pages/'.$page->url.'.php'));
    fclose($url);
    ?>
    <form method="post">
        <fieldset>
            <legend>Keisti statinį puslapį</legend>
            <input type="text" name="title" placeholder="pavadinimas" required value="<?php echo $page->title;?>" /><br />
            <input type="text" name="url" placeholder="url" value="<?php echo $page->url;?>" required /><br />
            <?php
            Role::makeForm($page->roles);
            ?>
            <textarea required name="code" style="width: 100%; height: 500px"><?php echo $contents;?></textarea>
            <br /><sup>*Čia rašomas PHP (ir HTML) kodas, jeigu norite naudotis BBcode,<br />
                <a href="<?php echo $website->url; ?>admin/news/add/">sukurkite naują įrašą</a> su savybe <i>„Neviešas“</i> ir naudokite jo adresą.</sup><br />
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
    /** @var Page[] $pages */
    $pages = [];
    if(is_numeric($_GET['offset'])){
        $pages = Page::selectMany('', 'LIMIT '.$_GET['offset']*$size .', '.$size);
    }
    if($pages[0] != null){
        echo 'Galite <a href="'.$website->url.'admin/pages/add/">pridėti naują</a> puslapį
<table><tr><th>Pavadinimas</th><th>url</th><th>Rolės</th><th>Veiksmas</th></tr>';
        foreach($pages as $new){
            echo '<tr><td>'.$new->title.'</td><td>'.$new->url.'</td><td>'.Role::toString($new->roles).'</td>
        <td><a href="'.$website->url.'admin/pages/edit/'.$new->id.'/">Keisti</a><br />
        <a data-action="delete" href="'.$website->url.'admin/pages/delete/'.$new->id.'/">Šalinti</a></td></tr>';
        }
        ?>
        </table>
        <?php
        $amount = ceil($db->query('SELECT count(id) as amount FROM page')->fetchArray()[0]/$size);
        if($amount == 1){
            //do nothing
        }else{
            echo '<br />';
            if($_GET['offset'] != 0){
                echo '<a href="'.$website->url.'admin/pages/show/'. ($_GET['offset']-1) .'"><< Ankstesnis </a>';
            }
            echo '<input min="0" max="'.($amount-1).'" class="small-input" type="number" form="page" value="'.$_GET['offset'].'" />';
            if($_GET['offset'] != $amount-1){
                echo '<a href="'.$website->url.'admin/pages/show/'. ($_GET['offset']+1) .'"> Kitas >></a>';
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
    $page = Page::selectOne('id = '.$_GET['offset']);
    $db->query(Page::deleteAny('id = '.$_GET['offset']*1));
    unlink('pages/'.$page->url.'.php');
    header('Location: '.$website->url.'admin/pages');
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