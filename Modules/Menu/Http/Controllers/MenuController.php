<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Menu\Entities\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $menus = Menu::query()->with('children')->orderBy('id','asc')->get();
        $menus = $this->makeJson($menus);

        return view('menu::index', compact('menus'));
    }

    private function makeJson($menus, $value = null)
    {
        return collect($menus)->where('parent_id', $value)->map(function ($menu) {
            return [
                'id' => $menu->id,
                'title' => $menu->title,
                'menu_url' => $menu->url,
                'children' => $this->makeJson($menu->children, $menu->id),
            ];
        })->toArray();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        Menu::query()->delete();

        $request->validate([
            'json' => 'required',
        ]);

        $menus = json_decode($request->input('json'));


        foreach ($menus as $menu) {
            $this->storeMenu($menu);
        }

        return redirect()->route('dashboard.menus.index');

    }


    private function storeMenu($menu, $parent = null)
    {
         $newMenu = Menu::create([
            'title' => $menu->title,
            'url' => $menu->menu_url ?? null,
            'parent_id' => $parent,
        ]);

        if (property_exists($menu,'children')){
            $this->makeMenu($menu,$newMenu->id);
        }
    }

    private function makeMenu($menu,$parent = null)
    {
        foreach ($menu->children as $child){
            $this->storeMenu($child,$parent);
        }
    }


}
