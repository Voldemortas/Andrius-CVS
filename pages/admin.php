<?php
if($allowed && $_GET['page'] == 'admin' && $_GET['id'] == 'database'){
    include_once 'admin/' . $_GET['id'] . '.php';
}
?>
<style>
    nav a{
        font-size: 25px;
        display: block;
    }
    nav a:not(:last-child){
        margin: 3px 0px;
        border-bottom: 2px solid yellow;
    }
    .content .content{
        box-shadow: none;
        border-radius: 0;
    }
    tr, td, a{
        word-break: break-all;
    }
</style>
<nav class="sideNav left" style="width: 25%">
    <a href="<?php echo $website->url; ?>news/">Grįžti į svetainę</a>
    <a href="<?php echo $website->url; ?>admin/news/">Veiksmai su įrašais</a>
    <a href="<?php echo $website->url; ?>admin/pages/">Veiksmai su puslapiais</a>
    <a href="<?php echo $website->url; ?>admin/menu/">Veiksmai su meniu</a>
    <a href="<?php echo $website->url; ?>admin/submenu/">Veiksmai su meniu punktais</a>
    <a href="<?php echo $website->url; ?>admin/roles/">Veiksmai su rolėmis</a>
    <a href="<?php echo $website->url; ?>admin/users/">Keisti vartotojų roles</a>
    <a href="<?php echo $website->url; ?>admin/settings/">Svetainės konfigūracija</a>
    <a href="<?php echo $website->url; ?>admin/logs/">Peržiūrėti logus</a>
    <a href="<?php echo $website->url; ?>admin/database/">Parsisiųsti duombazę</a>

</nav>
<div class="content" style="float: right; width: calc(75% - 25px)">
    <h2>Valdymo panelė</h2>
    <?php
        if(!isset($_GET['id'])){
            ?>
                <ul style="float: left; text-align: left">
                    <li>Grįžti į sveitainę - grįžta į pagrindinę sveitainę (išeina iš administravimo lango).</li>
                    <li>Veiksmai su įrašais - galite peržiūrėti, kurti, keisti, trinti įrašus ir jų įpatybes.</li>
                    <li>Veiksmai su puslapiais - galite peržiūrėti, kurti, keisti, trinti statinius puslapius ir jų įpatybes.</li>
                    <li>Veiksmai su meniu - galite peržiūrėti, kurti, keisti, trinti meniu punktus bei jų papunkčius.</li>
                    <li>Sveitainės konfigūracija - pakeisti tokius parametrus, kaip svetainės adresas ar pavadinimas</li>
                    <li>Peržiūrėti logus - galite peržiūrėti kokius veiksmus atliko kiekvienas vartotojas</li>
                </ul>
            <?php
        }elseif(file_exists('admin/'.$_GET['id'].'.php')){
            include 'admin/'.$_GET['id'].'.php';
        }else{
            include 'pages/404.php';
        }
    ?>
</div>
