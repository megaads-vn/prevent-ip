<html>
<head>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
        form {
            border: 3px solid #f1f1f1;
        }

        /* Full-width inputs */
        input[type=text], input[type=email] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Set a style for all buttons */
        button {
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        /* Add a hover effect for buttons */
        button:hover {
            opacity: 0.8;
        }

        /* Extra style for the cancel button (red) */
        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            background-color: #f44336;
        }

        /* Center the avatar image inside this container */
        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }

        /* Avatar image */
        img.avatar {
            width: 40%;
            border-radius: 50%;
        }

        /* Add padding to containers */
        .container {
            padding: 16px;
        }

        /* The "Forgot password" text */
        span.psw {
            float: right;
            padding-top: 16px;
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }
            .cancelbtn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<form action="/prevent-ip" onsubmit="sendRequest(event)" method="post">
    <div class="imgcontainer">
        <h2>Bạn không có quyền truy cập trang web này, vui lòng điền thông tin vào form bên dưới để yêu cầu cấp quyền truy cập!</h2>
    </div>
    <div class="container">
        <label for="uname"><b>Họ Và Tên</b></label>
        <input type="text" placeholder="Nhập họ tên" name="name" required>

        <label for="uname"><b>Email</b></label>
        <input type="email" placeholder="Nhập email" name="email" required>
        <input type="hidden" name="ip" value="<?= $ip ?>">
        <input type="hidden" name="url" value="<?= $url ?>">
        <button type="submit">Gửi yêu cầu</button>
    </div>
</form>
<script type="text/javascript">
    function sendRequest () {
        event.preventDefault();
        var data = $('form').serialize();
        $.ajax({
            type: "POST",
            url: '/prevent-ip/send-request',
            data: {
                name: $('input[name="name"]').val(),
                email: $('input[name="email"]').val(),
                ip: $('input[name="ip"]').val(),
                url: $('input[name="url"]').val(),
            },
            success: function(response) {
            }
        });
        alert('Yêu cầu đã được gửi đi, vui lòng chờ hoặc liên hệ bộ phận kỹ thuật!');
    }
</script>
</body>
</html>

