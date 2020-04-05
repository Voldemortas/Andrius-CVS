<?php
function show($size = 20){
    global $website, $db;
    if(!isset($_GET['offset'])){
        $_GET['offset'] = 0;
    }
    /** @var Log[] $logs */
    $logs = [];
    $users = [];
    if(is_numeric($_GET['offset'])){
        $logs = Log::selectMany('', 'ORDER BY id DESC LIMIT '.$_GET['offset']*$size .', '.$size);
        $userIds = array_unique(array_map(
            /**
            * @param Log $e
            * @return int[]
            */
            function($e){
                return 'id = '.$e->user;
            }, $logs));
        /** @var User[] $users */
        $users = User::selectMany(join(' OR ', $userIds));
    }
    if($logs[0] != null){
        echo '<style>td, th{word-break: keep-all; white-space: nowrap;}</style>
<table><tr><th>URL</th><th>Vartotojas</th><th>Data</th><th>Laikas</th><th>IP</th></tr>';
        foreach($logs as $log){
            /** @var User $usr */
            $usr = array_values(array_filter($users, function($e) use ($log) { return $e->id == $log->user;}))[0];
            $time = round(explode('.', $log->time)[0]);
            echo '<tr><td style="word-break: break-all; white-space: normal">'.$log->url.'</td><td>'.$usr->email.'</td><td>'.date('Y-m-d', $time).'</td>
        <td>'.date('H:i:s', $time).'</td>
        <td>'.$log->ip.'</td></tr>';
        }
        ?>
        </table>
        <?php
        $amount = ceil($db->query('SELECT count(id) as amount FROM log')->fetchArray()[0]/$size);
        if($amount == 1){
            //do nothing
        }else{
            echo '<br />';
            if($_GET['offset'] != 0){
                echo '<a href="'.$website->url.'admin/logs/show/'. ($_GET['offset']-1) .'"><< Ankstesnis </a>';
            }
            echo '<input min="0" max="'.($amount-1).'" class="small-input" type="number" form="page" value="'.$_GET['offset'].'" />';
            if($_GET['offset'] != $amount-1){
                echo '<a href="'.$website->url.'admin/logs/show/'. ($_GET['offset']+1) .'"> Kitas >></a>';
            }
        }
        ?>
        <?php
    }
}
switch($_GET['action']){
    case 'show':
        show();
        break;
    default:
        show();
        break;
}