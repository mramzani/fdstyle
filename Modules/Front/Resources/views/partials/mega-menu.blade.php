<nav class="nav-wrapper">
    <ul class="ri-menu-items">
        @foreach(\Modules\Menu\Entities\Menu::where('parent_id',null)->orderBy('id','asc')->get() as $menu)
            <li class="ri-mega-menu-li"><a href="{{ $menu->url }}" class="ri-menu-item">{{ $menu->title }}</a>
                @if(count($menu->children) > 0)
                    <div class="ri-mega-menu">
                        <div class="ri-content">
                            @include('front::partials.mega-menu-child',['menus' => $menu->children])
                            {{--<div class="ri-col img-col d-none">
                                <section>
                                    <a href="#" class="img-wrapper">
                                <span class="img">
                                    <img src="{{ asset('assets/front/images/mega-menu/amazing-offer.jpg') }}" alt="">
                                </span>
                                    </a>
                                </section>
                            </div>--}}
                        </div>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
