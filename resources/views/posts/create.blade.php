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

input[type="file"]{
    width:100%;
    padding:10px;
    border:1px solid #dcdcdc;
    border-radius:6px;
    font-size:14px;
    background:#fff;
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

.draft-banner{
    background:#fff3cd;
    color:#856404;
    padding:12px 16px;
    border-radius:6px;
    margin-bottom:20px;
    display:none;
    align-items:center;
    justify-content:space-between;
    gap:12px;
}

.draft-banner button{
    margin-top:0;
    padding:8px 14px;
    font-size:13px;
}

.btn-discard{
    background:#dc3545;
}

.btn-discard:hover{
    background:#c82333;
}

.counts-bar{
    display:flex;
    gap:20px;
    margin-top:10px;
    font-size:13px;
    color:#666;
}

.draft-status{
    font-size:12px;
    color:#888;
    margin-top:8px;
}

.image-preview{
    display:none;
    max-width:200px;
    margin-top:10px;
    border-radius:6px;
    border:1px solid #dcdcdc;
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

<div id="draftBanner" class="draft-banner">
<span>Draft found from <span id="draftTime"></span></span>
<div>
<button type="button" id="restoreDraftBtn" class="btn">Restore</button>
<button type="button" id="discardDraftBtn" class="btn btn-discard">Discard</button>
</div>
</div>

<form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">

@csrf

<label>Post Title</label>

<input type="text" name="title"  value="{{ old('title') }}" placeholder="Enter post title">

<br><br>

<label>Featured Image</label>

<input type="file" name="featured_image" id="featuredImageInput" accept="image/*">

<img id="imagePreview" class="image-preview" src="" alt="Preview">

<br><br>

<label>Post Content</label>

@trix(\App\Models\Post::class, 'content', ['disk' => 'public'])

<div class="counts-bar">
<span><span id="wordCount">0</span> words</span>
<span><span id="charCount">0</span> characters</span>
<span id="readingTime">0 min read</span>
</div>

<div id="draftStatus" class="draft-status"></div>

<button type="submit" class="btn">
Save Post
</button>

</form>

</div>

</div>

<script>

const DRAFT_KEY = 'post_create_draft';
const titleInput = document.querySelector('input[name="title"]');
const trixEditor = document.querySelector('trix-editor');
const wordCountEl = document.getElementById('wordCount');
const charCountEl = document.getElementById('charCount');
const readingTimeEl = document.getElementById('readingTime');
const draftBanner = document.getElementById('draftBanner');
const draftTimeEl = document.getElementById('draftTime');
const draftStatusEl = document.getElementById('draftStatus');
const imageInput = document.getElementById('featuredImageInput');
const imagePreview = document.getElementById('imagePreview');

let saveTimeout;

function updateCounts() {
    const text = trixEditor.innerText.trim();
    const words = text.length ? text.split(/\s+/).length : 0;
    const chars = text.length;
    const minutes = Math.max(1, Math.ceil(words / 200));

    wordCountEl.textContent = words;
    charCountEl.textContent = chars;
    readingTimeEl.textContent = words > 0 ? minutes + ' min read' : '0 min read';
}

function saveDraft() {
    const draft = {
        title: titleInput.value,
        content: trixEditor.innerHTML,
        savedAt: new Date().toISOString()
    };
    localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
    draftStatusEl.textContent = 'Draft saved at ' + new Date().toLocaleTimeString();
}

function scheduleSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(saveDraft, 800);
}

function loadDraft() {
    const raw = localStorage.getItem(DRAFT_KEY);
    if (!raw) return;

    const draft = JSON.parse(raw);
    draftTimeEl.textContent = new Date(draft.savedAt).toLocaleString();
    draftBanner.style.display = 'flex';

    document.getElementById('restoreDraftBtn').onclick = function() {
        titleInput.value = draft.title;
        trixEditor.editor.loadHTML(draft.content);
        draftBanner.style.display = 'none';
        updateCounts();
    };

    document.getElementById('discardDraftBtn').onclick = function() {
        localStorage.removeItem(DRAFT_KEY);
        draftBanner.style.display = 'none';
    };
}

titleInput.addEventListener('input', scheduleSave);

trixEditor.addEventListener('trix-change', function() {
    updateCounts();
    scheduleSave();
});

trixEditor.addEventListener('trix-initialize', function() {
    updateCounts();
    loadDraft();
});

document.querySelector('form').addEventListener('submit', function() {
    localStorage.removeItem(DRAFT_KEY);
});

imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) {
        imagePreview.style.display = 'none';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        imagePreview.src = e.target.result;
        imagePreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

</script>

</body>
</html>