@extends('front::layouts.app')
@section('title',$page->title)
@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 col-md-12 order-1 order-md-2">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="shadow-around">
                                <div class="px-3">
                                    <div class="blog-card single-blog">
                                        <div class="blog-card-title mb-3">
                                            <h2 class="text-right">
                                                <a href="#">{{ $page->title }}</a></h2>
                                        </div>

                                        <div class="blog-card-body">
                                            {!! $page->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- end Page Content -->
@endsection
