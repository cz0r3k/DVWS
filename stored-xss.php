<?php
$page_data = <<<EOT
<div class="page-header">
    <h1>Stored XSS</h1>
</div>
<div class="row">
    <div class="col-md-12">
        <p>
            Enter your name:
            <div class="form-group">
                <input type="text" class="form-control" id="name" name="name" placeholder="Your name">
            </div>
            Enter your comment:
            <div class="form-group">
                <textarea class="form-control" id="comment" name="comment" placeholder="Your comment"></textarea>
            </div>
            <button type="submit" class="btn btn-success" id="send">Post Comment</button>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p id="result">
        </p>
    </div>
</div>
EOT;
$wsUrl = sprintf('ws://%s:8080/%s', getenv('DVWS_HOST') ?? 'dvws.local', 'post-comments');
$wsUrl2 = sprintf('ws://%s:8080/%s', getenv('DVWS_HOST') ?? 'dvws.local', 'show-comments');
$page_script= <<<EOT
$(document).ready(function(){
//Open a WS server connection
var wsUri = "$wsUrl";
websocket = new WebSocket(wsUri);

//Connected to WS server
websocket.onopen = function(ev)
{
    console.log('Connected to server');
}

//Close WS server connection
websocket.onclose = function(ev)
{
    console.log('Disconnected from server');
};

//Message received from WS server
websocket.onmessage = function(ev)
{
    console.log('Message: '+ev.data);
    document.getElementById("result").innerHTML = document.getElementById("result").innerHTML + ev.data;
};

//Error
websocket.onerror = function(ev)
{
    console.log('Error: '+ev.data);
};

//Send value to WS
$('#send').click(function()
{
    var name_field_value = document.getElementById('name').value;
    var comment_field_value = document.getElementById('comment').value;
    var field_value = '{"name":"'+name_field_value+'","comment":"'+comment_field_value+'"}';
    console.log(field_value);
    websocket.send(field_value);
});

//Show comments
//Open a WS server connection
var wsUri2 = "$wsUrl2";
websocket2 = new WebSocket(wsUri2);

//Connected to WS server
websocket2.onopen = function(ev)
{
    console.log('Connected to server: show-comments');
}

//Close WS server connection
websocket2.onclose = function(ev)
{
    console.log('Disconnected from server: show-comments');
};

//Message received from WS server
websocket2.onmessage = function(ev)
{
    console.log('Message: show-comments : '+ev.data);
    document.getElementById("result").innerHTML = ev.data;
};

//Error
websocket2.onerror = function(ev)
{
    console.log('Error: show-comments : '+ev.data);
};

setTimeout(function() { websocket2.send("showComments"); }, 2000);

});
EOT;
?>

<?php require_once('includes/template.php'); ?>
