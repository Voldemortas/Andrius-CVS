<?php
class BBCode{
    const pattern = '|\[(\w+)\s?([\d\p{L}/?=.:\%\_#]+)?](.+?)\[\/\1\]|su';

    const converts = [
        ['b', '<$1>$3</$1>'],
        ['i', '<$1>$3</$1>'],
        ['u', '<$1>$3</$1>'],
        ['img', '<$1 style="width: $2" src="$3" alt="čia turėtų būti paveikslėlis" />'],
        ['url', '<a href="$2">$3</a>'],
        ['left', '<span class="left">$3</span>'],
        ['right', '<span class="right">$3</span>'],
        ['center', '<div class="center">$3</div>'],
        ['html', '$3'],
        ['colour', '<span style="color: $2">$3</span>'],
        ['size', '<span style="font-size: $2px">$3</span>']
    ];

    /**
     * @param string $string
     * @return string
     */
    public static function parse($string){
        $string = htmlspecialchars($string);
        self::realParse($string, $string);
        return str_replace("\r\n\r\n", "<br />", $string);
    }

    /**
     * @param string $str
     * @param string $string
     */
    private static function realParse($str, &$string){
        preg_match_all(static::pattern, $str, $out);
        for($i = 0; $i < count($out[3]); $i++){
            if($out[1][$i] != 'html') {
                self::realParse($out[3][$i], $string);
            }
        }
        preg_match_all(static::pattern, $string, $out);
        for($i = 0; $i < count($out[0]); $i++){
            $rule = -1;
            for($j = 0; $j < count(static::converts); $j++){
                if($out[1][$i] == static::converts[$j][0]){
                    $rule = $j;
                    break;
                }
            }
            if($rule != -1){
                $replaced = preg_replace(static::pattern, static::converts[$rule][1], $out[0][$i]);
                if($out[1][$i] == 'html'){
                    $replaced = htmlspecialchars_decode($replaced);
                }
                $string = str_replace($out[0][$i], $replaced, $string);
            }
        }
    }
}