@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            <li class="media mb-3">
                {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                <img class="mr-2 rounded" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                    </div>
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                        
                    </div>
                    <div class="d-flex justify-content-start">
                        <div>
                            {{-- いいねボタンのフォーム --}}
                            @if (Auth::user()->is_favorite($micropost->id))
                                {{-- いいねを解除する --}}
                                {!! Form::open(["route" => ["favorites.unfavorite", $micropost->id], "method" => "delete"]) !!}
                                    {!! Form::submit("unfavorite", ["class" => "btn btn-light btn-sm"]) !!}
                                {!! Form::close() !!}
                                
                            @else
                                {{-- いいね --}}
                                {!! Form::open(["route" => ["favorites.favorite", $micropost->id]]) !!}
                                    {!! Form::submit("favorite", ["class" => "btn btn-info btn-sm"]) !!}
                                {!! Form::close() !!}
                            @endif
                        </div>
                        <div>
                            {{-- 投稿削除ボタンのフォーム --}}
                            @if (Auth::id() == $micropost->user_id)
                                {!! Form::open(["route" => ["microposts.destroy", $micropost->id], "method" => "delete"]) !!}
                                    {!! Form::submit("Delete", ["class" => "btn btn-danger btn-sm"]) !!}
                                {!! Form::close() !!}
                            @endif
                        </div>
                        
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif