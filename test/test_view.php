<?php
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>게시글 보기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        .view-container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 10px;
        }

        .info {
            font-size: 14px;
            color: #777;
            margin-bottom: 20px;
        }

        .content {
            border-top: 1px solid #eee;
            padding-top: 15px;
            min-height: 150px;
            line-height: 1.6;
        }

        /* 첨부파일 영역 */
        .file-box {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .file-box a {
            text-decoration: none;
            color: #2196F3;
        }

        .file-box a:hover {
            text-decoration: underline;
        }

        .btn-group {
            margin-top: 25px;
            text-align: right;
        }

        button {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }

        .list-btn {
            background: #aaa;
            color: white;
        }

        .list-btn:hover {
            background: #888;
        }

        .edit-btn {
            background: #4CAF50;
            color: white;
        }

        .edit-btn:hover {
            background: #45a049;
        }

        .delete-btn {
            background: #f44336;
            color: white;
        }

        .delete-btn:hover {
            background: #d32f2f;
        }
    </style>
</head>

<body>

    <div class="view-container" id="view-container">
    </div>

    <script>
        let post_seq = 0;

        window.onload = function () {
            // 게시글 번호를 파라미터 값으로 가져오기
            const urlParams = new URL(location.href).searchParams;
            post_seq = urlParams.get('post_seq');

            fetch('./api/test_view_api.php', {
                method: 'POST',
                body: post_seq
            }).then(res => res.json())
                .then(res => {
                    const data = res.data;
                    if (res.result == 'success') {
                        let file_path = data.file_path.split('test/')[1];
                        let file_name = data.date + '_' + data.file_name;

                        document.getElementById('view-container').innerHTML =
                            `
                    <h2>${data.title}</h2>

                    <div class="info">
                        작성자: ${data.writer} | 작성일: ${data.date}
                    </div>

                    <div class="content">
                        ${data.content}
                    </div>

                    <!-- 첨부파일 -->
                    <div class="file-box">
                        📎 첨부파일:
                        <a href="${file_path}" download="${data.file_name}">${file_name}</a>
                    </div>

                    <div class="btn-group">
                        <button class="list-btn" onclick="location.href='/'">목록</button>
                        <button class="edit-btn" onclick="location.href='./test_edit.php?post_seq=${data.FK_post_seq}'">수정</button>
                        <button class="delete-btn" onclick="deleteData()">삭제</button>
                    </div>
                `;
                    } else {
                        alert(res.message);
                    }
                })
        }

        // 게시글 삭제 함수
        function deleteData() {
            fetch('./api/test_delete_api.php', {
                method: 'POST',
                body: post_seq
            }).then(res => res.json())
                .then(res => {
                    console.log(res);
                    if (res.result = 'success') {
                        alert("게시글이 삭제되었습니다.");
                        window.location.href = './test_index.php';
                    } else {
                        alert(res.message);
                    }
                })
        }
    </script>

</body>

</html>