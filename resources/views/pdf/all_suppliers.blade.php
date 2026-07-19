@extends('layouts.app')

@section('title')
    {{ __('report/all_supplier.title') }}
@endsection

@section('style')

    @include('pdf._style')

    <style>
        .page-break { page-break-after: always; }
        .page-break:last-child { page-break-after: avoid; }
    </style>

@endsection


@section('content')


<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="pdf-report">
                @include('pdf._toolbar')


    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>{{ __('report/all_supplier.generated_on') }}: {{ $data['date'] }}</p>
    </div>

    @foreach($supplierChunks as $chunkIndex => $chunk)
        <div class="page-break">
            <h2> {{ __('report/all_supplier.supplier_directory') }} - {{ __('report/all_supplier.page') }} {{ $chunkIndex + 1 }} </h2>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('report/all_supplier.id') }}</th>
                        <th>{{ __('report/all_supplier.name') }}</th>
                        <th>{{ __('report/all_supplier.company') }}</th>
                        <th>{{ __('report/all_supplier.phone') }}</th>
                        <th>{{ __('report/all_supplier.email') }}</th>
                        <th>{{ __('report/all_supplier.address') }}</th>
                        <th>{{ __('report/all_supplier.tax_number') }}</th>
                        <th>{{ __('report/all_supplier.status') }}</th>
                        <th>{{ __('report/all_supplier.created_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chunk as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
                            <td>{{ $supplier->company_name ?? __('report/all_supplier.na') }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email ?? __('report/all_supplier.na') }} </td>
                            <td>{{ $supplier->address ?? __('report/all_supplier.na') }} </td>
                            <td>{{ $supplier->tax_number ?? __('report/all_supplier.na') }} </td>
                            <td>{{ __('report/all_supplier.' . strtolower($supplier->status->value)) }}</td>
                            <td>{{ $supplier->created_at->format('Y-m-d H:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

        </div>
        </div>
    </div>
</main>

@endsection
