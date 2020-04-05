<?php
function show($size = 20){
    global $website, $db;
    if(!isset($_GET['offset'])){
        $_GET['offset'] = 0;
    }
    /** @var User[] $users */
    $users = [];
    if(is_numeric($_GET['offset'])){
        $users = User::selectMany('id <> 0', 'LIMIT '.$_GET['offset']*$size .', '.$size);
    }
    if($users[0] != null){
        echo '
<table><tr><th>El. paštas</th><th>Rolės</th><th>Veiksmas</th></tr>';
        foreach($users as $usr){
            echo '<tr><td>'.$usr->email.'</td><td>'.Role::toString($usr->roles).'</td>
        <td><a href="'.$website->url.'admin/users/edit/'.$usr->id.'/">Keisti</a><br />
        <a data-action="delete" href="'.$website->url.'admin/users/delete/'.$usr->id.'/">Šalinti</a></td></tr>';
        }
        ?>
        </table>
        <?php
        $amount = ceil($db->query('SELECT count(id) as amount FROM user')->fetchArray()[0]/$size);
        if($amount == 1){
            //do nothing
        }else{
            echo '<br />';
            if($_GET['offset'] != 0){
                echo '<a href="'.$website->url.'admin/users/show/'. ($_GET['offset']-1) .'"><< Ankstesnis </a>';
            }
            echo '<input min="0" max="'.($amount-1).'" class="small-input" type="number" form="page" value="'.$_GET['offset'].'" />';
            if($_GET['offset'] != $amount-1){
                echo '<a href="'.$website->url.'admin/users/show/'. ($_GET['offset']+1) .'"> Kitas >></a>';
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
function edit(){
    global $db, $website;
    /** @var User $usr */
    $usr = User::selectOne('id = '.$_GET['offset']*1);
    $added = 0;
    if(isset($_POST['roles'])){
        $added = array_reduce($_POST['roles'], function ($acc, $e) {
            return $acc + pow(2, $e);
        }, 0);
        if($added == 0){
            header('Location: '.$website->url.'admin/users/edit/'.$_GET['offset']);
        }
        $usr->roles = $added;
        $db->query($usr->edit('id = '.$usr->id)) OR die('Nepavyko');
        header('Location: '.$website->url.'admin/users/');
    }
    /** @var Role[] $roles */
    $roles = Role::selectMany('id <> 0');
    $bases = array_map(function($e){ return log($e, 2);}, $usr->roles());
    ?>
    <form method="post">
        <fieldset>
            <legend>Vartotojo <?php echo $usr->email; ?> rolių keitimas</legend>
            <?php
            foreach ($roles as $role){
                $checked = '';
                if(in_array($role->id, $bases)){
                    $checked = ' checked';
                }
                echo '<label>'.$role->name.' <input type="checkbox" name="roles[]" value="'.$role->id.'" '.$checked.'/></label><br />';
            }
            ?>
            <hr /><input type="submit" />
        </fieldset>
    </form>
    <?php
}
switch($_GET['action']){
    case 'show':
        show();
        break;
    case 'edit':
        edit();
        break;
    default:
        show();
        break;
}