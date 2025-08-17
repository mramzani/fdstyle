<div>
    <!-- Warehouse list -->
    <div class="card overflow-hidden">
        <div class="card-body">
            <div class="card-header header-elements">
                <h4 class="d-flex align-items-center">
                                    <span class="badge bg-label-secondary p-2 rounded me-3">
                                      <i class="bx bx-cube bx-sm"></i>
                                    </span>
                    @lang('unit::units.units list')
                </h4>

                <div class="card-header-elements ms-auto">
                    @can('units_create')
                        <button class="btn btn-primary" type="button" wire:click.prevent="showAddUnitOffCanvas"  >
                            @lang('unit::units.add new unit')
                        </button>
                    @endcan
                </div>
            </div>
            @include('dashboard::partials.alert')

            @can('units_view')
                @if($units->isEmpty())
                    <div class="alert alert-warning alert-dismissible d-flex align-items-center" role="alert">
                        <i class="bx bx-xs bx-message-alt-error me-2"></i>
                        @lang('unit::units.Unfortunately, no unit has been defined in the system yet!')
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table warehouse-list">
                            <thead class="table-dark">
                            <tr>
                                <th>@lang('dashboard::common.name')</th>
                                <th>@lang('dashboard::common.display_name')</th>
                                <th>@lang('dashboard::common.operation')</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            @foreach($units as $unit)
                                <tr>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->short_name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @can('units_edit')
                                                <button
                                                    class="btn btn-sm btn-outline-primary btn-group"
                                                    wire:click.prevent="showEditUnitOffCanvas({{ $unit }})">@lang('dashboard::common.edit')</button>
                                            @endcan

                                            @can('units_delete')
                                                    <button type="submit" wire:click.prevent="deleteUnit({{ $unit }})" class="btn btn-sm
                                                    btn-outline-danger">
                                                        @lang('dashboard::common.delete')
                                                    </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endcan
        </div>

    </div>
    <!-- /Warehouse list -->

    <div class="offcanvas offcanvas-end" tabindex="-1" id="addUnitOffCanvas"
         aria-labelledby="addUnitOffCanvasLabel"  wire:ignore.self>
        <div class="offcanvas-header">
            <h5 id="addUnitOffCanvasLabel" class="offcanvas-title">
                @if($showEditModal)
                    <span>ویرایش واحد شمارش</span>
                @else
                    <span>افزودن واحد شمارش جدید</span>
                @endif
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
        </div>

        <div class="offcanvas-body my-3 mx-0 flex-grow-0">

            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateUnit' : 'createUnit' }}">
                <div class="mb-3">
                    <label class="form-label" for="name">@lang('dashboard::common.name')</label>
                    <input type="text" wire:model.defer="unit.name" class="form-control
                        @error('name') border-danger @enderror"
                           id="name" placeholder="@lang('dashboard::common.name')">
                    @error('name')
                    <div class="d-block invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="short_name">@lang('dashboard::common.display_name')</label>
                    <input type="text" wire:model.defer="unit.short_name" class="form-control
                        @error('short_name') is-invalid @enderror"
                           id="short_name"
                           placeholder="@lang('dashboard::common.display_name')">
                    @error('short_name')
                    <div class="d-block invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mb-2 d-grid w-100">
                    @if($showEditModal)
                        <span>@lang('unit::units.update unit')</span>
                    @else
                        <span>@lang('unit::units.add new unit')</span>
                    @endif

                </button>
                <button type="button" class="btn btn-label-secondary d-grid w-100"
                        data-bs-dismiss="offcanvas">
                    @lang('dashboard::common.cancel')
                </button>
            </form>
        </div>
    </div>
</div>
