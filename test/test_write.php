<?php
    require_once "../config.php";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>글쓰기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .write-container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        textarea {
            height: 200px;
            resize: none;
        }

        /* 파일 업로드 영역 */
        .file-box {
            margin-top: 15px;
        }
        .file-box label {
            display: inline-block;
            padding: 10px 15px;
            background: #2196F3;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .file-box label:hover {
            background: #1976D2;
        }
        .file-box input[type="file"] {
            display: none;
        }
        .file-name {
            margin-left: 10px;
            font-size: 14px;
            color: #555;
        }

        .btn-group {
            margin-top: 20px;
            text-align: right;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .submit-btn {
            background: #4CAF50;
            color: white;
        }
        .submit-btn:hover {
            background: #45a049;
        }
        .cancel-btn {
            background: #aaa;
            color: white;
        }
        .cancel-btn:hover {
            background: #888;
        }
        .btn-close {
            display:inline-block;
        } 
        .btn-close:after {
            display: inline-block; content: "\00d7"; font-size:14px;
        }
    </style>
</head>
<body>

<div class="write-container">
    <h2>글쓰기</h2>

    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="제목" required>
        <input type="text" name="writer" placeholder="작성자" required>
        <textarea name="content" placeholder="내용을 입력하세요" required></textarea>

        <!-- 파일 업로드 -->
        <div class="file-box">
            <label for="file">파일 선택</label>
            <input type="file" id="file" name="file">
            <span class="file-name" id="file-name">선택된 파일 없음</span>
            <button type="button" class="btn-close" id="btn-close" style="display:none;" onclick="removeFile()">파일 삭제</button>
        </div>

        <div class="btn-group">
            <button type="button" class="cancel-btn" onclick="location.href='/'">취소</button>
            <button type="button" class="submit-btn" onclick="fetchData()">등록</button>
        </div>
    </form>
</div>

<script>
    var fileName = "";
    let fileInput = document.getElementById("file")
    let fileNameDisplay = document.getElementById("file-name");
    let btnCloseDisplay = document.getElementById("btn-close");

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if(file) {
            fileNameDisplay.textContent = file.name;
            btnCloseDisplay.style.display = 'inline-block';
        } else {
            fileNameDisplay.textContent = "선택된 파일 없음";
        }
    })

    // 파일 삭제 함수
    function removeFile() {
        fileNameDisplay.textContent = "선택된 파일 없음";
        btnCloseDisplay.style.display = 'none';
        fileInput.value = "";
    }

    function fetchData(){
        const fileInput = document.querySelector("input[name='file']");
        const file = fileInput.files[0];

        if(!file) {
            alert("파일이 선택되지 않았습니다.");
            return false;
        }

        const formData = new FormData();

        formData.append('file', file);
        formData.append('title', document.querySelector("input[name='title']").value);
        formData.append('writer', document.querySelector("input[name='writer']").value);
        formData.append('content', document.querySelector("textarea[name='content']").value);

        fetch('api/test_write_api.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);
            if(res.result == 'success') {
                window.location.href = './test_index.php';
            } else {
                alert(res.message);
            }
        })
    }
</script>

</body>
</html>