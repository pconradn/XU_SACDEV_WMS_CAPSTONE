<table>

<thead>

<tr>
<th width="60%">DETAILS</th>
<th width="20%">AMOUNT</th>
<th width="20%">CHARGE ACCOUNT</th>
</tr>

</thead>

<tbody>

<tr>
<td><strong>{{ $project->title }}</strong></td>
<td></td>
<td></td>
</tr>

@foreach($items as $item)

<tr>

<td>{{ $item->particulars }}</td>

<td class="right">
₱ {{ number_format($item->amount,2) }}
</td>

<td>
{{ $chargeAccounts[$item->id] ?? '' }}
</td>

</tr>

@endforeach


<tr>

<td class="right"><strong>TOTAL</strong></td>

<td class="right">
₱ {{ number_format($total,2) }}
</td>

<td></td>

</tr>

</tbody>

</table>

<br>