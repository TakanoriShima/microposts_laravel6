@extends("layouts.app")

@section("content")
    <div class="row">
        <aside class="col-sm-4">
            
            {{-- ユーザ情報 --}}
            @include("users.card")
            
            {{-- ユーザ削除フォーム --}}
            @if (Auth::id() == $user->id)
                {!! Form::model($user, ["route" => ["users.destroy", $user->id], "method" => "delete"]) !!}
                    {!! Form::submit("削除", ["class" => "btn btn-danger"]) !!}
                {!! Form::close() !!}
            @endif
        </aside>
        <div class="col-sm-8">
            {{-- タブ --}}
            @include("users.navtabs")
            
            {{-- 投稿フォーム --}}
            @if (Auth::id() == $user->id)
                @include("microposts.form")
            @endif
            
            {{-- 投稿一覧 --}}
            @include("microposts.microposts")
        </div>
    </div>
@endsection