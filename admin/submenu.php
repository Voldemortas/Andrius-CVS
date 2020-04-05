<?php
function show($size = 10){
    global $website, $db;
    if(!isset($_GET['offset'])){
        $_GET['offset'] = 0;
    }
    /** @var Submenu[] $menus */
    $menus = [];
    if(is_numeric($_GET['offset'])){
        $menus = Submenu::selectMany('', 'LIMIT '.$_GET['offset']*$size .', '.$size);
    }
    if($menus[0] != null){
        echo 'Galite <a href="'.$website->url.'admin/submenu/add/">pridėti naują</a> meniu punktą
<table><tr><th>Tekstas</th><th>url</th><th>Rolės</th><th>Tėvinis meniu</th><th>Veiksmas</th></tr>';
        foreach($menus as $menu){
            /** @var Menu $parent */
            $parent = Menu::selectOne('id = '.$menu->menu);
            echo '<tr><td>'.$menu->text.'</td><td>'.$menu->url.'</td><td>'.Role::toString($menu->roles).'</td>
        <td>'.$parent->text.'</td>
        <td><a href="'.$website->url.'admin/submenu/edit/'.$menu->id.'/">Keisti</a><br />
        <a data-action="delete" href="'.$website->url.'admin/submenu/delete/'.$menu->id.'/">Šalinti</a></td></tr>';
        }
        ?>
        </table>
        <?php
        $amount = ceil($db->query('SELECT count(id) as amount FROM submenu')->fetchArray()[0]/$size);
        if($amount == 1){
            //do nothing
        }else{
            echo '<br />';
            if($_GET['offset'] != 0){
                echo '<a href="'.$website->url.'admin/submenu/show/'. ($_GET['offset']-1) .'"><< Ankstesnis </a>';
            }
            echo '<input min="0" max="'.($amount-1).'" class="small-input" type="number" form="page" value="'.$_GET['offset'].'" />';
            if($_GET['offset'] != $amount-1){
                echo '<a href="'.$website->url.'admin/submenu/show/'. ($_GET['offset']+1) .'"> Kitas >></a>';
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
function add(){
    global $db, $website;
    $added = 0;
    if(isset($_POST['text']) && isset($_POST['url']) && isset($_POST['local']) && isset($_POST['roles']) && isset($_POST['menu'])){
        if($_POST['roles'][0] != 0) {
            $added = array_reduce($_POST['roles'], function ($acc, $e) {
                return $acc + pow(2, $e-1);
            }, 0);
        }
        if(isset($_POST['no']) && $_POST['no'] == 'true'){
            $added *= -1;
        }
        if($_POST['local'] == 'false'){
            $_POST['url'] = 'redirect/'.urlencode(urlencode($_POST['url']));
        }
        $menu = new Submenu($_POST['text'], $_POST['url'], $added, $_POST['menu']*1);
        $db->query($menu->insert()) OR die('Nepavyko');
        header('Location: '.$website->url.'admin/submenu/');
    }
    ?>
    <style>
        .insideText{
            font-size: 19px;
            vertical-align: bottom;
        }
    </style>
        <form method="post">
            <fieldset>
                <legend>Meniu duomenys</legend>
                <span class="insideText">[url </span><input type="text" required name="url" placeholder="nuorodos adresas"
                /><span class="insideText">]</span><input type="text" required name="text" placeholder="tekstas"
                /><span class="insideText">[/url]</span><br />
                <sup>tuščiai nuorodai naudokite <b>#</b></sup><br />
                Langely <i>„tekstas“</i> galite naudoti BBcode žymas<br />
                <select required name="local" size="2" style="overflow: hidden;">
                    <option value="true" selected>Vietinė nuoroda</option>
                    <option value="false">Išorinė nuoroda</option>
                </select><br />
                <select name="menu" required>
                    <option value="" selected disabled hidden>Pasirinkite tėvinį meniu</option>
                    <?php
                    /** @var Menu[] $parents */
                    $parents = Menu::selectMany();
                    foreach($parents as $parent){
                        echo '<option value="'.$parent->id.'">'.$parent->text.'</option>';
                    }
                    ?>
                </select>
                <hr />
                <?php
                Role::makeForm();
                ?>
                <hr /><input type="submit" />
            </fieldset>
        </form>
        <script>
            let inputs = [...document.getElementsByTagName('input')].filter(e => e.name == 'roles[]');
            inputs[0].checked = true;
            inputs[0].addEventListener('change', ()=>{
                if(inputs[0].checked){
                    for (let i = 1; i < inputs.length; i++) {
                        inputs[i].checked = false;
                    }
                }
            });
            for(let i = 1; i < inputs.length; i++){
                inputs[i].addEventListener('change', ()=>{
                    if(inputs[i].checked){
                        inputs[0].checked = false;
                    }
                })
            }
        </script>
    <?php
}
function delete(){
    global $website, $db;
    $db->query(Submenu::deleteAny('id = '.$_GET['offset']*1));
    header('Location: '.$website->url.'admin/submenu');
}
function edit(){
    global $db, $website;
    $added = 0;
    if(isset($_POST['text']) && isset($_POST['url']) && isset($_POST['local']) && isset($_POST['roles']) && isset($_POST['menu'])){
        if($_POST['roles'][0] != 0) {
            $added = array_reduce($_POST['roles'], function ($acc, $e) {
                return $acc + pow(2, $e-1);
            }, 0);
        }
        if(isset($_POST['no']) && $_POST['no'] == 'true'){
            $added *= -1;
        }
        if($_POST['local'] == 'false'){
            $_POST['url'] = 'redirect/'.urlencode(urlencode($_POST['url']));
        }
        $menu = new Submenu($_POST['text'], $_POST['url'], $added, $_POST['menu']*1);
        $menu->id = $_GET['offset']*1;
        $db->query($menu->edit('id = '.$menu->id)) OR die('Nepavyko');
        header('Location: '.$website->url.'admin/submenu/');
    }
    /** @var Submenu $menu */
    $menu = Submenu::selectOne('id = '.$_GET['offset']*1);
    ?>
    <style>
        .insideText{
            font-size: 19px;
            vertical-align: bottom;
        }
    </style>
    <form method="post">
        <fieldset>
            <legend>Meniu duomenys</legend>
            <span class="insideText">[url </span><input type="text" name="url" placeholder="nuorodos adresas"
                value="<?php echo $menu->url; ?>"/><span class="insideText">]</span>
            <input type="text" name="text" placeholder="tekstas" value="<?php echo $menu->text; ?>"
            /><span class="insideText">[/url]</span><br />
            <sup>tuščiai nuorodai naudokite <b>#</b></sup><br />
            Langely <i>„tekstas“</i> galite naudoti BBcode žymas<br />
            <select name="local" size="2" style="overflow: hidden;">
                <option value="true" selected>Vietinė nuoroda</option>
                <option value="false">Išorinė nuoroda</option>
            </select><br />
            <select name="menu" required>
                <?php
                /** @var Menu[] $parents */
                $parents = Menu::selectMany();
                foreach($parents as $parent){
                    $selected = ($parent->id == $menu->menu)?' selected':'';
                    echo '<option value="'.$parent->id.'"'.$selected.'>'.$parent->text.'</option>';
                }
                ?>
            </select><hr />
            <?php
            Role::makeForm($menu->roles);
            ?>
            <hr /><input type="submit" />
        </fieldset>
    </form>
    <script>
        let inputs = [...document.getElementsByTagName('input')].filter(e => e.name == 'roles[]');
        inputs[0].addEventListener('change', ()=>{
            if(inputs[0].checked){
                for (let i = 1; i < inputs.length; i++) {
                    inputs[i].checked = false;
                }
            }
        });
        for(let i = 1; i < inputs.length; i++){
            inputs[i].addEventListener('change', ()=>{
                if(inputs[i].checked){
                    inputs[0].checked = false;
                }
            })
        }
    </script>
    <?php
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