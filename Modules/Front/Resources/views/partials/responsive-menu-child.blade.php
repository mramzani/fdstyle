<ul>
    @foreach($menus as $menu)
        <li @if(count($menu->children) > 0) class="has-children" @endif>
            <a href="{{ $menu->url }}">{{ $menu->title }}</a>
            @if(count($menu->children) > 0)
                @include('front::partials.responsive-menu-child',['menus' => $menu->children])
            @endif
        </li>
    @endforeach()
</ul>
