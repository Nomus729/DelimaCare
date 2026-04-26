<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($article) ? 'Edit Konten' : 'Buat Konten Baru' }} - Admin DelimaCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} }
        }
    </script>
    
    {{-- QUILL EDITOR --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FFFE; }
        html.dark body { background-color: #0B1120; }
        
        /* Quill Editor Customization */
        .editor-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        #quill-editor {
            min-height: 500px;
            font-size: 16px;
            line-height: 1.7;
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            color: #1F2937;
            padding: 1rem 0.5rem;
        }
        .ql-toolbar.ql-snow {
            border: 1px solid #E5E7EB;
            border-bottom: none;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            background-color: #F8FAFC;
            padding: 14px 16px;
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        /* Style toolbar groups to look distinct */
        .ql-formats {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 0.5rem;
            padding: 2px;
            margin-right: 0 !important;
        }
        .ql-toolbar.ql-snow button:hover, .ql-toolbar.ql-snow button:focus, .ql-toolbar.ql-snow .ql-picker-label:hover, .ql-toolbar.ql-snow .ql-picker-label.ql-active {
            color: #0D9488 !important;
        }
        .ql-snow .ql-stroke { stroke: #64748B; stroke-width: 1.8; }
        .ql-snow .ql-fill { fill: #64748B; }
        .ql-toolbar.ql-snow button:hover .ql-stroke, .ql-toolbar.ql-snow .ql-picker-label:hover .ql-stroke, .ql-toolbar.ql-snow .ql-picker-label.ql-active .ql-stroke { stroke: #0D9488 !important; }
        .ql-toolbar.ql-snow button:hover .ql-fill, .ql-toolbar.ql-snow .ql-picker-label:hover .ql-fill, .ql-toolbar.ql-snow .ql-picker-label.ql-active .ql-fill { fill: #0D9488 !important; }
        
        /* Dark Mode adjustments for Quill */
        html.dark .editor-wrapper {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
        }
        html.dark #quill-editor {
            background-color: #0F172A;
            border-color: #334155;
            color: #E2E8F0;
        }
        html.dark .ql-toolbar.ql-snow {
            background-color: #1E293B;
            border-color: #334155;
        }
        html.dark .ql-formats {
            background: #0F172A;
            border-color: #334155;
        }
        html.dark .ql-snow .ql-stroke { stroke: #94A3B8; }
        html.dark .ql-snow .ql-fill { fill: #94A3B8; }
        html.dark .ql-snow .ql-picker { color: #94A3B8; }
        html.dark .ql-snow .ql-picker-options { background-color: #1E293B; border-color: #334155; }
        html.dark .ql-toolbar.ql-snow button:hover, html.dark .ql-toolbar.ql-snow .ql-picker-label:hover { color: #2DD4BF !important; }
        html.dark .ql-toolbar.ql-snow button:hover .ql-stroke, html.dark .ql-toolbar.ql-snow .ql-picker-label:hover .ql-stroke { stroke: #2DD4BF !important; }
        html.dark .ql-toolbar.ql-snow button:hover .ql-fill, html.dark .ql-toolbar.ql-snow .ql-picker-label:hover .ql-fill { fill: #2DD4BF !important; }
        
        /* Focus state */
        .editor-wrapper:focus-within .ql-toolbar {
            border-color: #0D9488;
            border-bottom-color: transparent;
        }
        .editor-wrapper:focus-within #quill-editor {
            border-color: #0D9488;
            border-top-color: #E5E7EB;
        }
        html.dark .editor-wrapper:focus-within #quill-editor {
            border-top-color: #334155;
        }
    </style>
</head>

<body class="text-gray-900 antialiased dark:text-gray-100 relative">

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-5 right-5 z-[100] transform transition-all duration-300 translate-y-[-150%] opacity-0 flex items-center p-4 mb-4 w-full max-w-xs text-gray-500 bg-white rounded-xl shadow-xl dark:text-gray-400 dark:bg-gray-800 border-l-4 border-red-500" role="alert">
        <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="ml-3 text-sm font-semibold text-gray-800 dark:text-white" id="toast-message">Maksimal ukuran file adalah 2 MB.</div>
    </div>

    <div class="max-w-4xl mx-auto py-10 px-6">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ isset($article) ? 'Edit Konten' : 'Buat Konten Baru' }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan editor di bawah untuk menulis artikel dengan format lengkap.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl">
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ isset($article) ? route('admin.konten.update', $article->id) : route('admin.konten.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#1E293B] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8 space-y-6" id="articleForm">
            @csrf
            @if(isset($article))
                @method('PUT')
            @endif

            {{-- Judul & Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Judul Konten <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-gray-900 dark:text-white font-medium" 
                           placeholder="Contoh: Manfaat ASI Eksklusif">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-gray-900 dark:text-white font-medium">
                        <option value="Artikel" {{ old('category', $article->category ?? '') == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                        <option value="Berita" {{ old('category', $article->category ?? '') == 'Berita' ? 'selected' : '' }}>Berita</option>
                        <option value="Acara" {{ old('category', $article->category ?? '') == 'Acara' ? 'selected' : '' }}>Acara</option>
                    </select>
                </div>
            </div>

            {{-- Thumbnail / Banner --}}
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Gambar Banner / Thumbnail</label>
                
                {{-- Preview Container --}}
                <div id="preview-container" class="mb-3 w-64 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 {{ (isset($article) && $article->image_path) ? '' : 'hidden' }}">
                    <img id="image-preview" src="{{ (isset($article) && $article->image_path) ? asset('storage/' . $article->image_path) : '' }}" alt="Preview Banner" class="w-full object-cover h-auto">
                </div>
                
                <input type="file" name="image" id="image-input" accept="image/*"
                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-900/30 dark:file:text-teal-400 cursor-pointer">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB. Gambar akan ditampilkan di Landing Page.</p>
            </div>

            {{-- Rich Text Editor (Quill) --}}
            <div class="space-y-2 pt-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Isi Konten <span class="text-red-500">*</span></label>
                
                <input id="hidden-content" type="hidden" name="content" value="{{ old('content', $article->content ?? '') }}">
                
                <div class="editor-wrapper">
                    <div id="quill-editor"></div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-xl font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-semibold text-white bg-teal-600 hover:bg-teal-700 shadow-lg shadow-teal-500/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan Publikasi
                </button>
            </div>

        </form>
    </div>

    <script>
        // Fungsi untuk menampilkan Pop-up / Toast
        function showToast(message) {
            var toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-[-150%]', 'opacity-0');
            setTimeout(function() {
                toast.classList.add('translate-y-[-150%]', 'opacity-0');
            }, 4000);
        }

        // Inisialisasi Quill Editor
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tuliskan konten terbaik Anda di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, false] }], // Menu Heading
                    ['bold', 'italic', 'underline', 'strike'], // Format text
                    [{ 'align': [] }], // Rata kanan, kiri, tengah, justify
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }], // Lists
                    ['blockquote', 'link', 'image'], // Blocks & media
                    ['clean'] // Tombol untuk menghapus format
                ]
            }
        });

        // Custom Handler untuk Image Upload di dalam Quill
        var toolbar = quill.getModule('toolbar');
        toolbar.addHandler('image', function() {
            var fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('accept', 'image/*');
            fileInput.click();

            fileInput.onchange = function() {
                var file = fileInput.files[0];
                if (file) {
                    // Validasi ukuran file (maksimal 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showToast("Ukuran gambar di dalam konten terlalu besar! Maksimal 2 MB.");
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', e.target.result);
                        quill.setSelection(range.index + 1);
                    };
                    reader.readAsDataURL(file);
                }
            };
        });

        // Set konten awal jika sedang mode Edit
        var existingContent = document.getElementById('hidden-content').value;
        if (existingContent) {
            quill.clipboard.dangerouslyPasteHTML(existingContent);
        }

        // Tangkap event submit, masukkan isi dari quill ke input hidden
        var form = document.getElementById('articleForm');
        form.onsubmit = function() {
            var html = quill.root.innerHTML;
            // Jika editor kosong (hanya tag p kosong), ubah jadi kosong beneran
            if (html === '<p><br></p>') {
                html = '';
            }
            document.getElementById('hidden-content').value = html;
        };
        // Fitur Live Preview Gambar Banner
        var imageInput = document.getElementById('image-input');
        var previewContainer = document.getElementById('preview-container');
        var imagePreview = document.getElementById('image-preview');

        if (imageInput) {
            imageInput.addEventListener('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    // Validasi ukuran file (maksimal 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showToast("Ukuran gambar banner terlalu besar! Maksimal ukuran file adalah 2 MB.");
                        // Reset input
                        this.value = "";
                        // Sembunyikan preview jika sebelumnya kosong
                        if (imagePreview.src === '' || imagePreview.src === window.location.href) {
                            previewContainer.classList.add('hidden');
                        }
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    // Jika user membatalkan pilihan file baru dan sebelumnya tidak ada gambar
                    if (imagePreview.src === '' || imagePreview.src === window.location.href) {
                        previewContainer.classList.add('hidden');
                    }
                }
            });
        }
    </script>
</body>
</html>
