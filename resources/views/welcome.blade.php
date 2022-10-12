@extends("layouts.app")

@section("content")
    <div class="center jumbotron">
        <div class="text-center">
            <H1>Welcome to the Microposts</H1>
            
            {{-- ユーザ登録ページへのリンク --}}
            {!! link_to_route("signup.get", "Sign up now!", [], ["class" => "btn btn-lg btn-primary"]) !!}
        </div>
    </div>

@endsection()