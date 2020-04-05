<?php
if(!isset($_POST['email']) || !isset($_POST['password2']) || !isset($_POST['password1'])){
    ?>
    <div class="content" style="text-align: center">
        <h2>Registracija</h2>
        <form action="<?php echo $website->url; ?>register/" method="post">
            <fieldset style="width: fit-content; right: 0; left: 0;   margin:auto;">
                <legend>Jūsų duomenys</legend>
                <p><input type="email" name="email" placeholder="El. paštas" required/></p>
                <p><input type="password" name="password1" placeholder="Slaptažodis" required/></p>
                <p><input type="password" name="password2" placeholder="Pakartokite slaptažodį" required/></p>
                <input type="submit"/>
            </fieldset>
        </form>
    </div>
    <?php
}elseif($_POST['password1'] != $_POST['password2']){
    ?>
    <div class="content" style="text-align: center">
        <h2>Registracija</h2>
        <form action="<?php echo $website->url; ?>register/" method="post">
            <fieldset style="width: fit-content; right: 0; left: 0;   margin:auto;">
                <legend>Jūsų duomenys</legend>
                <p><input type="email" name="email" placeholder="El. paštas" required/></p>
                <p><input type="password" name="password1" placeholder="Slaptažodis" required/></p>
                <p><input type="password" name="password2" placeholder="Pakartokite slaptažodį" required/></p>
                <input type="submit"/>
            </fieldset>
        </form>
    </div>
    <br /><span style="color: red; font-weight: bolder">Slaptažodžiai nesutampa.</span>
    <?php
}else{
    /** @var User $user */
    $user = User::selectOne('email = "'.SQLite3::escapeString($_POST['email']).'"');
    if($user == null){
        $message = '<h2>Jūs sėkmingai užsiregistravote</h2>
            Galite <a href="<?php echo $website->url;?>login/">Prisijungti</a>.';
        $user = new User($_POST['email'], password_hash($_POST['password1'], PASSWORD_BCRYPT), 1);
        $db->query($user->insert()) OR $message = 'Nepavyko užregistruoti';
        ?>
        <div class="content" style="text-align: center">
            <?php echo $message; ?>
        </div>
        <?php
    }else {
        ?>
        <div class="content" style="text-align: center">
            <h2>Registracija</h2>
            <form action="<?php echo $website->url; ?>register/" method="post">
                <fieldset style="width: fit-content; right: 0; left: 0;   margin:auto;">
                    <legend>Jūsų duomenys</legend>
                    <p><input type="email" name="email" placeholder="El. paštas" required/></p>
                    <p><input type="password" name="password1" placeholder="Slaptažodis" required/></p>
                    <p><input type="password" name="password2" placeholder="Pakartokite slaptažodį" required/></p>
                    <input type="submit"/>
                </fieldset>
            </form>
            <br/><span style="color: red; font-weight: bolder">Toks el.paštas jau užregistruotas</span>
        </div>
        <?php
    }
}
