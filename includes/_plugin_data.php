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
    </div>
    <div class="your_code">
        <p>Your Code</p>
        <p class="code" id="code"></p>
        <form>
            <input name="MyUrlName" type="text" class="add_name" id="MyUrlName" placeholder="Enter Code">
            <input type="button" name="submit" id="MyUrlsubmit" value="Submit" class="submit">
        </form>
    </div>
</div>

<?php  
$timer = get_option('map_title');
if(!$timer){
    $timer = '60';
}
$load_time = $timer * 1000;
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
        percent += 1
        jQuery('.progress-text').text(`${percent} %`)
        jQuery('.bar').css('width', `${percent}%`)

        if (percent >= 100) {
            clearInterval(timer)
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
                        action: "savedata",
                        MyUrlName: name
                    },

                    success: function (data) {
                        alert('success');
                    }
                });
            } else {
                alert('Not match');
            }
        });
    });
</script>