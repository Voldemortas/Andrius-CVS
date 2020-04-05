<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.27
 * Time: 01.32
 */
if(!file_exists('config/config.php')){
    header('location: install.php');
}
/** @var Menu[] $menus */
$menus = Menu::selectMany();
?>
<nav id="mainNav">
    <?php
    /** @var Menu $menu */
    foreach($menus as $menu){
        if(Role::allowed($menu->roles, $user)){
            $url = '';
            if($menu->url == '#'){
                $url = '#';
            }else{
                $url = $website->url.$menu->url;
            }
            echo "<nav>\n\t\t<a href=\"".$url.'/">'.$menu->text.'</a>';
            $submenus = Submenu::selectMany('menu = '.($menu->id * 1));
            if($submenus[0] != null) {
                /** @var Submenu $submenu */
                foreach($submenus as $submenu){
                    if(Role::allowed($submenu->roles, $user)){
                        echo "\n\t\t<a href=\"". $website->url . $submenu->url . '/">' . $submenu->text . '</a>';
                    }
                }
            }
            echo "\n\t</nav>";
        }
    }
    ?>

</nav>
