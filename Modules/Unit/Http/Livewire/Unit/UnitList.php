<?php

namespace Modules\Unit\Http\Livewire\Unit;

use App\Services\Common;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Modules\Unit\Entities\Unit;
use Throwable;

class UnitList extends Component
{


    public $unit = [];
    public $unitModel;
    public $more = [
        'operator' => 'multiply',
        'operator_value' => '1',
        'is_deletable' => true
    ];

    protected $listeners = ['deleteConfirmed' => 'handleDeleteUnit'];

    public $showEditModal = false;

    public function render()
    {
        $units = Unit::latest()->paginate(25);
        return view('unit::livewire.unit.unit-list', [
            'units' => $units
        ]);
    }

    public function showAddUnitOffCanvas()
    {
        $this->unit = [];
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Unit'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function createUnit()
    {
        $validatedData = $this->validateUnit();
        \DB::beginTransaction();
        try {
            Unit::create(array_merge($validatedData, $this->more));
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error creating Unit: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error creating Unit'
            ]);
        }
        $this->dispatchBrowserEvent('hide-offCanvas', [
            'message' => trans('unit::units.New unit successfully created.')
        ]);

    }

    public function showEditUnitOffCanvas(Unit $unit)
    {
        $this->showEditModal = true;
        $this->unitModel = $unit;
        $this->unit = $unit->toArray();
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Unit'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function updateUnit()
    {
        $validatedData = $this->validateUnit();
        \DB::beginTransaction();
        try {
            $this->unitModel->update($validatedData);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Updating Unit: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error Updating Unit'
            ]);
        }

        $this->dispatchBrowserEvent('hide-offCanvas', [
            'message' => trans('unit::units.Unit has been successfully updated.')
        ]);
    }

    public function deleteUnit(Unit $unit)
    {
        $this->unitModel = $unit;
        $this->dispatchBrowserEvent('confirm-delete');
    }

    public function handleDeleteUnit()
    {
        $this->unitModel->delete();
        $this->dispatchBrowserEvent('Deleted', ['message' => trans('unit::units.Unit was successfully removed.')]);

    }

    /**
     * Validate Unit for STORE and UPDATE
     * @return array
     */
    private function validateUnit(): array
    {
        return Validator::make($this->unit, [
            'name' => 'required',
            'short_name' => 'required',
        ])->validate();
    }
}
