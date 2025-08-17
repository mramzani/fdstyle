<?php
namespace Modules\Product\View\Components;
use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Product\Entities\Product;

class ProductAlert extends Component
{
    public int $count = 0;
    public int $variable = 0;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($count,$variable)
    {
        $this->count = $count;
        $this->variable = $variable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('product::components.product-alert');
    }
}
