<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\AttributeGroup;

class AttributeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $attribute_groups = AttributeGroup::query()->paginate(15);

        return view('product::attribute-group.index',compact('attribute_groups'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        AttributeGroup::create([
            'title' => $request->input('title'),
        ]);
        return back()->with('alertSuccess' , 'گروه ویژگی با موفقیت ایجاد شد.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param AttributeGroup $attribute_group
     * @return Renderable
     */
    public function edit(AttributeGroup $attribute_group)
    {
        return view('product::attribute-group.edit',compact('attribute_group'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param AttributeGroup $attribute_group
     * @return RedirectResponse
     */
    public function update(Request $request, AttributeGroup $attribute_group)
    {

        $request->validate([
            'title' => 'required|string',
            //'category_id' => 'required|string',
        ]);
        $attribute_group->update([
            'title' => $request->input('title'),
            //'category_id' => $request->input('category_id'),
        ]);
        return back()->with('alertSuccess','گروه ویژگی با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param AttributeGroup $attribute_group
     * @return RedirectResponse
     */
    public function destroy(AttributeGroup $attribute_group)
    {
        $attribute_group->delete();

        return back()->with('alertSuccess','گروه ویژگی با موفقیت حذف شد.');
    }

    public function addAttribute(Request $request,AttributeGroup $attribute_group)
    {
        $validData = $request->validate([
            'attributes' => 'array',
        ]);

        $attributes = collect($validData['attributes']);

        $attribute_group->attributes()->update([
            'group_id' => null
        ]);

        //$attribute_group->attributes()->detach();

        $attributes->each(function ($item) use ($attribute_group) {
            if (!array_key_exists('name', $item)) return back()->with('alertWarning', 'عنوان ویژگی نباید خالی باشد.');

            $attr = Attribute::firstOrCreate([
                'name' => $item['name'],
            ]);
            $attr->is_filterable = array_key_exists('filterable', $item) ? 1 : 0;
            $attr->group_id = $attribute_group->id;
            $attr->save();

            //$attr->groups()->attach($attribute_group->id);
        });

        return back()->with('alertSuccess','با موفقیت بروزرسانی شد.');
    }
}
