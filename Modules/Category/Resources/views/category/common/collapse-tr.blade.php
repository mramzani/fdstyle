<tr >
    @for($i=0;$i<count($categories);$i++)
        <td id="headingCollapse{{ $categories[$i]['id'] }}"
            data-bs-toggle="collapse"
            role="button"
            data-bs-target="#collapse-{{ $categories[$i]['id'] }}"
            aria-expanded="false"
            aria-controls="collapse{{ $categories[$i]['id'] }}">
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-md me-3">
                        <img class="rounded" src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}">
                    </div>
                </div>
                <div class="d-flex flex-column">
                        <span
                            class="text-body text-truncate"><span
                                class="fw-semibold">{{ $categories[$i]['name'] }}</span>
                        </span>
                </div>
            </div>
        </td>
        <td>slug</td>
        <td>1</td>
        <td>
            <div class="d-inline-block text-nowrap">
                <a href="#" class="btn btn-sm btn-icon">
                    <i class="bx bx-edit"></i></a>

                <form action="#"
                      id="deleteBrandConfirm-1"
                      method="post" class="btn-group">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-icon
                                                        delete-category"
                            data-id="1"><i
                            class="bx bx-trash"></i>
                    </button>
                </form>


            </div>
        </td>

        @if(!empty($categories[$i]['child']))
            <tbody  id="collapse-{{ $categories[$i]['id'] }}"
                  role="tabpanel"
                  aria-labelledby="headingCollapse{{ $categories[$i]['id'] }}"
                  class="collapse">
                @include('category::category.collapse-tr',['categories'=> $categories[$i]['child']])
            </tbody>
        @endif
    @endfor
</tr>

