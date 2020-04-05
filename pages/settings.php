<?php
$message = '';
if(isset($_POST['password0'])){
    if(!password_verify($_POST['password0'], $user->password)){
        $message = 'Blogas slaptažodis!';
    }else{
        if($_POST['email'] != '') {
            $user->email = $_POST['email'];
            if ($db->query($user->edit('id = '.$user->id))) {
                $message = 'El. paštas sėkmingai atnaujintas.<br />';
            } else {
                $message = 'Nepavyko pakeisti el. pašto!<br />';
            }
        }
        if($_POST['password1'] != $_POST['password2']){
            $message .= 'Naujieji slaptažodžiai nesutampa!';
        }else{
            $pass = $user->password;
            $user->password = password_hash($_POST['password1'], PASSWORD_BCRYPT);
            if ($db->query($user->edit('id = '.$user->id))) {
                $message .= 'Slaptažodis sėkmingai atnaujintas.<br />';
            } else {
                $message .= 'Nepavyko pakeisti slaptažodžio!<br />';
                $user->password = $pass;
            }
        }
    }
}
?>
<div class="content">
<h2>Paskyros nustatymai</h2>
<form method="post">
        <fieldset style="width: fit-content; right: 0; left: 0; margin:auto;">
            <legend>Keisti paskyros nustatymus</legend>
            <input type="email" name="email" placeholder="naujas el. paštas" /><br>
            <sup>*Galite palikti tuščią lauką, tuomet bus paliktas esamas el. paštas</sup><hr />
            <input type="password" name="password1" placeholder="naujas slaptažodis" /><br>
            <input type="password" name="password2" placeholder="pakartokite naują slaptažodis" /><br>
            <sup>*Galite palikti tuščius laukus, tuomet bus paliktas esamas slaptažodis</sup><hr />
            <input type="password" name="password0" placeholder="esamas slaptažodis" required /><br />
            <input type="submit">
        </fieldset>
    </form>
    <span><?php echo $message; ?></span>
</div>