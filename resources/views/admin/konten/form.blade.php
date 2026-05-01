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

        /* Image Source Modal */
        #image-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 10001;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .image-modal {
            background: white;
            width: 90%;
            max-width: 400px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        html.dark .image-modal { background: #1E293B; border: 1px solid #334155; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .image-modal-header { padding: 20px 24px; border-bottom: 1px solid #F1F5F9; }
        html.dark .image-modal-header { border-bottom-color: #334155; }
        
        .image-modal-body { padding: 24px; }
        .image-option-btn {
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            border: 2px solid #F1F5F9;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            margin-bottom: 12px;
            text-align: left;
        }
        html.dark .image-option-btn { border-color: #334155; background: #0F172A; }
        .image-option-btn:hover { border-color: #0D9488; background: #F0FDFA; }
        html.dark .image-option-btn:hover { border-color: #2DD4BF; background: #134E4A; }
        
        .url-input-container {
            display: none;
            margin-top: 12px;
            animation: fadeIn 0.3s;
        }

        /* Floating Context Menu */
        #floating-toolbar {
            position: fixed;
            display: none;
            z-index: 9999;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 8px;
            border: 1px solid #E2E8F0;
            flex-direction: row;
            gap: 4px;
            align-items: center;
            animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        html.dark #floating-toolbar {
            background: #1E293B;
            border-color: #334155;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        #floating-toolbar button {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-center;
            border-radius: 6px;
            color: #64748B;
            transition: all 0.2s;
        }
        #floating-toolbar button:hover {
            background: #F1F5F9;
            color: #0D9488;
        }
        html.dark #floating-toolbar button:hover {
            background: #0F172A;
            color: #2DD4BF;
        }
        #floating-toolbar button.active {
            background: #F0FDFA;
            color: #0D9488;
        }
        html.dark #floating-toolbar button.active {
            background: #134E4A;
            color: #2DD4BF;
        }
        #floating-toolbar .divider {
            width: 1px;
            height: 20px;
            background: #E2E8F0;
            margin: 0 4px;
        }
        html.dark #floating-toolbar .divider {
            background: #334155;
        }
    </style>
</head>

<body class="text-gray-900 antialiased dark:text-gray-100 relative">

    {{-- Image Source Modal --}}
    <div id="image-modal-overlay">
        <div class="image-modal">
            <div class="image-modal-header flex items-center justify-between">
                <h3 class="font-bold text-lg">Pilih Sumber Gambar</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="image-modal-body">
                <button onclick="triggerFileUpload()" class="image-option-btn group">
                    <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm">Unggah dari Komputer</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Pilih file JPG, PNG, atau WEBP</div>
                    </div>
                </button>
                
                <button onclick="showUrlInput()" class="image-option-btn group">
                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm">Gunakan Link URL</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Tempelkan alamat URL gambar</div>
                    </div>
                </button>

                <div id="url-input-area" class="url-input-container">
                    <div class="relative">
                        <input type="text" id="img-url-field" placeholder="https://contoh.com/gambar.jpg" 
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-sm mb-3">
                        <div class="flex gap-2">
                            <button onclick="insertImageUrl()" class="flex-1 py-2.5 bg-teal-600 text-white rounded-lg text-sm font-bold hover:bg-teal-700">Masukkan</button>
                            <button onclick="hideUrlInput()" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-lg text-sm font-bold">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Context Toolbar --}}
    <div id="floating-toolbar" class="ql-snow">
        <button type="button" data-format="bold" title="Bold">
            <svg viewBox="0 0 18 18"><path class="ql-stroke" d="M5,4H9.5A2.5,2.5,0,0,1,12,6.5,2.5,2.5,0,0,1,9.5,9H5Z"></path><path class="ql-stroke" d="M5,9h5.5A2.5,2.5,0,0,1,13,11.5,2.5,2.5,0,0,1,10.5,14H5Z"></path></svg>
        </button>
        <button type="button" data-format="italic" title="Italic">
            <svg viewBox="0 0 18 18"><line class="ql-stroke" x1="7" x2="13" y1="4" y2="4"></line><line class="ql-stroke" x1="5" x2="11" y1="14" y2="14"></line><line class="ql-stroke" x1="8" x2="10" y1="14" y2="4"></line></svg>
        </button>
        <button type="button" data-format="underline" title="Underline">
            <svg viewBox="0 0 18 18"><path class="ql-stroke" d="M5,3V9a4,4,0,0,0,8,0V3"></path><line class="ql-stroke" x1="3" x2="15" y1="15" y2="15"></line></svg>
        </button>
        <div class="divider"></div>
        <button type="button" data-format="header" data-value="1" title="H1">
            <svg viewBox="0 0 18 18"><path class="ql-fill" d="M10,4V14a1,1,0,0,1-2,0V10H3v4a1,1,0,0,1-2,0V4A1,1,0,0,1,3,4V8H8V4a1,1,0,0,1,2,0Zm6.067,9.11V14a1,1,0,0,1-2,0V12.383h-0.254c-0.12,0-0.231,0.016-0.334,0.048A0.815,0.815,0,0,1,13.2,12.3c-0.08-0.071-0.12-0.178-0.12-0.321s0.038-0.252,0.114-0.327,0.184-0.113,0.326-0.113a0.47,0.47,0,0,1,0.275.079,0.732,0.732,0,0,1,0.21.242,0.569,0.569,0,0,0,0.176.19c0.11,0.063,0.244,0.095,0.4,0.095h0.48V9.524H14.136V9.877c0,0.12,0.013,0.218,0.039,0.293a0.485,0.485,0,0,1,0.13,0.176,0.505,0.505,0,0,1,0.048,0.21,0.42,0.42,0,0,1-0.114,0.306c-0.076,0.076-0.18,0.113-0.312,0.113s-0.238-0.036-0.312-0.109a0.45,0.45,0,0,1-0.111-0.315V9.456a0.844,0.844,0,0,1,0.207-0.584,0.727,0.727,0,0,1,0.543-0.221h1.134a1,1,0,0,1,1,1Z"></path></svg>
        </button>
        <button type="button" data-format="header" data-value="2" title="H2">
            <svg viewBox="0 0 18 18"><path class="ql-fill" d="M10,4V14a1,1,0,0,1-2,0V10H3v4a1,1,0,0,1-2,0V4A1,1,0,0,1,3,4V8H8V4a1,1,0,0,1,2,0Zm6.467,8.559a0.868,0.868,0,0,1-0.279.627,0.919,0.919,0,0,1-0.675.273H13.565v-0.62a1,1,0,0,1,2,0h0.528a0.191,0.191,0,0,0,0.141-0.057,0.182,0.182,0,0,0,0.057-0.139,0.193,0.193,0,0,0-0.048-0.132,0.709,0.709,0,0,0-0.371-0.248,4.425,4.425,0,0,1-1.173-0.571,1.321,1.321,0,0,1-0.471-1.006,1.342,1.342,0,0,1,0.444-1.017,1.5,1.5,0,0,1,1.056-0.412h1.133a1,1,0,0,1,1,1V9.315a1,1,0,0,1-2,0V9.012h-0.41a0.193,0.193,0,0,0-0.144.057,0.191,0.191,0,0,0-0.057,0.141A0.2,0.2,0,0,0,14.736,9.35a0.716,0.716,0,0,0,0.369,0.245,4.408,4.408,0,0,1,1.176,0.574,1.321,1.321,0,0,1,0.471,1.005Z"></path></svg>
        </button>
        <div class="divider"></div>
        <button type="button" data-format="list" data-value="ordered" title="Ordered List">
            <svg viewBox="0 0 18 18"><line class="ql-stroke" x1="7" x2="15" y1="4" y2="4"></line><line class="ql-stroke" x1="7" x2="15" y1="9" y2="9"></line><line class="ql-stroke" x1="7" x2="15" y1="14" y2="14"></line><path class="ql-fill" d="M4.111,7.821a2,2,0,0,0-1.069-1.3l0.313-0.478A2.433,2.433,0,0,1,4.4,7.011,1.431,1.431,0,0,1,4.111,7.821Z"></path><path class="ql-fill" d="M4.582,4.13a0.95,0.95,0,0,1-0.193.593,1.636,1.636,0,0,1-0.512.433A0.463,0.463,0,0,1,3.64,5.13a0.123,0.123,0,0,1-0.137-0.123,0.135,0.135,0,0,1,0.137-0.137A0.462,0.462,0,0,0,3.93,4.58a0.612,0.612,0,0,0,0.051-0.248,0.425,0.425,0,0,0-0.138-0.342,0.527,0.527,0,0,0-0.343-0.112,0.507,0.507,0,0,0-0.493.523A0.136,0.136,0,0,1,2.87,4.537,0.137,0.137,0,0,1,2.733,4.4a0.775,0.775,0,0,1,0.228-0.58A0.8,0.8,0,0,1,3.538,3.6,0.793,0.793,0,0,1,4.13,3.812,0.732,0.732,0,0,1,4.582,4.13Z"></path><path class="ql-fill" d="M3.734,10.334a1,1,0,0,0-0.5-.353l0.227-0.552a1.471,1.471,0,0,1,0.679.447,1.15,1.15,0,0,1,0.308,0.8,1.185,1.185,0,0,1-0.35,0.865l-0.849,0.814h0.871a0.139,0.139,0,0,1,0.139,0.139v0.254a0.139,0.139,0,0,1-0.139,0.139H2.822a0.139,0.139,0,0,1-0.139-0.139V13.5l0.719-0.694a1.074,1.074,0,0,0,0.335-0.71,0.614,0.614,0,0,0-0.127-0.4A0.446,0.446,0,0,0,3.734,10.334Z"></path><path class="ql-fill" d="M4.5,14.87a0.827,0.827,0,0,1-0.249.62,0.922,0.922,0,0,1-0.71.21h-0.02a0.739,0.739,0,0,1-0.71-.447l0.277-0.454a0.444,0.444,0,0,0,0.444.263,0.461,0.461,0,0,0,0.493-0.5,0.461,0.461,0,0,0-0.493-0.5H3.354a0.135,0.135,0,0,1-0.135-0.135v-0.254a0.135,0.135,0,0,1,0.135-0.135h0.178a0.463,0.463,0,0,0,0.445-0.484,0.423,0.423,0,0,0-0.445-0.445,0.345,0.345,0,0,0-0.346.234l-0.294-0.437a0.733,0.733,0,0,1,0.711-0.439,0.771,0.771,0,0,1,0.592.231,0.806,0.806,0,0,1,0.246.58,0.87,0.87,0,0,1-0.19,0.56,0.637,0.637,0,0,1-0.483.344,0.737,0.737,0,0,1,0.627.312A0.9,0.9,0,0,1,4.5,14.87Z"></path></svg>
        </button>
        <button type="button" data-format="list" data-value="bullet" title="Bullet List">
            <svg viewBox="0 0 18 18"><line class="ql-stroke" x1="6" x2="15" y1="4" y2="4"></line><line class="ql-stroke" x1="6" x2="15" y1="9" y2="9"></line><line class="ql-stroke" x1="6" x2="15" y1="14" y2="14"></line><line class="ql-stroke" x1="3" x2="3" y1="4" y2="4"></line><line class="ql-stroke" x1="3" x2="3" y1="9" y2="9"></line><line class="ql-stroke" x1="3" x2="3" y1="14" y2="14"></line></svg>
        </button>
        <div class="divider"></div>
        <button type="button" data-format="link" title="Link">
            <svg viewBox="0 0 18 18"><line class="ql-stroke" x1="7" x2="11" y1="7" y2="11"></line><path class="ql-even ql-stroke" d="M8.9,4.577a3.476,3.476,0,0,1,.36,4.617l-1.33,1.33a3.476,3.476,0,0,1-4.915,0l-0.012-.012a3.476,3.476,0,0,1,0-4.917L4.33,4.265A3.476,3.476,0,0,1,8.9,4.577Z"></path><path class="ql-even ql-stroke" d="M13.668,13.734a3.476,3.476,0,0,1-4.917,0l-1.331-1.33a3.476,3.476,0,0,1,0-4.917l0.012-.012a3.476,3.476,0,0,1,4.917,0l1.33,1.33A3.476,3.476,0,0,1,13.668,13.734Z"></path></svg>
        </button>
        <button type="button" data-format="image" title="Image">
            <svg viewBox="0 0 18 18"><rect class="ql-stroke" height="10" width="12" x="3" y="4"></rect><circle class="ql-fill" cx="6" cy="7" r="1"></circle><polyline class="ql-even ql-fill" points="5 12 5 11 7 9 8 10 11 7 13 9 13 12 5 12"></polyline></svg>
        </button>
    </div>

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
            <div class="space-y-3">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Gambar Banner / Thumbnail</label>
                
                <div class="flex items-center gap-4 mb-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="image_source" value="file" checked 
                               class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300"
                               onchange="toggleBannerSource('file')">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 group-hover:text-teal-600">Unggah File</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="image_source" value="url" 
                               {{ (isset($article) && filter_var($article->image_path, FILTER_VALIDATE_URL)) ? 'checked' : '' }}
                               class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300"
                               onchange="toggleBannerSource('url')">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 group-hover:text-teal-600">Gunakan URL</span>
                    </label>
                </div>

                {{-- Preview Container --}}
                <div id="preview-container" class="mb-3 w-64 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 {{ (isset($article) && $article->image_path) ? '' : 'hidden' }}">
                    <img id="image-preview" src="{{ (isset($article) && $article->image_path) ? $article->image_url : '' }}" alt="Preview Banner" class="w-full object-cover h-auto">
                </div>
                
                <div id="banner-file-wrapper" class="{{ (isset($article) && filter_var($article->image_path, FILTER_VALIDATE_URL)) ? 'hidden' : '' }}">
                    <input type="file" name="image" id="image-input" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-900/30 dark:file:text-teal-400 cursor-pointer">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                </div>

                <div id="banner-url-wrapper" class="{{ (isset($article) && filter_var($article->image_path, FILTER_VALIDATE_URL)) ? '' : 'hidden' }}">
                    <input type="text" name="image_url" id="banner-url-input" 
                           value="{{ old('image_url', (isset($article) && filter_var($article->image_path, FILTER_VALIDATE_URL)) ? $article->image_path : '') }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-gray-900 dark:text-white text-sm" 
                           placeholder="Masukkan URL Gambar (https://...)">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pastikan URL gambar valid dan dapat diakses publik.</p>
                </div>
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

        // Custom Handler untuk Image Upload di dalam Quill (Mendukung File & Link)
        var toolbar = quill.getModule('toolbar');
        toolbar.addHandler('image', function() {
            openImageModal();
        });

        // Image Modal Logic
        const imageModal = document.getElementById('image-modal-overlay');
        const urlInputArea = document.getElementById('url-input-area');
        const urlField = document.getElementById('img-url-field');

        function openImageModal() {
            imageModal.style.display = 'flex';
            hideUrlInput();
        }

        function closeImageModal() {
            imageModal.style.display = 'none';
        }

        function showUrlInput() {
            urlInputArea.style.display = 'block';
            urlField.focus();
        }

        function hideUrlInput() {
            urlInputArea.style.display = 'none';
            urlField.value = '';
        }

        function triggerFileUpload() {
            closeImageModal();
            var fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('accept', 'image/*');
            fileInput.click();

            fileInput.onchange = function() {
                var file = fileInput.files[0];
                if (file) {
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
        }

        function insertImageUrl() {
            const url = urlField.value;
            if (url && url.trim() !== "") {
                var range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url);
                quill.setSelection(range.index + 1);
                closeImageModal();
            } else {
                alert("Harap masukkan URL gambar yang valid.");
            }
        }

        // --- Banner Image Logic ---
        function toggleBannerSource(source) {
            const fileWrapper = document.getElementById('banner-file-wrapper');
            const urlWrapper = document.getElementById('banner-url-wrapper');
            
            if (source === 'file') {
                fileWrapper.classList.remove('hidden');
                urlWrapper.classList.add('hidden');
                // Trigger preview refresh from file input if exists
                if (imageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => { imagePreview.src = e.target.result; previewContainer.classList.remove('hidden'); };
                    reader.readAsDataURL(imageInput.files[0]);
                }
            } else {
                fileWrapper.classList.add('hidden');
                urlWrapper.classList.remove('hidden');
                // Trigger preview refresh from URL input
                const url = document.getElementById('banner-url-input').value;
                if (url) {
                    imagePreview.src = url;
                    previewContainer.classList.remove('hidden');
                }
            }
        }

        // Live preview for Banner URL
        const bannerUrlInput = document.getElementById('banner-url-input');
        if (bannerUrlInput) {
            bannerUrlInput.addEventListener('input', function() {
                if (document.querySelector('input[name="image_source"]:checked').value === 'url') {
                    const url = this.value;
                    if (url) {
                        imagePreview.src = url;
                        previewContainer.classList.remove('hidden');
                    } else {
                        previewContainer.classList.add('hidden');
                    }
                }
            });
        }

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

        // Logic for Floating Context Toolbar
        const floatingToolbar = document.getElementById('floating-toolbar');
        const editorElement = document.getElementById('quill-editor');

        editorElement.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            
            // Show toolbar at mouse position
            floatingToolbar.style.display = 'flex';
            
            // Adjust position to stay within viewport
            let x = e.clientX;
            let y = e.clientY - 50; // Show slightly above cursor
            
            const toolbarRect = floatingToolbar.getBoundingClientRect();
            if (x + toolbarRect.width > window.innerWidth) x = window.innerWidth - toolbarRect.width - 20;
            if (y < 0) y = e.clientY + 20;
            
            floatingToolbar.style.left = x + 'px';
            floatingToolbar.style.top = y + 'px';
        });

        // Hide floating toolbar on click elsewhere or scrolling
        document.addEventListener('mousedown', function(e) {
            if (!floatingToolbar.contains(e.target)) {
                floatingToolbar.style.display = 'none';
            }
        });

        window.addEventListener('scroll', () => {
            floatingToolbar.style.display = 'none';
        });

        // Handle floating toolbar actions
        floatingToolbar.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function() {
                const format = this.getAttribute('data-format');
                const value = this.getAttribute('data-value') || true;
                
                // Get current selection
                const range = quill.getSelection();
                if (range) {
                    if (format === 'image') {
                        // Trigger the same image handler as the main toolbar
                        const imageHandler = quill.getModule('toolbar').handlers['image'];
                        imageHandler.call(quill.getModule('toolbar'));
                    } else if (format === 'link') {
                         const currentFormat = quill.getFormat(range);
                         if (currentFormat.link) {
                             quill.format('link', false);
                         } else {
                             const url = prompt('Masukkan URL:');
                             if (url) quill.format('link', url);
                         }
                    } else {
                        // Toggle format
                        const currentFormat = quill.getFormat(range);
                        const isValueMatch = currentFormat[format] == value;
                        quill.format(format, isValueMatch ? false : value);
                    }
                }
                
                floatingToolbar.style.display = 'none';
            });
        });
    </script>
</body>
</html>
