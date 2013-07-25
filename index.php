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
            var ws = $.gracefulWebSocket('ws://localhost:8080');
            ws.onmessage = function (msg){
                var server = eval('('+msg.data+')');
                $('#message').prepend("<p>"+server.texto+"</p>");
                $('#qtdusers').text(server.qtdlogin);
            };
            
            $('document').ready(function() {
                $('#send').click(function() {
                    ws.send($("#txtin").val());
                });
            });    
        </script>
        <div id="message"></div>
        <textarea id="txtin"></textarea>
        <button id="send">enviar</button>
        
        <h1>Conectados no servidor: <span id="qtdusers">0</span></h1> 
    </body>
</html>
