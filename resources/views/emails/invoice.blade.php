@component('mail::message')
# New Invoice Generated

Dear {{ $invoice->student->first_name }} {{ $invoice->student->last_name }},

A new invoice has been generated for school fees.

**Invoice Details:**
- Invoice No: {{ $invoice->reference_no }}
- Academic Year: {{ $invoice->academic_year }}
- Total Amount: **{{ number_format($invoice->total_amount, 2) }} TZS**
- Due Date: {{ $invoice->due_date }}

Please log in to the portal to view details and make payment.

@component('mail::button', ['url' => route('parent.finance.pay', $invoice->id)])
Pay Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
