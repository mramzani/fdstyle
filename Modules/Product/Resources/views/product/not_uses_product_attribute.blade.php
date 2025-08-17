<!-- for create product attributes -->
<div class="card mb-4">
    <div class="card-body">

        <div id="attribute_section"></div>
        <hr>
        <button class="btn btn-sm btn-danger" type="button"
                id="add_product_attribute">@lang('product::products.add new attribute')</button>
    </div>
</div>
<script>
    //--- attributes added js
    let changeAttributeValues = (event, id) => {
        let valueBox = $(`select[name='attributes[${id}][value]']`);
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('attributes.get-values') }}',
            contentType: "application/json;charset=utf-8",
            data: JSON.stringify({
                name: event.target.value
            }),
            success: function (res) {
                valueBox.html(`
                            <option value="" selected>انتخاب کنید</option>
                            ${
                    res.data.map(function (item) {
                        return `<option value="${item}">${item}</option>`
                    })
                }
                        `);
            }
        });

    }

    $('#add_product_attribute').click(function (e) {
        let AttributeSection = $('#attribute_section');
        let id = AttributeSection.children().length;
        let attributes = $('#attributes').data('attributes');

        AttributeSection.append(
            createNewAtt({
                attributes,
                id
            })
        );
        $('.attribute-select').select2({tags: true});
    });

    let createNewAtt = ({attributes, id}) => {
        return `
            <div class="row" id="attribute-${id}">
            <div class="mb-0 col-md-5 col-xl-5 col-sm-12">
                                        <label for="attributes" class="form-label">عنوان ویژگی</label>
                                        <select class="form-select attribute-select" onchange="changeAttributeValues(event,${id})" name="attributes[${id}][name]" id="attributes">
                                            <option value="">انتخاب کنید</option>
                                            ${
            attributes.map(function (item) {
                return `<option value="${item}">${item}</option>`
            })
        }
                                        </select>
                                    </div>
                                    <div class="mb-0 col-md-5 col-xl-5 col-sm-12">
                                        <label for="category" class="form-label">مقدار ویژگی</label>
                                        <select name="attributes[${id}][value]" class="attribute-select form-control">
                                            <option value="">انتخاب کنید</option>
                                         </select>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12">
                                        <label for="category" class="form-label">@lang("dashboard::common.operation")</label>
                                        <div class="mt-1">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('attribute-${id}').remove()">@lang("dashboard::common.delete")</button>
                                        </div>
                                    </div>
            </div>`
    }

    //--- attributes added js
</script>
<!-- for product edit attributes -->
<div class="card mb-4">
    <div class="card-body">
        <div id="attribute_section">
            @foreach($product->attributes as $key => $attribute)
                <div class="row" id="attribute-{{ $loop->index }}">
                    <div class="mb-0 col-md-5 col-xl-5 col-sm-12">
                        <label for="attributes" class="form-label">عنوان ویژگی</label>
                        <select class="form-select attribute-select"
                                onchange="changeAttributeValues(event,{{ $loop->index }})"
                                name="attributes[{{ $attribute->id }}][name]" id="attributes">
                            <option value="">انتخاب کنید</option>
                            @foreach(\Modules\Product\Entities\Attribute::all() as $att)
                                <option value="{{ $att->name }}" {{ $att->name == $attribute->name ? 'selected' : '' }} >{{ $att->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0 col-md-5 col-xl-5 col-sm-12">
                        <label for="category" class="form-label">مقدار ویژگی</label>
                        <select name="attributes[{{ $attribute->id }}][value]" class="attribute-select form-control">
                            <option value="">انتخاب کنید</option>
                            @foreach($attribute->values as $value)
                                <option value="{{ $value->value }}" {{ $value->id == $attribute->pivot->value_id ? 'selected' : '' }}>{{ $value->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-xl-2 col-sm-12">
                        <label for="category"
                               class="form-label">@lang("dashboard::common.operation")</label>
                        <div class="mt-1">
                            <button type="button" class="btn btn-sm btn-danger"
                                    onclick="document.getElementById('attribute-${id}').remove()">@lang("dashboard::common.delete")</button>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <hr>
        <button class="btn btn-sm btn-danger" type="button"
                id="add_product_attribute">@lang('product::products.add new attribute')</button>
    </div>
</div>
<script>
    let changeAttributeValues = (event, id) => {
        let valueBox = $(`select[name='attributes[${id}][value]']`);
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('attributes.get-values') }}',
            contentType: "application/json;charset=utf-8",
            data: JSON.stringify({
                name: event.target.value
            }),
            success: function (res) {
                valueBox.html(`
                            <option value="" selected>انتخاب کنید</option>
                            ${
                    res.data.map(function (item) {
                        return `<option value="${item}">${item}</option>`
                    })
                }
                        `);
            }
        });
    }

    $('#add_product_attribute').click(function (e) {
        let AttributeSection = $('#attribute_section');
        let id = AttributeSection.children().length;
        let attributes = $('#attributes').data('attributes');

        AttributeSection.append(
            createNewAtt({
                attributes,
                id
            })
        );
        $('.attribute-select').select2({tags: true});
    });
    $('.attribute-select').select2({tags: true});

    let createNewAtt = ({attributes, id}) => {
        return `
            <div class="row" id="attribute-${id}">
            <div class="mb-0 col-md-5 col-xl-5 col-sm-12">
                                        <label for="attributes" class="form-label">عنوان ویژگی</label>
                                        <select class="form-select attribute-select" onchange="changeAttributeValues(event,${id})" name="attributes[${id}][name]" id="attributes">
                                            <option value="">انتخاب کنید</option>
                                            ${
            attributes.map(function (item) {
                return `<option value="${item}">${item}</option>`
            })
        }
                                        </select>
                                    </div>
                                    <div class="mb-0 col-md-5 col-xl-5 col-sm-12">
                                        <label for="category" class="form-label">مقدار ویژگی</label>
                                        <select name="attributes[${id}][value]" class="attribute-select form-control">
                                            <option value="">انتخاب کنید</option>
                                         </select>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12">
                                        <label for="category" class="form-label">@lang("dashboard::common.operation")</label>
                                        <div class="mt-1">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('attribute-${id}').remove()">@lang("dashboard::common.delete")</button>
                                        </div>
                                    </div>
            </div>`
    }
</script>
