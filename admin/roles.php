<?php
function add(){
    global $db, $website;
    /** @var Role $role */
    $role = new Role();
    if(isset($_POST['name']) && $_POST['name'] != ''){
        $role = new Role($_POST['name']);
        $db->query($role->insert()) OR DIE ('Nepavyko pridėti');
        header('Location: '.$website->url.'admin/roles');
    }
    ?>
    <form method="post">
        <fieldset>
            <legend>Pridėti rolę</legend>
            <input type="text" name="name" placeholder="Rolės pavadinimas" required value="<?php echo $role->name; ?>" />
            <br /><input type="submit" />
        </fieldset>
    </form>
    <?php
}
function edit(){
    global $db, $website;
    /** @var Role $role */
    $role = new Role();
    if(isset($_POST['name']) && $_POST['name'] != ''){
        $role = new Role($_POST['name']);
        $role->id = $_GET['offset'] * 1;
        $db->query($role->edit('id ='.$role->id)) OR DIE ('Nepavyko pakeisti');
        header('Location: '.$website->url.'admin/roles');
    }
    $role = Role::selectOne('id = '.$_GET['offset']*1);
    ?>
    <form method="post">
        <fieldset>
            <legend>Keisti statinį puslapį</legend>
            <input type="text" name="title" placeholder="Rolės pavadinimas" required value="<?php echo $role->name;?>" />
            <br /><input type="submit" />
        </fieldset>
    </form>
    <?php
}
function show($size = 10){
    global $website, $db;
    if(!isset($_GET['offset'])){
        $_GET['offset'] = 0;
    }
    /** @var Role[] $pages */
    $roles = [];
    if(is_numeric($_GET['offset'])){
        $roles = Role::selectMany('', 'LIMIT '.$_GET['offset']*$size .', '.$size);
    }
    if($roles[0] != null){
        echo 'Galite <a href="'.$website->url.'admin/roles/add/">pridėti naują</a> rolę
<table><tr><th>Pavadinimas</th><th>Veiksmas</th></tr>';
        foreach($roles as $role){
            echo '<tr><td>'.$role->name.'</td>
        <td><a href="'.$website->url.'admin/roles/edit/'.$role->id.'/">Keisti</a><br />
        <a data-action="delete" href="'.$website->url.'admin/roles/delete/'.$role->id.'/">Šalinti</a></td></tr>';
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