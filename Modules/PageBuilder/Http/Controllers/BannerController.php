<?php

namespace Modules\PageBuilder\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PageBuilder\Entities\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banners= Banner::query()->orderBy('created_at')->paginate(10);

        return view('pagebuilder::banners.index',compact('banners'));
    }

    public function create()
    {
        return view('pagebuilder::banners.create');
    }

    public function store(Request $request)
    {
        $this->validateBanner($request);
        $data = [
            'name' => $request->input('name'),
            'key' => str_replace(' ','-',$request->input('key')),
            'status' => $request->input('status'),
            'type' => $request->input('banner_type'),
        ];
        $data = $this->makeBannerLink($request,$data);

        Banner::query()->create($data);

        return redirect()->route('dashboard.banners.create')->with('alertSuccess', 'بنر جدید با موفقیت ایجاد شد.');
    }

    public function edit(Banner $banner)
    {
        return view('pagebuilder::banners.edit',compact('banner'));
    }

    public function update(Request $request,Banner $banner)
    {
        $this->validateBanner($request);

        $data = [
            'name' => $request->input('name'),
            'key' => str_replace(' ','-',$request->input('key')),
            'status' => $request->input('status'),
            'type' => $request->input('banner_type'),
        ];

        $data = $this->makeBannerLink($request,$data);

        $banner->update($data);

        return redirect()->route('dashboard.banners.index')->with('alertSuccess','بنر با موفقیت بروزرسانی شد.');
    }

    public function destroy(Banner $banner)
    {
        //TODO:: handle destroy
    }

    private function validateBanner(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'key' => 'required',
            'status' => 'required',

            'banner_type' => 'required',

            'banner1x_1_url' => 'required_if:banner_type,banner1x',
            'banner1x_1_link' => 'required_if:banner_type,banner1x',

            'banner2x_1_url' => 'required_if:banner_type,banner2x',
            'banner2x_1_link' => 'required_if:banner_type,banner2x',
            'banner2x_2_url' => 'required_if:banner_type,banner2x',
            'banner2x_2_link' => 'required_if:banner_type,banner2x',

            'banner4x_1_url' => 'required_if:banner_type,banner4x',
            'banner4x_1_link' => 'required_if:banner_type,banner4x',
            'banner4x_2_url' => 'required_if:banner_type,banner4x',
            'banner4x_2_link' => 'required_if:banner_type,banner4x',
            'banner4x_3_url' => 'required_if:banner_type,banner4x',
            'banner4x_3_link' => 'required_if:banner_type,banner4x',
            'banner4x_4_url' => 'required_if:banner_type,banner4x',
            'banner4x_4_link' => 'required_if:banner_type,banner4x',
         ]);
    }

    private function makeBannerLink(Request $request,array $data)
    {
        if ($request->input('banner_type') == "banner1x") {
            $data['banner_1'] = serialize([
                'link' => $request->input('banner1x_1_link'),
                'img_url' => $request->input('banner1x_1_url'),
            ]);
        }
        elseif ($request->input('banner_type') == "banner2x") {
            $data['banner_1'] = serialize([
                'link' => $request->input('banner2x_1_link'),
                'img_url' => $request->input('banner2x_1_url')
            ]);

            $data['banner_2'] = serialize([
                'link' => $request->input('banner2x_2_link'),
                'img_url' => $request->input('banner2x_2_url')
            ]);
        }
        elseif ($request->input('banner_type') == "banner4x") {
            $data['banner_1'] = serialize([
                'link' => $request->input('banner4x_1_link'),
                'img_url' => $request->input('banner4x_1_url'),
            ]);
            $data['banner_2'] = serialize([
                'link' => $request->input('banner4x_2_link'),
                'img_url' => $request->input('banner4x_2_url'),
            ]);
            $data['banner_3'] = serialize([
                'link' => $request->input('banner4x_3_link'),
                'img_url' => $request->input('banner4x_3_url'),
            ]);
            $data['banner_4'] = serialize([
                'link' => $request->input('banner4x_4_link'),
                'img_url' => $request->input('banner4x_4_url'),
            ]);
        }
        return $data;
    }

}
