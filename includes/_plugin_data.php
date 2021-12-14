<div class="plugin-data">
    <div class="waiting_section">
        <div class="card">
            <span id="countdown" style="font-weight: bold;"></span>
        </div>
        <p>Wait for your code</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%;">
                <div class="progress-text"></div>
            </div>
        </div>
        <p class="love-loding">love loading</p>
    </div>
    <div class="your_code">
        <p>Your Code</p>
        <p class="code" id="code"></p>
        <p id="msg"></p>
        <form>
            <input name="MyUrlName" type="text" class="add_name" id="MyUrlName" placeholder="Enter Code">
            <input type="button" name="submit" id="MyUrlsubmit" value="Submit" class="submit">
        </form>
    </div>
    
    <div class="success-page">
    <div class="svg">
    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            width="78.369px" height="78.369px" viewBox="0 0 78.369 78.369" style="enable-background:new 0 0 78.369 78.369;"
            xml:space="preserve">
        <g>
            <path d="M78.049,19.015L29.458,67.606c-0.428,0.428-1.121,0.428-1.548,0L0.32,40.015c-0.427-0.426-0.427-1.119,0-1.547l6.704-6.704
                c0.428-0.427,1.121-0.427,1.548,0l20.113,20.112l41.113-41.113c0.429-0.427,1.12-0.427,1.548,0l6.703,6.704
                C78.477,17.894,78.477,18.586,78.049,19.015z"/>
        </g>
        </svg>
    </div>
    <p class="success">success</p>
    <p class="para">Everything Working Fine</p>
    <?php
    global $post;
    if(is_single( )){ ?>
        <a class="continue" href="<?php echo get_post_meta($post->ID,  'post_reading_time', true); ?>">Contunie</a>
    <?php }else{ ?>
        <a class="continue" href="<?php bloginfo( 'url' ); ?>">Contunie</a>
   <?php } ?>

    </div>

</div>

<?php
$timer = get_option('map_title');
if(!$timer){
    $timer = '60';
}
$load_time = $timer * 1000;

wp_register_script( 'jQuery', 'https://code.jquery.com/jquery-3.6.0.min.js', null, null, true );
wp_enqueue_script('jQuery');

?>

<script>
    var timeInSecs;
    var ticker;

    function startTimer(secs) {
        timeInSecs = parseInt(secs);
        ticker = setInterval("tick()", 1000);
    }

    function tick() {
        var secs = timeInSecs;
        if (secs > 0) {
            timeInSecs--;
        } else {
            clearInterval(ticker);
            startTimer(<?php echo $timer; ?>);
        }

        var mins = Math.floor(secs / 60);
        secs %= 60;
        var pretty = ((mins < 10) ? "0" : "") + mins + ":" + ((secs < 10) ? "0" : "") + secs;

        document.getElementById("countdown").innerHTML = pretty;
    }

    startTimer(<?php echo $timer; ?>);


    let percent = 0
    let timer = setInterval(function () {
        if(jQuery("#countdown").text()){
            percent += 1
        jQuery('.progress-text').text(`${percent} %`)
        jQuery('.bar').css('width', `${percent}%`)

        if (percent >= 100) {
            clearInterval(timer)
        }
        }
    }, <?php echo $load_time; ?> / 100)

    // random number generator
    let code = Math.floor((Math.random() * 9999999999) + 1);
    document.getElementById("code").innerHTML = code;

    // display code section after certain time
    window.onload = function () {
        setTimeout(function () {
            jQuery('.your_code').css('display', 'block');
            jQuery('.waiting_section').css('display', 'none');
        }, <?php echo $timer; ?> * 1000);
    }
</script>

<script>
    jQuery(document).ready(function () {
        jQuery("#MyUrlsubmit").click(function () {
            var name = jQuery("#MyUrlName").val();
            if (name == code) {
                jQuery.ajax({
                    type: 'POST',
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data: {
                        action: "wesoftpress_savedata",
                        MyUrlName: name
                    },

                    success: function (data) {
                        // document.getElementById("msg").innerHTML = `<div class="alert alert-success" role="alert">Success</div>`;
                        jQuery('.your_code').css({'display': 'none'});
                        jQuery('.success-page').css({'display': 'block'});
                    }
                });
            } else {
                document.getElementById("msg").innerHTML = `<div class="alert alert-danger" role="alert">Error !</div>`;
            }
        });
    });
</script>