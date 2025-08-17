<ul class="ri-mega-links">
    @foreach($menus as $menu)
        <li><a href="{{ $menu->url }}" class="text-muted">{{ $menu->title }}</a></li>
    @endforeach
</ul>
