@extends('layouts.app')
@section('content')
    <div class="jumbotron">
        <h1 class="display-4" align="center">Page Analyzer</h1>
        <p class="lead" align="center">Web-application to analyze sites for SEO suitability. Please input site URL that you want to analyze.</p>
        <hr class="my-4">
        <p class="lead"></p>
            <form method="post" action="/domains">
            <input type="text" id="domain" name="domain" class="form-control mb-3" placeholder="Site URL" required>
            <button class="btn btn-primary btn-lg btn-block" type="submit" role="button">Analyze</button>
            </form>
        @isset($errors)
            @foreach($errors as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
        @endisset
    </div>
@endsection


