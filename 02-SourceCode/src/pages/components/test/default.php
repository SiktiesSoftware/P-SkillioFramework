N°
@if($i % 2 == 0)
<span style="color: blue;">{{ $i }}</span>
@elseif($i === 3)
<span style="color: green;">{{ $i }}</span>
@elseif($i > 5)
<span style="color: orange;">{{ $i }}</span>
@else
<span style="color: red;">{{ $i }}</span>
@endif


<br>
{{ $i > 3 ? "Greater than 3" : "Less than or equal to 3" }}
<br>
@child.name="content"

