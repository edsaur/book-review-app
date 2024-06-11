@props(['rating'])

@if ($rating)
@for ($i = 1; $i <=5; $i++)
   {{$i <= $rating ? '★' : '☆'}}
@endfor
@else
<p>
   No rating yet
</p>
@endif