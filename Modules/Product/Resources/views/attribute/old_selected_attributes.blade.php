<div class="row my-1">
    <div class="col-12 col-lg-4 my-1">
        <input type="hidden" name="choice_no[]" id="attribute_id_{{$attribute->id}}" value="{{$attribute->id}}">
        <div class=" mb-25">
            <input class="form-control" width="40%" name="choice[]" type="text"
                   value="{{ $attribute->title }}" readonly></div>
    </div>
    <div class="col-10 col-lg-7 my-1">
        <div class="mb-25">
            <select name="choice_options_{{ $attribute->id }}[]"
                    id="choice_options_{{ $attribute->id }}"
                    class="choice_options form-control mb-15" multiple>
                @foreach($attribute->allowedValuesForVariant($product) as $key => $item)
                    <option value="{{ $item->valuable->id }}">{{ $item->valuable->title }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="col-2 col-lg-1 text-center">
        <a class="btn cursor_pointer attribute_remove"><i class='bx bx-trash'></i></a>
    </div>
</div>
