<ul class="d-flex flex-column">
    @php
        $color = substr(md5(rand()), 0, 6)
    @endphp
    @for($i=0;$i<count($categories);$i++)
        <li class="list-group-item border-0">
            <div class="h-px-40 bg-gray
             d-flex justify-content-between align-items-start">
                <div id="headingCollapse{{ $categories[$i]['id'] }}"
                     data-bs-toggle="collapse"
                     style="border-left: 2px solid {{'#'.$color}}"
                     class="w-75 h-100 p-2 "
                     data-bs-target="#collapse-{{ $categories[$i]['id'] }}"
                     aria-expanded="false"
                     aria-controls="collapse-{{ $categories[$i]['id'] }}">
                    <span class="badge bg-secondary">
                        {{ $categories[$i]['title_fa'] }}
                    </span>
                </div>
                <div class="p-2 d-inline-block text-nowrap">
                    <a href="{{ route('front.category.list',$categories[$i]['slug']) }}" target="_blank"
                       class="btn btn-sm btn-outline-secondary link-gray collapse-item">صفحه دسته‌بندی</a>
                    @can('categories_edit')
                        <a href="{{ route('categories.edit',$categories[$i]['id']) }}"
                           class="btn btn-sm btn-primary link-gray collapse-item">@lang('dashboard::common.edit')</a>
                    @endcan
                    @can('categories_delete')
                        <form id="deleteConfirmCategory-{{ $categories[$i]['id'] }}" class="btn-group"
                              action="{{ route('categories.destroy',$categories[$i]['id']) }}"
                              method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger delete-category"
                                    data-id="{{ $categories[$i]['id'] }}">@lang('dashboard::common.delete')</button>
                        </form>
                    @endcan
                </div>
                {{-- <a href="#" onclick="document.getElementById('deleteCategory-'+ {{ $categories[$i]['id'] }}).submit();"
                       target="_blank" class="badge bg-primary link-gray delete-category"></a>--}}
            </div>
        </li>

        @if(!empty($categories[$i]['child']))
            <div id="collapse-{{ $categories[$i]['id'] }}"
                 role="tabpanel"
                 aria-labelledby="headingCollapse{{ $categories[$i]['id'] }}"
                 class="collapse">
                @include('category::category.common.collapse-group',['categories'=> $categories[$i]['child']])
            </div>
        @endif
    @endfor

</ul>
