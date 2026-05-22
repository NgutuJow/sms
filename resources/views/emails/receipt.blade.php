@component('mail::message')
# Payment Received

Dear {{ $payment->student->first_name }} {{ $payment->student->last_name }},

We have received your payment of **{{ number_format($payment->amount, 2) }} {{ $payment->currency }}**.

**Receipt Details:**
- Receipt No: {{ $receipt->receipt_no }}
- Date: {{ $receipt->issued_at }}
- Payment Method: {{ ucfirst($payment->payment_method) }}
- Reference: {{ $payment->provider_ref }}

@if($payment->invoice)
**Invoice Details:**
- Invoice No: {{ $payment->invoice->reference_no }}
- Outstanding Balance: {{ number_format($payment->invoice->balance, 2) }} {{ $payment->currency }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
