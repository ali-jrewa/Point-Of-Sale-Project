@extends('layouts.app')

@section('title')
    {{ $data['title'] }}
@endsection

@section('style')
    @include('pdf._style')
@endsection

@section('content')
<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="pdf-report">

                @include('pdf._toolbar')


                <div class="header">

                    <h2>
                        {{ __('report/profit-loss.title') }}
                    </h2>

                    <p>
                        {{ __('report/profit-loss.generated_on') }}:
                        {{ $data['date'] }}
                    </p>

                </div>


                <p class="range">

                    {{ __('report/profit-loss.period') }}:
                    {{ $data['from'] }}

                    {{ __('report/profit-loss.to') }}

                    {{ $data['to'] }}

                </p>



                <div class="section-title">
                    {{ __('report/profit-loss.revenue') }}
                </div>


                <table>

                    <tr>

                        <th width="50%">
                            {{ __('report/profit-loss.gross_sales_revenue') }}
                        </th>

                        <td>
                            ${{ number_format($totalRevenue, 2) }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            {{ __('report/profit-loss.less_refunds') }}
                        </th>

                        <td class="text-danger">
                            -${{ number_format($totalRefunds, 2) }}
                        </td>

                    </tr>


                    <tr class="totals-row">

                        <td>
                            {{ __('report/profit-loss.net_revenue') }}
                        </td>

                        <td>
                            ${{ number_format($netRevenue, 2) }}
                        </td>

                    </tr>

                </table>




                <div class="section-title">
                    {{ __('report/profit-loss.cost_of_goods_sold') }}
                </div>



                <table>

                    <tr>

                        <th width="50%">
                            {{ __('report/profit-loss.total_purchases_cogs') }}
                        </th>

                        <td class="text-danger">
                            -${{ number_format($totalCogs, 2) }}
                        </td>

                    </tr>


                    <tr class="totals-row">

                        <td>
                            {{ __('report/profit-loss.gross_profit') }}
                        </td>


                        <td class="{{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            ${{ number_format($grossProfit, 2) }}
                        </td>

                    </tr>

                </table>




                <div class="section-title">

                    {{ __('report/profit-loss.operating_expenses') }}

                </div>



                <table>

                    <thead>

                        <tr>

                            <th>
                                {{ __('report/profit-loss.category') }}
                            </th>

                            <th>
                                {{ __('report/profit-loss.amount') }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @forelse($expensesByCategory as $category => $amount)

                        <tr>

                            <td>
                                {{ $category }}
                            </td>


                            <td>
                                ${{ number_format($amount, 2) }}
                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="2">
                                {{ __('report/profit-loss.no_expenses_recorded') }}
                            </td>

                        </tr>


                    @endforelse



                    <tr class="totals-row">

                        <td>
                            {{ __('report/profit-loss.total_expenses') }}
                        </td>


                        <td class="text-danger">
                            -${{ number_format($totalExpenses, 2) }}
                        </td>

                    </tr>


                    </tbody>

                </table>




                <div class="section-title">

                    {{ __('report/profit-loss.net_profit_loss') }}

                </div>



                <table>

                    <tr class="totals-row">


                        <td width="50%">

                            {{ __('report/profit-loss.net_profit_loss') }}

                            {{ $netProfit >= 0 
                                ? __('report/profit-loss.profit') 
                                : __('report/profit-loss.loss') 
                            }}

                        </td>


                        <td class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:14px;">

                            ${{ number_format(abs($netProfit), 2) }}

                        </td>


                    </tr>

                </table>


            </div>
        </div>
    </div>
</main>
@endsection
