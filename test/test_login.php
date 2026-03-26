<?php
require_once "../config.php";
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>로그인</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-btn {
            background: #4CAF50;
            color: white;
        }

        .login-btn:hover {
            background: #45a049;
        }

        .signup-btn {
            background: #2196F3;
            color: white;
        }

        .signup-btn:hover {
            background: #1976D2;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>로그인</h2>
        <form>
            <input type="text" name="username" placeholder="아이디" required>
            <input type="password" name="password" placeholder="비밀번호" required>
            <button type="button" class="login-btn" onclick="fetchData()">로그인</button>
        </form>

        <!-- 회원가입 버튼 -->
        <button class="signup-btn" onclick="goSignup()">회원가입</button>
    </div>

    <script>
        function goSignup() {
            // 회원가입 페이지로 이동
            window.location.href = "./test_reg.php";
        }

        function fetchData() {
            fetch('api/test_login_api.php',
                {
                    method: 'POST', 
                    body: JSON.stringify({
                        username: document.querySelector('input[name="username"]').value,
                        password: document.querySelector('input[name="password"]').value
                    })
                }
            )
                .then(res =>res.json())
                .then(res => {
                    console.log(res);
                    if(res.result == 'success') {
                        window.location.href = "./test_index.php";
                    } else {
                        alert(res.message);
                    }
                });
        }
    </script>

</body>

</html>