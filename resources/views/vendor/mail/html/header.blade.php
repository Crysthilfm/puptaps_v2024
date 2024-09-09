<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
{{-- <img src="{{ public_path('img/pupLogo.png') }}" class="logo"  style="height: 100px;"> --}}
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
