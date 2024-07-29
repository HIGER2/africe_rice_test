@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'AfricaRice')
<img src="https://mycareer.africarice.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fafricarice.b93af9cc.webp&w=1080&q=75" class="logo" alt="AfricaRice Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
