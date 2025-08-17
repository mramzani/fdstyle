<?php

namespace Modules\PageBuilder\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PageBuilder\Entities\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $pages = Page::query()->paginate(10);
        return view('pagebuilder::pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pagebuilder::pages.create');
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
            'slug' => 'required|persian_not_accept',
            'description' => 'required',
            'status' => 'required',
        ]);

        $page = Page::create([
            'title' => $request->input('title'),
            'slug' => Common::makeSlug($request->input('slug'),$request->input('slug')),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('dashboard.page.edit',$page->id);
    }

    /**
     * @param Page $page
     *
     */
    public function show(Page $page)
    {
        return $page->status == Page::STATUS['draft']
        ?  abort(404)
        :   view('pagebuilder::pages.show',compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Page $page
     * @return Renderable
     */
    public function edit(Page $page)
    {
        return view('pagebuilder::pages.edit',compact('page'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Page $page
     * @return RedirectResponse
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|persian_not_accept|unique:pages,slug,'.$page->id,
            'description' => 'required',
            'status' => 'required',
        ]);
        $page->update([
            'title' => $request->input('title'),
            'slug' => Common::makeSlug($request->input('slug'),$request->input('slug')),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('dashboard.page.index')->with('alertSuccess','صفحه مورد نظر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Page $page
     * @return Renderable
     */
    public function destroy(Page $page)
    {
        //
    }
}
