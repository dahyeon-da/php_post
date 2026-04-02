<?php
require_once "../config.php";
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            margin-bottom: 30px;
        }

        h2 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr, td {
            border-top: 0.7px solid #c2c2c2;
            border-bottom: 0.7px solid #c2c2c2;
            padding: 12px;
            text-align: left;
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

        .comment-block {
            display: flex;
            justify-content: space-between;
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

        .comment-btn {
            background: #6dc5ff;
            color: white;
        }

        .delete-btn:hover {
            background: #d32f2f;
        }

        .star-label {
            font-size: 27px;
            color: #f44336;
            display: inline-block;
            content: '☆';
            padding-top: 9 px;
        }

        .star-checkbox {
            display: none;
        }

        .title {
            display: flex;
            align-items: center;
        }

        .none-comment {
            display: none;
        }
        .comment-date {
            font-size: 0.9em;
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="view-container" id="view-container">
    </div>

    <div class="view-container" id="view-container">
        <!-- 댓글이 있을 경우에는 none 처리 -->
        <span class="none-comment">댓글 없음</span>

        <table class="comment">
        </table>
    </div>

    <script>
        let post_seq = 0;

        window.onload = function() {
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
                    <div class="title">
                        <h2>${data.title}</h2>
                        <input type="checkbox" class="star-checkbox" id = "star">
                        <label for="star" class="star-label">☆</label>
                    </div>

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
                        <button class="list-btn" onclick="location.href='/leedh/test/test_index.php'">목록</button>
                        <button class="edit-btn" onclick="location.href='./test_edit.php?post_seq=${data.FK_post_seq}'">수정</button>
                        <button class="delete-btn" onclick="deleteData()">삭제</button>
                    </div>
                `;
                        commentData(post_seq);
                        onOffStar(data.star_on_off);
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

        // 댓글 불러오기 및 출력 함수
        function commentData(post_seq) {
            fetch('./api/test_comment_api.php', {
                method: 'POST',
                body:post_seq
            }).then(res => res.json())
            .then(res => {
                const comment = document.querySelector('.comment');

                if(res.result == 'success') {
                    const answer = res.data;
                    let html = ``;
                    
                    if(answer.length == 0) {
                        html = `<span>댓글이 없습니다.</span>`;
                    }
                    answer.forEach(data => {
                        html += `<tr>
                            <td>
                                <div class="comment-block">
                                    <div>
                                        <span class="comment-writer">${data.username}</span>
                                        <span><br>${data.comment_content}</span>
                                    </div>
                                    <span class="comment-date">${data.comment_date}</span>
                                </div>
                            </td>
                        </tr>`;
                    });
                    console.log(html);
                    comment.innerHTML = html;
                }
            })
        }

        // 기존 즐겨찾기 on_off 함수로 즐겨찾기의 텍스트를 지정해주는 함수
        function onOffStar(star) {
            const $label = $('.star-checkbox').siblings('.star-label');

            if (star == 1) {
                $label.html("★").css("color", "#f44336;");
            } else {
                $label.html("☆").css("color", "#f44336;");
            }
        }
    </script>

</body>

</html>