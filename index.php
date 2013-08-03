<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.gracefulWebSocket.js"></script>
        <title>Página</title>
    </head>
    <body>
        <script type="text/javascript">
            var ws = $.gracefulWebSocket('ws://33.33.33.10:8080');
            ws.onmessage = function (msg){
                var server = JSON.parse(msg.data);
                $('#message').prepend("<p>"+server.texto+"</p>");
                $('#qtdusers').text(server.qtdlogin);
            };
            
            $('document').ready(function() {
                $('#send').click(function() {
                    ws.send($("#txtin").val());
                    $('#txtin').val('');
                });
            });    
        </script>
        <div id="message"></div>
        <textarea id="txtin"></textarea><br />
        <button id="send">enviar</button>
        
        <h1>Conectados no servidor: <span id="qtdusers">0</span></h1> 
    </body>
</html>
