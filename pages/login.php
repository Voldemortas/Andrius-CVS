<?php
if($user->roles != 1){
    ?>
    <div class="content" style="text-align: center">
        <h2>JŪS JAU ESATE PRISIJUNGĘS!</h2>
        Galite grįžti į <a href="<?php echo $website->url;?>news">Pagrindinį puslapį</a> ir naršyti toliau.
    </div>
    <?php
    return;
}elseif(!isset($_POST['email']) || !isset($_POST['password'])){
    ?>
    <div class="content" style="text-align: center">
        <h2>Prisijungti</h2>
        <form action="<?php echo $website->url;?>login/" method="post">
            <fieldset style="width: fit-content; right: 0; left: 0;   margin:auto;">
                <legend>Jūsų duomenys</legend>
                <p><input type="email" name="email" placeholder="El. paštas" required /></p>
                <p><input type="password" name="password" placeholder="Slaptažodis" required /></p>
                <input type="submit" />
            </fieldset>
        </form>
    </div>
    <?php
}else{
    /** @var User $user */
    $user = User::selectOne('email = "'.SQLite3::escapeString($_POST['email']).'"');
    if($user != null){
        if(password_verify($_POST['password'], $user->password)) {
            $_SESSION['user'] = $user;
            header('Location: ' . $website->url . 'news/');
        }
    }
    ?>
    <div class="content" style="text-align: center">
        <h2>Prisijungti</h2>
        <form action="<?php echo $website->url;?>login/" method="post">
            <fieldset style="width: fit-content; right: 0; left: 0;   margin:auto;">
                <legend>Jūsų duomenys</legend>
                <p><input type="email" name="email" placeholder="El. paštas" required /></p>
                <p><input type="password" name="password" placeholder="Slaptažodis" required /></p>
                <input type="submit" />
            </fieldset>
        </form>
        <br /><span style="color: red; font-weight: bolder">Blogas paštas arba slaptažodis</span>
    </div>
    <?php
}