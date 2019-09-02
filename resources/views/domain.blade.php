@extends('layouts.app')
@section('title', 'Page Analyzer')
@section('navbar')
    @parent
    @section('table')
    @stop
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
        <tr>
            <th>{{ $domains[0]->id }}</th>
            <td>{{ $domains[0]->name }}</td>
            <td>{{ $domains[0]->created_at }}</td>
            <td>{{ $domains[0]->updated_at }}</td>
            <td>{{ $domains[0]->state }}</td>
            <td>{{ $domains[0]->status }}</td>
        </tr>
        </tbody>
    </table>
@endsection


