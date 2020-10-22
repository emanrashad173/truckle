<!doctype html>
<html>
<head>
    <title>Socket.IO chat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font: 13px Helvetica, Arial; }
        form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
        form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
        form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
        #messages { list-style-type: none; margin: 0; padding: 0; }
        #messages li { padding: 5px 10px; }
        #messages li:nth-child(odd) { background: #eee; }
    </style>
</head>
<body>
<ul id="messages"></ul>
<form action="">
    <input id="m" autocomplete="off" /><button>Send</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
<script>
    var socket = io('');
    var s=JSON.stringify({
        "channel": "chat message",
        "data": {
            "id": 5,
            "name": "waza",
            "user_id": 7,
            "user_photo": "",
            "voice": "",
            "message": "لا  اله الا أنت سبحانك أني كنت ن الظالمين",
            "created_at": "2020-4-19"
        }
    });
    socket.emit('chat message', s);

    socket.on('chat message',function (s) {
        console.log(s);

    })
</script>
</body>
</html>
