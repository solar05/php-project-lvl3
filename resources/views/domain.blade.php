@extends('layouts.app')
@section('title', 'Page Analyzer')
@section('navbar')
    @parent
@endsection
@section('content')
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">URL</th>
        <th scope="col">Created at</th>
        <th scope="col">Updated at</th>
    </tr>
    </thead>
    <tbody>
<tr>
    <th>{{ $domains[0]->id }}</th>
    <td>{{ $domains[0]->name }}</td>
    <td>{{ $domains[0]->created_at }}</td>
    <td>{{ $domains[0]->updated_at }}</td>
</tr>
    </tbody>
</table>
    @endsection

