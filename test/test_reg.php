<?php
require_once "../config.php";
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 320px;
        }

        .signup-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .signup-box input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .signup-box button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .signup-btn {
            background: #2196F3;
            color: white;
        }

        .signup-btn:hover {
            background: #1976D2;
        }

        .back-btn {
            background: #aaa;
            color: white;
        }

        .back-btn:hover {
            background: #888;
        }
    </style>
</head>

<body>

    <div class="signup-box">
        <h2>회원가입</h2>
        <form onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="아이디" required>
            <input type="password" name="password" placeholder="비밀번호" required>
            <input type="password" name="confirm_password" placeholder="비밀번호 확인" required>
            <input type="email" name="email" placeholder="이메일" required>

            <button type="button" class="signup-btn"" onclick="fetchData()">회원가입</button>
        </form>

        <button class="back-btn" onclick="goLogin()">로그인으로 돌아가기</button>
    </div>

    <script>
        function goLogin() {
            window.location.href = "./test_login.php";
        }

        function validateForm() {
            const pw = document.querySelector('input[name="password"]').value;
            const cpw = document.querySelector('input[name="confirm_password"]').value;

            if (pw !== cpw) {
                alert("비밀번호가 일치하지 않습니다.");
                return false;
            }

            return false;
        }

        function fetchData() {
            

            console.log('fetchData 실행');
            fetch('api/test_reg_api.php', {
                method: 'POST',
                body: JSON.stringify({
                    username: document.querySelector('input[name="username"]').value,
                    password: document.querySelector('input[name="password"]').value,
                    email: document.querySelector('input[name ="email"]').value
                })
            })
            .then(res => res.json())
            .then(res => {
                console.log(res);
                if(res.result == 'success') {
                    window.location.href = "./test_index.php";
                } else {
                    alert(res.message);
                }
            })
        }
    </script>

</body>

</html>