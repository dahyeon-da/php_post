<?php
    require_once "../config.php";
    session_start();
    
    if(!$_SESSION['username']){
        echo "<script>window.location.href = './test_login.php';</script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>간단 게시판</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .board-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #f0f0f0;
        }
        .write-btn {
            float: right;
            margin-top: 10px;
            padding: 8px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .write-btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="board-container">
    <h2>게시판</h2>

    <button class="write-btn" onclick="goWrite()">글쓰기</button>

    <table>
        <thead>
            <tr>
                <th>번호</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
            </tr>
        </thead>
        <tbody id="boardList">
            
        </tbody>
    </table>
</div>

<script>
    function goWrite() {
        // 글쓰기 페이지 이동
        window.location.href = "./test_write.php";
    }

    window.onload = function() {
        fetch('./api/test_index_api.php', {
            method: 'GET'
        }).then(res => res.json())
        .then(res => {
            console.log(res);
            const data = res.data;
            const displayArea = document.getElementById('boardList');
            let html = '';
            data.forEach((post, index) => {
                
            html +=   `
            <tr>
                <td>${index + 1}</td>
                <td><button type="button" onclick="goDetailView(${post.post_seq})" style="width: 100%; height: 100%; border: none; background-color: #ffffff; cursor: pointer">${post.title}</td>
                <td>${post.writer}</td>
                <td>${post.date}</td>
            </tr>
            `
            });

            displayArea.innerHTML = html;
        })
    }

    function goDetailView(post_seq) {
        location.href = './test_view.php?post_seq=' + post_seq;
    }
</script>

</body>
</html>