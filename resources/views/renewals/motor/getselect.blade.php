<div style="position: absolute; right:130px; top: 40px;">
    <select name="filter" class="browser-default" id="filter">

        
        @foreach ( $elements as $element )
        <option value="{{$element->$val}}">{{$element->$val}}</option>
        @endforeach
    </select>
</div>
