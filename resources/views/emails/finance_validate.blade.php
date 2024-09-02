@component('mail::message')

Dear finance and RH,

The departure date for Bouaké of the **{{ $data->employees->firstName." ".$data->employees->lastName }}** staff is scheduled for **{{$data->depart_date}}**.

The taking up of office is for the **{{$data->taking_date}}**.
Please prepare all the necessary administrative documents.

Below are the details of the compensation


**Travel Details**

Reqest N° {{$data->request_number}}
{{-- {{$data->marital_status == "yes" ? 10 000 : 0}} --}}
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Description</th>
            <th align="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Travel with Family <br>
               (Staff=10 000) +(spouse= {{$data->marital_status == 'yes' ? '10 000' : '0'}}) + ({{$data->number_child}} child * 10 000)
            </td>
            <td align="right">{{number_format($data->total_t_w_f, 0, ',', ' ')}} XOF</td>
        </tr>
        <tr>
            <td>Family initial accommodation <br>
               ({{$data->room}} Rooms x 15 000) x 7 days
            </td>
            <td align="right">{{number_format($data->total_f_i_a, 0, ',', ' ')}} XOF</td>
        </tr>
        <tr>
            <td>Personal effect Transportation</td>
            <td align="right">{{number_format($data->total_p_e_t, 0, ',', ' ')}} XOF</td>
        </tr>
        <tr>
            <td>Unforseen</td>
            <td align="right">{{number_format($data->total_u, 0, ',', ' ')}} XOF</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td align="right"><strong>{{number_format($data->total_amount, 0, ',', ' ')}} XOF</strong></td>
        </tr>
    </tbody>
</table>



Thank you for your attention.

{{ config('app.name') }}

@endcomponent
