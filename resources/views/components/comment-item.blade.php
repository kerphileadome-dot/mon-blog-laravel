@props(['comment', 'post', 'allComments', 'depth' => 0])

@php
    $isOwner = auth('web')->check() && (int) auth('web')->id() === (int) $comment->user_id;
@endphp

<div class="{{ $depth > 0 ? 'comment-reply' : 'comment-item' }}" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <div>
            <span class="{{ $depth > 0 ? 'comment-reply-author' : 'comment-author' }}">{{ $comment->name }}</span>
            <span class="{{ $depth > 0 ? 'comment-reply-time' : 'comment-time' }}">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <div class="comment-owner-actions">
            @if($isOwner)
                <button type="button" class="action-link" style="font-size:0.8rem;width:auto;" onclick="toggleEdit({{ $comment->id }})">Modifier</button>
                <form method="POST" action="{{ route('comments.destroy', [$post, $comment]) }}" class="inline" onsubmit="return confirm('Supprimer ce commentaire ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-link danger" style="font-size:0.8rem;width:auto;">Supprimer</button>
                </form>
            @endif
            @auth('admin')
                <form method="POST" action="{{ route('admin.comments.delete', $comment) }}" class="inline" onsubmit="return confirm('Supprimer ce commentaire ?')">
                    @csrf
                    @method('DELETE')
                    <button class="action-link danger" style="font-size:0.8rem;width:auto;">Supprimer (admin)</button>
                </form>
            @endauth
        </div>
    </div>

    <div id="comment-body-{{ $comment->id }}">
        <p class="{{ $depth > 0 ? 'comment-reply-body' : 'comment-body' }}">{!! $comment->formattedBody() !!}</p>
    </div>

    @if($isOwner)
        <div class="edit-form" id="edit-{{ $comment->id }}" style="display: none;" hidden>
            <form method="POST" action="{{ route('comments.update', [$post, $comment]) }}" class="comment-submit-once">
                @csrf
                @method('PATCH')
                <textarea name="body" rows="3" class="form-input" style="margin-bottom:0.75rem;resize:vertical;" required>{{ $comment->body }}</textarea>
                <div style="display:flex;gap:0.5rem;">
                    <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.875rem;">Enregistrer</button>
                    <button type="button" class="btn-ghost" style="padding:0.5rem 1rem;font-size:0.875rem;" onclick="toggleEdit({{ $comment->id }})">Annuler</button>
                </div>
            </form>
        </div>
    @endif

    <div class="comment-actions">
        @auth('web')
            <button type="button" class="reply-btn" onclick="toggleReply({{ $comment->id }})">Répondre</button>
            <div class="reply-form" id="reply-{{ $comment->id }}" style="display: none;" hidden>
                <form method="POST" action="{{ route('comments.reply', [$post, $comment]) }}" class="comment-submit-once">
                    @csrf
                    <textarea name="body" rows="3" placeholder="Votre réponse… @prénom pour taguer"
                        class="form-input" style="margin-bottom:0.75rem;resize:vertical;" required></textarea>
                    <div style="display:flex;gap:0.5rem;">
                        <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.875rem;">Envoyer</button>
                        <button type="button" class="btn-ghost" style="padding:0.5rem 1rem;font-size:0.875rem;" onclick="toggleReply({{ $comment->id }})">Annuler</button>
                    </div>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="reply-btn" style="text-decoration:none;display:inline-block;">Répondre</a>
        @endauth
    </div>

    @php $children = $allComments->where('parent_id', $comment->id); @endphp
    @if($children->count() > 0)
        <div class="comment-replies">
            @foreach($children as $child)
                <x-comment-item :comment="$child" :post="$post" :all-comments="$allComments" :depth="$depth + 1" />
            @endforeach
        </div>
    @endif
</div>
