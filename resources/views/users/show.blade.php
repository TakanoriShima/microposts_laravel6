@extends("layouts.app")

@section("content")
    <div class="row">
        <aside class="col-sm-4">
            {{-- ユーザ情報 --}}
            @include("users.card")
        </aside>
        <div class="col-sm-8">
            {{-- タブ --}}
            @include("users.navtabs")
            @if (Auth::id() == $user->id)
                {{-- 投稿フォーム --}}
                @include("microposts.form")
            @endif
                {{-- 投稿一覧 --}}
                @include("microposts.microposts")
        </div>
    </div>
    
    @if (Auth::id() == $user->id)
        {{-- ユーザ削除フォーム --}}
        {!! Form::model($user, ["route" => ["users.destroy", $user->id], "method" => "delete"]) !!}
            {!! Form::submit("削除", ["class" => "btn btn-danger"]) !!}
        {!! Form::close() !!}
    @endif
@endsection