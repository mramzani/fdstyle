@if($categoriesBreadcrumb)
    @include('front::product.parent_categories', ['categoriesBreadcrumb' => $categoriesBreadcrumb->parentsCategories])
    <a href="{{ route('front.category.list',$categoriesBreadcrumb->slug) }}">{{ $categoriesBreadcrumb->title_fa }}</a>
@endif
