'use strict';
class BBCode{
    static get pattern(){return /\[(\w+)\s?([\d\p{L}/?=.:%_#]+)?\](.+?)\[\/\1\]/sug;}

    static get converts(){ return [
        ['b', '<$1>$3</$1>'],
        ['i', '<$1>$3</$1>'],
        ['u', '<$1>$3</$1>'],
        ['img', '<$1 style="width: $2" src="$3" />'],
        ['url', '<a href="$2">$3</a>'],
        ['left', '<span class="left">$3</span>'],
        ['right', '<span class="right">$3</span>'],
        ['center', '<div class="center">$3</div>'],
        ['html', '$3'],
        ['colour', '<span style="color: $2">$3</span>'],
        ['size', '<span style="font-size: $2px">$3</span>']
    ];}

    static parse(str){
        str = this.escapeHtml(str);
        let obj = {value: str};
        this.realParse(str, obj);
        return obj.value.replace(/\n\n/g, '<br />');
    }

    static realParse(str, obj){
        let pattern = this.pattern;
        let out = [];
        var temp;
        while(temp = pattern.exec(str)){
            out.push(temp);
        }
        if(out.length == 0){
            return;
        }
        for(let i = 0; i < out.length; i++){
            if(out[i][1] != 'html'){
                this.realParse(out[i][3], obj);
            }
        }
        out = [];
        while(temp = pattern.exec(obj.value)){
            out.push(temp);
        }
        if(out.length == 0){
            return;
        }
        for(let i = 0; i < out.length; i++){
            let rule = -1;
            for(let j = 0; j < this.converts.length; j++){
                if(out[i][1] == this.converts[j][0]){
                    rule = j;
                    break;
                }
            }
            if(rule != -1){
                let replaced = out[i][0].replace(this.pattern, this.converts[rule][1])
                if(out[i][1] == 'html'){
                    replaced = this.decodeHtml(replaced);
                }
                obj.value = obj.value.replace(out[i][0], replaced);
            }
        }
    }

    // https://stackoverflow.com/questions/1787322/htmlspecialchars-equivalent-in-javascript
    static escapeHtml(text) {
        let map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    static decodeHtml(text){
        let map =
            {
                '&amp;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&quot;': '"',
                '&#039;': "'"
            };
        return text.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
    }
}