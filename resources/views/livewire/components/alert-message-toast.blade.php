<h2>Toast</h2>
<p>Click on the button to show the Toast. It will disappear after 5 seconds.</p>

<button type="button" onclick="launch_toast()">Show Toast</button>

<div id="toast"><div id="img">Icon</div><div id="desc">A notification message..</div></div>

<script>
    function launch_toast() {
        var x = document.getElementById("toast")
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
    }
</script>
