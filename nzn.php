<?php
include 'BBCode.php';
$string = 'Nieko
Labas[b][i]dsaQ[/i][/b]
[i]dsaW[/i] [b]hello[/b]
[img]https://lačbas.jpg[/img]
[url ane]labas[/url]
[url https://sdfsafč][img]lul[/img][b]aa[/b][/url]';
echo BBCode::parse($string);