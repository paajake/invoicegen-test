<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{$invoice_num = time()}}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: small;
        }
        th{
            font-weight: bold;
            font-size: large;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: large;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information {
            background-color: #3fa4ff;
            color: #FFF;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 40%;">
                <h3>{{$client->name}}</h3>
                <pre>
{{$client->email}}
{{$client->phone}}
<br /><br />
Date: {{\Carbon\Carbon::today()->format("Y-m-d")}}
Identifier: {{$invoice_num}}
Period: {{$period}}
</pre>


            </td>
            <td align="center">
                <img src="images/logo/logo.png" alt="InvoiceGen" width="64" class="logo"/>
            </td>
            <td align="right" style="width: 40%;">

                <h3>InvoiceGen</h3>
                <pre>
                    https://invoicegen.test

                    No 12 SomeWhere St
                    Tuabodom City
                    Ghana
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3>Invoice #{{$invoice_num}}</h3>
    <table width="100%">
        <thead>
        <tr>
            <th align="left">Employee</th>
            <th align="left">Hours</th>
            <th align="left">Unit Price</th>
            <th align="left">Cost</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($billables as $billable)
            <tr>
                <td>{{$billable->lawyer}}</td>
                <td> {{$billable->hours}}</td>
                <td>{{number_format($billable->unit_rate, 2)}}</td>
                <td align="left">{{number_format($billable->total_rate,2)}}</td>
            </tr>
        @endforeach

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>

        <tfoot>
        <tr>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td align="left">Total</td>
            <td align="left" class="gray">GHS {{number_format($invoice_sum,2)}}</td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                ... Your Partner for Justice.
            </td>
        </tr>

    </table>
</div>
</body>
</html>
