<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intento con css3</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
<div class="container">
    <div class="card">
        <div>
            <h2 class="title hover-paint">Find Twitter ID</h2>
            <input class="input-card" placeholder="Usuario" type="text" name="" id="username">
            <button type="submit" onclick="get()" class="btn" id="btn">Get Twitter ID</button>
        </div>
        <div class="details" style="display: none;">
            <div class="detail money">
                <span class="response" id="response">12345678912345</span>
            </div>
            <button onclick="copyToClipboard('response')">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
            </button>
        </div>
        <div class="message" id="message">

        </div>
        <div>
            <p class="description">This tool called "Find Twitter ID" provides an easy way for you to get a Twitter profile's numeric ID. You can also learn when a Twitter account was created..</p>
        </div>
        <div class="user">
            <div>Developed by <a target="_blank" href="https://github.com/F4brizio"><span class="hover-paint">@F4brizio</span></a></div>
            <br>
            <div>Designed by <a target="_blank" href="https://github.com/AbelLLontop"><span class="hover-paint">@AbelLLontop</span></a></div>
        </div>

    </div>

</div>

<script>
var disabled = false;
function get(){
    if (disabled === false){
        $('#btn').html("Loading...");
        disabled = true;
        $.post( "/get", { user: document.getElementById("username").value})
        .done(function( data ) {
            $('#btn').html("Get Twitter ID");
            if (data.status == "ok"){
                $('.message').hide();
                $('.details').show();
                document.getElementById('response').innerHTML = data.response;
            }else{
                $('.message').show();
                $('.details').hide();
                document.getElementById('message').innerHTML = data.message;
            }
            disabled = false;
        }).fail(function(xhr, textStatus, errorThrown) {
            console.log(xhr,textStatus,errorThrown);
        });
    }
}
function copyToClipboard(elementId) {
    var aux = document.createElement("input");
    aux.setAttribute("value", document.getElementById(elementId).innerHTML);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
}


</script>

</body>

</html>