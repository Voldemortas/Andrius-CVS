<?php
function edit(){
    global $db, $website;
    /** @var Website $web */
    $web = Website::selectOne('');
    if(isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['url']) && $_POST['url'] != ''){
        if($_POST['url'][strlen($_POST['url'])-1] != '/'){
            $_POST['url'] .= '/';
        }
        $web = new Website($_POST['name'], $_POST['url'], $_POST['logo']);
        $db->query($web->edit('1 = 1')) OR DIE ('Nepavyko pakeisit');
        header('Location: '.$website->url.'admin/settings/');
    }
    ?>
    <form method="post">
        <fieldset>
            <legend>Keisti tinklalapio konfigūraciją</legend>
            <input type="text" name="name" placeholder="Tinklalapio pavadinimas" required value="<?php echo $web->name; ?>" /><br />
            <input type="text" name="url" placeholder="Puslapio adresas" required value="<?php echo $web->url; ?>" /><br />
            <sub>*Patartina nekeisti, nes neveiks daugelis dalykų.</sub><br />
            <input type="url" name="logo" placeholder="Puslapio ikona" required value="<?php echo $web->logo; ?>" />
            <br /><input type="submit" />
        </fieldset>
    </form>
    <?php
}
edit();