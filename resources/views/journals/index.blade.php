@extends('layouts.app')

@section('content')
<h4>Journal Entries</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Account</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($journals as $entry)
        <tr>
            <td>{{ $entry->entry_date }}</td>
            <td>{{ $entry->type }}</td>
            <td>{{ $entry->account }}</td>
            <td>{{ $entry->amount }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
