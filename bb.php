<?php
$bb_height = $bb_height ?: '300px';
$bb_name = $bb_name ?: 'bb_input';
$bb_value = $bb_value ?: '';
$bb_width = $bb_width ?: '100%';
?>
<script src="<?php echo $website->url; ?>scripts/BBCode.js"></script>
<style>
    button[data-type='bbcode']{
        min-width: 40px;
        width: fit-content;
        height: 40px;
        background: transparent;
        font-size: 14px;
        margin: 0;
        background-color: white;
    }
</style>
<button data-type="bbcode"><b>B</b></button><button data-type="bbcode"><i>I</i></button><button data-type="bbcode"><u>U</u>
</button><button alt="kairinė lygiuotė" data-type="bbcode" style="background:url(<?php echo $website->url; ?>images/left.png); no-repeat;">&nbsp;
</button><button alt="centrinė lygiuotė"  data-type="bbcode" style="background:url(<?php echo $website->url; ?>images/center.png); no-repeat;">&nbsp;
</button><button alt="dešininė lygiuotė"  data-type="bbcode" style="background:url(<?php echo $website->url; ?>images/right.png); no-repeat;">&nbsp;
</button><button data-type="bbcode"><i>img</i></button><button data-type="bbcode"><u>url</u>
</button><button data-type="bbcode">html</button><button data-type="bbcode" style="color: red">spalva
</button><button data-type="bbcode">dydis</button>
<br /><textarea rows="1" id="textarea" name="<?php echo $bb_name; ?>" style="width: <?php echo $bb_width;?>;
        min-height: <?php echo $bb_height; ?>">
<?php echo $bb_value; ?>
</textarea><br />
Vaizdinys<br />
<div id="preview" style="min-width: <?php echo $bb_width; ?>; min-height: <?php echo $bb_height; ?>; border: 1px solid #777777;
        background-color: white; text-align: left"></div>
<script>
    let changed = false;
    let text = document.getElementById('textarea');
    let preview = document.getElementById('preview');
    text.addEventListener('keydown', (even) => changed = true);
    text.addEventListener('keyup', (even) => changed = true);
    function parse(){
        preview.innerHTML = BBCode.parse(text.value);
    }
    setInterval(()=>{
        if(changed) {
            parse();
            changed = false;
        }
    }, 60);

    function getInputSelection(el) {
        let start = 0, end = 0, normalizedValue, range,
            textInputRange, len, endRange;

        if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
            start = el.selectionStart;
            end = el.selectionEnd;
        } else {
            range = document.selection.createRange();

            if (range && range.parentElement() == el) {
                len = el.value.length;
                normalizedValue = el.value.replace(/\r\n/g, "\n");

                // Create a working TextRange that lives only in the input
                textInputRange = el.createTextRange();
                textInputRange.moveToBookmark(range.getBookmark());

                // Check if the start and end of the selection are at the very end
                // of the input, since moveStart/moveEnd doesn't return what we want
                // in those cases
                endRange = el.createTextRange();
                endRange.collapse(false);

                if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                    start = end = len;
                } else {
                    start = -textInputRange.moveStart("character", -len);
                    start += normalizedValue.slice(0, start).split("\n").length - 1;

                    if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                        end = len;
                    } else {
                        end = -textInputRange.moveEnd("character", -len);
                        end += normalizedValue.slice(0, end).split("\n").length - 1;
                    }
                }
            }
        }

        return {
            start: start,
            end: end
        };
    }
    function replaceSelectedText(el, text) {
        let sel = getInputSelection(el), val = el.value;
        el.value = val.slice(0, sel.start) + text + val.slice(sel.end);
        parse();
    }

    let buttons = [...document.getElementsByTagName('button')].filter(e => e.dataset.type == 'bbcode');
    let changes = ['[b]$1[/b]', '[i]$1[/i]', '[u]$1[/u]', '[left]$1[/left]', '[center]$1[/center]',
        '[right]$1[/right]', '[img]$1[/img]', '[url nuorodos_adresas]$1[/url]', '[html]$1[/html]',
        '[colour red]$1[/colour]', '[size 20]$1[/size]'];
    for(let i = 0; i < buttons.length; i++){
        buttons[i].addEventListener('click', (event)=>{
            let txt = window.getSelection().toString();
            let forRegex = txt.replace(/([\[\\\]])/g, '\\$1');
            let regexed = new RegExp('('+forRegex+')', 'u');
            let replaced = txt.replace(regexed, changes[i]);
            replaceSelectedText(text, replaced);
            text.focus();
            event.preventDefault();
        });
    }
    parse();
</script>