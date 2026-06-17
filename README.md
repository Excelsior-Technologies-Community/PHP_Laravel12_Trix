# PHP_Laravel12_Trix

## Introduction

**PHP_Laravel12_Trix** is a Laravel 12 demonstration project that integrates the **Trix WYSIWYG editor** to enable rich text content creation within a Laravel application.

The project shows how developers can easily implement a modern rich text editor, store formatted content in the database, and display it in Blade views. It also demonstrates how Trix manages attachments such as images and files automatically through dedicated database tables.

This project is designed for developers who want to learn how to integrate **rich text editing functionality in Laravel 12 applications** using the **te7a-houdini/laravel-trix package**.

---

## Project Overview

This project demonstrates the complete workflow of integrating the **Trix rich text editor** into a Laravel 12 application.

The application allows users to:

- Create blog posts with rich formatted content
- Store rich text data in a structured database format
- Automatically handle file and image attachments
- Render rich text content safely inside Blade templates

The project uses the **Laravel-Trix package**, which simplifies the integration of the Trix editor by providing Blade directives, database migrations, and model traits.

Key components implemented in this project include:

- Laravel 12 application setup
- Trix editor integration
- Post model with rich text support
- Controller logic for creating and storing posts
- Blade view for rich text editing
- Database tables for posts, rich text content, and attachments

---

## Step 1: Create Laravel 12 Project

Open terminal and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_Trix "12.*"
cd PHP_Laravel12_Trix
```

---

## Step 2: Configure Database

In .env file, update database credentials:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_trix_db
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL:

```bash
CREATE DATABASE laravel12_trix_db;
```

Or 

Run Migration Command:

```bash
php artisan migrate
```

---

## Step 3: Install Laravel-Trix Package

```bash
composer require te7a-houdini/laravel-trix
```

Publish config and migrations:

```bash
php artisan vendor:publish --provider="Te7aHoudini\LaravelTrix\LaravelTrixServiceProvider"
```

Run migrations:

```bash
php artisan migrate
```

This creates the required tables for storing rich text content and attachments.

---

## Step 4: Create Post Model & Migration

```bash
php artisan make:model Post -m
```

### Migration Table

Edit the migration file database/migrations/xxxx_create_posts_table.php:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

---

### Model 

app/Models/Post.php:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class Post extends Model
{
    use HasTrixRichText;

    protected $guarded = [];
}
```

---

## Step 5: Create PostController

```bash
php artisan make:controller PostController
```

Add methods in app/Http/Controllers/PostController.php:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Post::create($request->all());

        return redirect()->route('posts.create')
            ->with('success', 'Post created successfully');
    }
}
```

---

## Step 6: Add Routes

In routes/web.php:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/post/create',[PostController::class,'create'])->name('posts.create');
Route::post('/post/store',[PostController::class,'store'])->name('posts.store');

Route::get('/', function () {
    return view('welcome');
});
```

---

## Step 7: Create Blade File

resources/views/posts/create.blade.php:

```html
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
```

---

## Step 8: Run the Application

```bash
php artisan serve
```

Open your browser and visit:

```bash
http://127.0.0.1:8000/post/create
```

This page allows users to create a post using the Trix rich text editor.

---

## Output

<img width="1918" height="1030" alt="Screenshot 2026-03-06 125413" src="https://github.com/user-attachments/assets/d05082ed-6756-4aee-b243-a4615be74436" />

<img width="1919" height="1031" alt="Screenshot 2026-03-06 125424" src="https://github.com/user-attachments/assets/bba3eac3-7ebc-49a6-ba52-526fe65c3d0d" />

---

## Project Structure

```
PHP_Laravel12_Trix
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── PostController.php
│   │
│   └── Models
│       └── Post.php
│
├── config
│   └── laravel-trix.php
│
├── database
│   ├── migrations
│   │   ├── xxxx_create_posts_table.php
│   │   └── 2026_03_06_045322_create_trix_rich_texts_table.php
│
├── resources
│   └── views
│       └── posts
│           ├── create.blade.php
│          
│
├── routes
│   └── web.php
│
├── .env
│
├── composer.json
│
└── README.md
```

---

Your PHP_Laravel12_Trix Project is now ready!
