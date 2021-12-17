<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusher-PHP</title>

    <?php if (isset($_SESSION['username'])) : ?>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <?php endif; ?>
    <link rel="stylesheet" href="views/style.css">
</head>

<body>
    <h1>Simple Pusher PHP</h1>
    <div class="scene scene--card">
        <div class="card">
            <div id="chat">
                <span>Chat</span>

                <?php if (!isset($_SESSION['username'])) : ?>
                    <div>
                        <form action="post" method="POST">
                            Enter name:
                            <input type="text" name="username" id="username">
                            <input type="submit" name="submitUsername" value="Submit">
                        </form>
                    </div>
                <?php else : ?>
                    <div id="chat-div">

                    </div>

                    <textarea type="text" name="message" id="message" placeholder="Enter message"></textarea>
                    <input type="submit" onclick="sendMessage()" value="Send" name="submitMessage" id="submitMessage">

                <?php endif; ?>
            </div>

            <div id="video">
                <span>Video</span>
            </div>
        </div>
    </div>
</body>
<?php if (isset($_SESSION['username'])) : ?>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('YOUR_APP_KEY', {
            cluster: 'YOUR_APP_CLUSTER'
        });

        var socketId = null;
        pusher.connection.bind("connected", () => {
            socketId = pusher.connection.socket_id;
        });

        var channel = pusher.subscribe('presence-my-channel');
        channel.bind('my-event', function(data) {
            appendMessage(data);
            JSON.stringify(data.user_id)
        });

        function sendMessage() {
            var inp_msg = document.getElementById('message');
            var btn_submit = document.getElementById('submitMessage');

            var formData = new FormData();
            formData.append(inp_msg.name, inp_msg.value);
            formData.append(btn_submit.name, btn_submit.value);

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    //alert(xmlHttp.responseText);
                }
            }
            xmlHttp.open("post", "/post");
            xmlHttp.send(formData);
        }

        function appendMessage(data) {
            let div = document.createElement('div');
            let span_msg = div.createElement('span');
            let p = div.createElement('p');
            let span_time = div.createElement('span');

            if (data.username == <?php echo $_SESSION['username']; ?>) {
                div.className = "container";
                span_msg.className = "username-right";
                p.innerHTML = data.message;
                span_time.className = "time-left";
            } else {
                div.className = "container darker";
                span_msg.className = "username";
                p.innerHTML = data.message;
                span_time.className = "time-right";
            }

            let div_chat = document.getElementById('div-chat');
            div_chat.appendChild(div);
        }
    </script>
<?php endif; ?>

</html>