@foreach($subcategories as $subcategory)
    <ul style="list-style: none">
        <li>
            <input type="checkbox" id="label{{ $subcategory->id }}" name="categories[]" value="{{$subcategory->id}}" {{ (in_array($subcategory->id, old('categories', []))) ? "checked" : "" }}>
            <label for="label{{ $subcategory->id }}">{{$subcategory->title}}</label>
        </li>
        @if(count($subcategory->children))
            @include('backend.category.subs',['subcategories' => $subcategory->children])
        @endif
    </ul>
@endforeach
