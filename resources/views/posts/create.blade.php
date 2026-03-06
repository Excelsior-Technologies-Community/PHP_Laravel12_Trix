<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laravel Trix Editor</title>

@trixassets

<style>

body{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f1f5f9;
    margin:0;
    padding:0;
}

.container{
    max-width:900px;
    margin:auto;
    padding:40px 20px;
}

.card{
    background:#ffffff;
    padding:35px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.page-title{
    font-size:28px;
    font-weight:600;
    margin-bottom:25px;
    color:#333;
}

label{
    font-weight:600;
    display:block;
    margin-bottom:8px;
    color:#444;
}

input[type="text"]{
    width:100%;
    padding:12px;
    border:1px solid #dcdcdc;
    border-radius:6px;
    font-size:15px;
    transition:0.3s;
}

input[type="text"]:focus{
    border-color:#3490dc;
    outline:none;
}

.trix-editor{
    min-height:200px;
}

.btn{
    margin-top:20px;
    padding:12px 20px;
    background:#3490dc;
    color:white;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#2779bd;
}

.alert{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:6px;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2 class="page-title">Create New Post</h2>

@if(session('success'))
<div class="alert">
{{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('posts.store') }}">

@csrf

<label>Post Title</label>

<input type="text" name="title" placeholder="Enter post title">

<br><br>

<label>Post Content</label>

@trix(\App\Models\Post::class, 'content')

<button type="submit" class="btn">
Save Post
</button>

</form>

</div>

</div>

</body>
</html>