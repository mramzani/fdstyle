@foreach($menus->chunk(3) as $chunks)
    @foreach($chunks as $menu)
        <div class="ri-col">
            <section>
                <h2>{{ $menu->title }}</h2>
                @include('front::partials.mega-menu-child-li',['menus' => $menu->children])
            </section>
        </div>
    @endforeach
@endforeach
