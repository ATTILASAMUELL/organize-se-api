@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Organize-se')
<img src="{{asset('assets/logo-organize-se.png')}}" class="logo" alt="Organize-se Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
