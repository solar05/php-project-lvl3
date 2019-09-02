@extends('layouts.app')
@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">URL</th>
            <th scope="col">Created at</th>
            <th scope="col">Updated at</th>
            <th scope="col">Analysis status</th>
            <th scope="col">Status code</th>
            <th scope="col">Content length</th>
            <th scope="col">Header</th>
            <th scope="col">Content</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $domains->id }}</td>
            <td>{{ $domains->name }}</td>
            <td>{{ $domains->created_at }}</td>
            <td>{{ $domains->updated_at }}</td>
            <td>{{ $domains->state }}</td>
            <td>{{ $domains->status }}</td>
            <td>{{ $domains->content_length }}</td>
            <td>{{ $domains->header }}</td>
            <td>{{ $domains->content }}</td>
        </tr>
        </tbody>
    </table>
@endsection
