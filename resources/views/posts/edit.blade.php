@extends('layouts.blog')

@section('title', 'Modifier la publication')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
/* Quill dark */
.ql-toolbar.ql-snow { border:none; border-top:1px solid var(--border); background:var(--bg-card2); padding:10px 16px; flex-wrap:wrap; gap:4px; }
.ql-toolbar.ql-snow .ql-formats { margin-right:8px; }
.ql-toolbar.ql-snow button, .ql-toolbar.ql-snow .ql-picker-label { color:var(--text-muted); border-radius:6px; transition:background .15s,color .15s; }
.ql-toolbar.ql-snow button:hover, .ql-toolbar.ql-snow .ql-picker-label:hover { background:var(--bg-card); color:var(--text); }
.ql-toolbar.ql-snow button.ql-active, .ql-toolbar.ql-snow .ql-picker-label.ql-active { color:var(--terra); }
.ql-toolbar.ql-snow .ql-stroke { stroke:currentColor; }
.ql-toolbar.ql-snow .ql-fill  { fill:currentColor; }
.ql-toolbar.ql-snow .ql-picker-options { background:var(--bg-card); border:1px solid var(--border); border-radius:8px; }
.ql-toolbar.ql-snow .ql-picker-item:hover { color:var(--terra); }
.ql-container.ql-snow { border:none; }
.ql-editor { min-height:220px; font-family:var(--font-body); font-size:.95rem; color:var(--text); padding:18px 24px; line-height:1.7; }
.ql-editor.ql-blank::before { color:var(--text-faint); font-style:normal; }
.ql-editor h2 { font-family:var(--font-head); font-size:1.4rem; font-weight:700; color:var(--text); margin:16px 0 8px; }
.ql-editor h3 { font-family:var(--font-head); font-size:1.1rem; font-weight:700; color:var(--text); margin:12px 0 6px; }
.ql-editor blockquote { border-left:3px solid var(--terra); padding-left:14px; color:var(--text-muted); margin:12px 0; font-style:italic; }
.ql-editor pre.ql-syntax { background:var(--bg-card2); border-radius:8px; padding:12px 16px; font-family:'JetBrains Mono',monospace; font-size:.82rem; color:#a5f3fc; }
.ql-editor a { color:var(--terra); }
.ql-editor ul, .ql-editor ol { padding-left:18px; }
.ql-editor p { margin-bottom:4px; }
/* Page */
.edit-page { min-height:100vh; padding-top:24px; padding-bottom:60px; overflow-x:hidden; width:100%; }
.edit-wrap { max-width:720px; margin:0 auto; padding:0 16px; overflow-x:hidden; }
.edit-card { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
/* Quill overflow fix */
.ql-toolbar.ql-snow { flex-wrap:wrap !important; overflow-x:hidden !important; }
.ql-editor { overflow-x:hidden !important; word-break:break-word; }
.ql-container { max-width:100%; }
.edit-header { padding:20px 24px 16px; border-bottom:1px solid var(--border); }
.edit-header h2 { font-family:var(--font-head); font-size:1.1rem; font-weight:700; color:var(--text); }
.edit-title { width:100%; background:transparent; border:none; outline:none; font-family:var(--font-head); font-size:1.25rem; font-weight:700; color:var(--text); padding:18px 24px 0; resize:none; line-height:1.4; }
.edit-title::placeholder { color:var(--text-faint); }
.edit-meta { padding:10px 24px 14px; display:flex; align-items:center; gap:10px; }
.edit-meta label { font-size:.72rem; color:var(--text-muted); font-weight:600; }
.edit-category { background:var(--bg-card2); border:1px solid var(--border); color:var(--text); border-radius:8px; padding:6px 10px; font-size:.8rem; outline:none; font-family:var(--font-body); }
/* Cover zone */
.cover-upload-zone { position:relative; margin:0 24px 16px; aspect-ratio:16/5; border-radius:10px; border:1.5px dashed var(--border-hover); background:var(--bg); display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; transition:border-color .2s,background .2s; }
.cover-upload-zone:hover { border-color:var(--terra); background:rgba(200,82,42,.04); }
.cover-upload-zone.has-cover { border-style:solid; border-color:transparent; }
.cover-placeholder { display:flex; flex-direction:column; align-items:center; gap:8px; color:var(--text-muted); font-size:.72rem; pointer-events:none; }
.cover-remove-btn { position:absolute; top:8px; right:8px; width:26px; height:26px; background:rgba(0,0,0,.6); color:#fff; border:none; border-radius:50%; font-size:.72rem; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:2; }
.cover-remove-btn:hover { background:rgba(200,50,50,.8); }
/* Actions */
.edit-actions { padding:16px 24px; display:flex; align-items:center; gap:10px; border-top:1px solid var(--border); }
.btn-save { display:inline-flex; align-items:center; gap:6px; padding:0 20px; height:40px; border-radius:100px; background:var(--terra); color:#fff; font-weight:700; font-size:.82rem; border:none; cursor:pointer; transition:opacity .2s; }
.btn-save:hover { opacity:.85; }
.btn-cancel { color:var(--text-muted); font-size:.82rem; text-decoration:none; }
</style>
@endpush

@section('main')
<div class="edit-page">
<div class="edit-wrap">

    <div class="edit-card">
        <div class="edit-header">
            <h2 style="display:flex;align-items:center;gap:8px;"><x-icon name="edit" :size="18"/> Modifier la publication</h2>
        </div>

        <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            {{-- Cover image --}}
            <div class="cover-upload-zone" id="coverZone" onclick="document.getElementById('coverInput').click()">
                @if($post->thumbnail)
                    <img id="coverPreview" src="{{ asset('storage/'.$post->thumbnail) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:10px;">
                    <button type="button" class="cover-remove-btn" id="coverRemoveBtn" onclick="removeCover(event)"><x-icon name="x" :size="14"/></button>
                    <input type="file" id="coverInput" name="thumbnail" accept="image/jpeg,image/png,image/webp" style="display:none">
                @else
                    <img id="coverPreview" src="" alt="" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:10px;">
                    <div class="cover-placeholder" id="coverPlaceholder">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.4"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                        <span>Changer l'image de couverture</span>
                    </div>
                    <button type="button" class="cover-remove-btn" id="coverRemoveBtn" style="display:none;" onclick="removeCover(event)"><x-icon name="x" :size="14"/></button>
                    <input type="file" id="coverInput" name="thumbnail" accept="image/jpeg,image/png,image/webp" style="display:none">
                @endif
                @if($post->thumbnail)
                    <script>document.getElementById('coverZone').classList.add('has-cover');</script>
                @endif
            </div>

            {{-- Titre --}}
            <textarea class="edit-title" name="title" placeholder="Titre (optionnel)" rows="1" maxlength="150" oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'">{{ old('title', $post->title) }}</textarea>

            {{-- Corps --}}
            <div id="quillEditor" style="border:none;"></div>
            <input type="hidden" name="body" id="postBody" value="{{ old('body', $post->body) }}">

            {{-- Catégorie --}}
            <div class="edit-meta">
                <label for="postCategory">Catégorie</label>
                <select name="category" id="postCategory" class="edit-category">
                    <option value="">Aucune</option>
                    @foreach($postCategories as $slug => $label)
                        <option value="{{ $slug }}" @selected(old('category', $post->category) === $slug)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- YouTube --}}
            <div class="edit-meta" style="flex-direction:column;align-items:stretch;gap:8px;">
                <label>Vidéo YouTube (optionnel)</label>
                <div style="display:flex;align-items:center;gap:10px;background:var(--bg-card2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color:#ff0000;flex-shrink:0"><path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.54 3.5 12 3.5 12 3.5s-7.54 0-9.38.55A3.02 3.02 0 0 0 .5 6.19C0 8.04 0 12 0 12s0 3.96.5 5.81a3.02 3.02 0 0 0 2.12 2.14C4.46 20.5 12 20.5 12 20.5s7.54 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14C24 15.96 24 12 24 12s0-3.96-.5-5.81zM9.75 15.52V8.48L15.5 12l-5.75 3.52z"/></svg>
                    <input type="text" name="youtube_url" id="youtubeUrlInput"
                        placeholder="https://www.youtube.com/watch?v=..."
                        value="{{ old('youtube_url', $post->youtube_url) }}"
                        style="flex:1;background:transparent;border:none;outline:none;color:var(--text);font-family:var(--font-body);font-size:.88rem;"
                        oninput="updateYoutubePreview(this.value)">
                </div>
                <div id="youtubePreviewWrap" style="border-radius:10px;overflow:hidden;aspect-ratio:16/9;background:#000;{{ old('youtube_url', $post->youtube_url) ? '' : 'display:none;' }}">
                    <iframe id="youtubePreviewFrame"
                        src="{{ $post->youtube_id ? 'https://www.youtube.com/embed/'.$post->youtube_id : '' }}"
                        width="100%" height="100%" frameborder="0" allowfullscreen style="display:block;"></iframe>
                </div>
            </div>

            <div class="edit-actions">
                <button type="submit" class="btn-save" style="display:inline-flex;align-items:center;gap:6px;"><x-icon name="check" :size="15"/> Enregistrer</button>
                <a href="{{ route('posts.show', $post->id) }}" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Contenu de l\'article…',
    modules: {
        toolbar: [
            [{ header: [2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'], ['clean']
        ]
    }
});

// Pré-remplir avec le contenu existant
const existing = document.getElementById('postBody').value;
if (existing) {
    quill.root.innerHTML = existing;
}

quill.on('text-change', () => {
    document.getElementById('postBody').value = quill.root.innerHTML;
});

// Cover image
document.getElementById('coverInput').addEventListener('change', function() {
    if (!this.files || !this.files[0]) return;
    const url = URL.createObjectURL(this.files[0]);
    const preview = document.getElementById('coverPreview');
    preview.src = url;
    preview.style.display = 'block';
    const ph = document.getElementById('coverPlaceholder');
    if (ph) ph.style.display = 'none';
    document.getElementById('coverRemoveBtn').style.display = 'flex';
    document.getElementById('coverZone').classList.add('has-cover');
});

function removeCover(e) {
    e.stopPropagation();
    document.getElementById('coverInput').value = '';
    const preview = document.getElementById('coverPreview');
    preview.style.display = 'none';
    preview.src = '';
    const ph = document.getElementById('coverPlaceholder');
    if (ph) ph.style.display = 'flex';
    document.getElementById('coverRemoveBtn').style.display = 'none';
    document.getElementById('coverZone').classList.remove('has-cover');
}

function updateYoutubePreview(url) {
    const id = extractYoutubeId(url);
    const wrap  = document.getElementById('youtubePreviewWrap');
    const frame = document.getElementById('youtubePreviewFrame');
    if (id) {
        frame.src = 'https://www.youtube.com/embed/' + id;
        wrap.style.display = 'block';
    } else {
        frame.src = '';
        wrap.style.display = 'none';
    }
}

function extractYoutubeId(url) {
    if (!url) return null;
    let m;
    if ((m = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/))) return m[1];
    if ((m = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/))) return m[1];
    if ((m = url.match(/embed\/([a-zA-Z0-9_-]{11})/))) return m[1];
    if ((m = url.match(/shorts\/([a-zA-Z0-9_-]{11})/))) return m[1];
    return null;
}
</script>
@endpush
