<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<html>
    <head>
        <title>@yield('title')</title>
    </head>
    <body>
        @section('navbar')
        <nav class="navbar navbar-dark bg-dark">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/domains">All Domains</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </nav>
        @show
            @yield('content')
        @section('table')
            @if (isset($domains))
            <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">URL</th>
                <th scope="col">Created at</th>
                <th scope="col">Updated at</th>
                <th scope="col">Analysis status</th>
                <th scope="col">Status code</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($domains as $domain)
            <tr>
                <th>{{ $domain->id }}</th>
                <td><a href="{{ route('domain', ['id' => $domain->id]) }}">{{ $domain->name }}</a></td>
                <td>{{ $domain->created_at }}</td>
                <td>{{ $domain->updated_at }}</td>
                <td>{{ $domain->state }}</td>
                <td>{{ $domain->status }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
            @if(count($domains) > 1)
            {!! $domains->render() !!}
            @endif
            @endif
        @show
    </body>
</html>
