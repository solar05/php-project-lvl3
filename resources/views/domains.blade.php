@extends('layouts.app')
@section('content')
    @isset($domains)
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
            {{ $domains->render() }}
    @endisset
@endsection
