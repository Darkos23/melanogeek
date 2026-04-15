<article class="feed-card fade-in" data-post-id="{{ $post->id }}">

    {{-- En-tête auteur --}}
    <div class="feed-card-head">
        <a href="{{ route('profile.show', $post->user->username) }}" class="feed-card-author">
            <div class="feed-avatar">
                <div class="feed-avatar-inner">
                    @if($post->user->avatar)
                        <img src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->name }}">
                    @else
                        {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                    @endif
                </div>
            </div>
            <div class="feed-author-info">
                <div class="feed-author-name">
                    {{ $post->user->username }}
                    @if($post->user->is_verified)
                        <span class="verified-badge">✓</span>
                    @endif
                </div>
                <div class="feed-author-meta" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                    {{ $post->created_at?->diffForHumans() ?? "-" }}
                </div>
            </div>
        </a>

        {{-- Bouton suivre (si pas l'auteur du post) — utilise le tableau PHP, 0 requête SQL --}}
        @if(auth()->id() !== $post->user_id)
            @php $alreadyFollowing = in_array((int) $post->user_id, $followingIdsArr ?? []); @endphp
            <button class="feed-follow-btn {{ $alreadyFollowing ? 'following' : '' }}"
                onclick="toggleFollow('{{ $post->user->username }}', this)">
                {{ $alreadyFollowing ? 'Abonné ✓' : '+ Suivre' }}
            </button>
        @endif
    </div>

        {{-- Titre --}}
        @if($post->title)
            <a href="{{ route('posts.show', $post->id) }}" class="feed-card-title" style="display:block;text-decoration:none;">
                {{ $post->title }}
            </a>
        @endif

        {{-- Corps (tronqué si long) --}}
        @if($post->body)
            @php $longBody = mb_strlen($post->body) > 280; @endphp
            <div class="feed-card-body {{ $longBody ? 'truncated' : '' }}">{{ $post->body }}</div>
            @if($longBody)
                <a href="{{ route('posts.show', $post->id) }}" class="feed-read-more">Lire la suite →</a>
            @endif
        @endif

        {{-- Média --}}
        @if($post->media_url)
            <div class="feed-card-media">
                @if($post->media_type === 'video')
                    <video src="{{ Storage::url($post->media_url) }}" controls></video>
                @else
                    <a href="{{ route('posts.show', $post->id) }}">
                        <img src="{{ Storage::url($post->media_url) }}" alt="{{ $post->title }}">
                    </a>
                @endif
            </div>
        @endif

    {{-- Actions --}}
    <div class="feed-card-actions">
        <button class="feed-action-btn {{ in_array($post->id, $likedPostIds) ? 'liked' : '' }}"
            onclick="toggleLike({{ $post->id }}, this)">
            <svg viewBox="0 0 24 24" fill="{{ in_array($post->id, $likedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span class="like-count">{{ number_format($post->likes_count) }}</span>
        </button>

        <a href="{{ route('posts.show', $post->id) }}#comments" class="feed-action-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            {{ number_format($post->comments_count) }}
        </a>

        <div class="feed-action-sep"></div>

        @if(auth()->id() !== $post->user_id)
        <button class="feed-action-btn" style="color:var(--text-faint);font-size:.75rem;"
            onclick="openReportModal({{ $post->id }})" title="Signaler ce contenu">
            🚩
        </button>
        @endif

        <button class="feed-action-share" onclick="sharePost('{{ route('posts.show', $post->id) }}')">
            ↗ Partager
        </button>
    </div>

</article>
