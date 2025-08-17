<footer class="main-footer">
    <div class="back-to-top">
        <div class="back-btn">
            <i class="far fa-chevron-up icon"></i>
            <span>برگشت به بالا</span>
        </div>
    </div>
    <div class="container">
        <div class="services row mb-2">
            <div class="col-md-3 col-sm-6 mb-md-0 mb-4">
                <div class="service-item">
                    <img src="{{ url('assets/front/images/services/fast-delivery.png') }}" alt="">
                    <div class="service-item-body">
                        <h6>تحویل سریع</h6>
                        <p>تحویل سریع | حداکثر ۴ روزه</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-md-0 mb-4">
                <div class="service-item">
                    <img src="{{ asset('assets/front/images/services/discount.png') }}" alt="">
                    <div class="service-item-body">
                        <h6>تضمین بهترین قیمت</h6>
                        <p>ما تضمین بهترین قیمت با بهترین کیفیت را به شما می‌دهیم</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-md-0 mb-4">
                <div class="service-item">
                    <img src="{{ asset('assets/front/images/services/help-desk.png') }}" alt="">
                    <div class="service-item-body">
                        <h6>پشتیبانی ۲۴ ساعته</h6>
                        <p>در صورت وجود هرگونه سوال یا ابهامی، با ما در تماس باشید</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-md-0 mb-4">
                <div class="service-item">
                    <img src="{{ asset('assets/front/images/services/debit-card2.png') }}" alt="">
                    <div class="service-item-body">
                        <h6>پرداخت آنلاین ایمن</h6>
                        <p>محصولات مدنظر خود را با خیال آسوده به صورت آنلاین خریداری کنید</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row my-3">
            <div class="col-md-3 col-sm-6">
                <div class="widget widget-links">
                    <h2 class="widget-title">دسته بندی کالاها</h2>
                    <ul class="widget-list">
                        @foreach(\Modules\Category\Entities\Category::where('parent_id',null)->get() as $category)
                            <li class="widget-list-item">
                                <a href="{{ route('front.category.list',$category->slug) }}" class="widget-list-link">{{ $category->title_fa }}</a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="widget widget-links">
                    <h2 class="widget-title">راهنمای خرید از {{ get_short_name() }}</h2>
                    <ul class="widget-list">
                        <li class="widget-list-item">
                            <a href="{{ purchase_guide() }}" class="widget-list-link">نحوه ثبت سفارش</a>
                        </li>
                        <li class="widget-list-item">
                            <a href="{{ shipping_methods() }}" class="widget-list-link">رویه ارسال سفارش</a>
                        </li>
                        <li class="widget-list-item">
                            <a href="{{ payment_methods() }}" class="widget-list-link">شیوه‌های پرداخت</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="widget widget-links">
                    <h2 class="widget-title">خدمات مشتریان</h2>
                    <ul class="widget-list">
                        <li class="widget-list-item">
                            <a href="{{ faq() }}" class="widget-list-link">سوالات مشتریان</a>
                        </li>
                        <li class="widget-list-item">
                            <a href="{{ return_policy() }}" class="widget-list-link">شرایط بازگشت کالا</a>
                        </li>
                        <li class="widget-list-item">
                            <a href="{{ terms_conditions() }}" class="widget-list-link">شرایط استفاده</a>
                        </li>
                        <li class="widget-list-item">
                            <a href="{{ privacy_policy() }}" class="widget-list-link">حریم خصوصی</a>
                        </li>
                        <li class="widget-list-item">
                            <a href="{{ order_tracking() }}" class="widget-list-link">پیگیری سفارش</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">

                <div class="widget">
                    <h2 class="widget-title">
                        {{ get_short_name() }} را در شبکه‌های اجتماعی دنبال کنید:
                    </h2>
                    <div class="social">
                        <ul>
                            <li>
                                <a href="{{ app(\Modules\Dashboard\Helper\Setting\GeneralSettings::class)->facebook_link }}" class="linkedin"><i class="fab fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="{{ app(\Modules\Dashboard\Helper\Setting\GeneralSettings::class)->twitter_link }}" class="twitter"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="{{ app(\Modules\Dashboard\Helper\Setting\GeneralSettings::class)->instagram_link }}" class="instagram"><i class="fab fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="{{ app(\Modules\Dashboard\Helper\Setting\GeneralSettings::class)->telegram_link }}" class="telegram"><i class="fab fa-telegram-plane"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row my-2">
            <div class="d-flex align-items-center justify-content-between py-4 flex-wrap">
                <div class="col-md-8 col-sm-12">
                    <p class="text-justify">{!! company()->desc !!}</p>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="d-flex align-items-center justify-content-center">

                        @if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->e_symbol_code != "")
                        <div class="el-trust-symbol d-flex justify-content-center align-items-center border my-2 mx-auto align-items-center p-2" style="border-radius: 15px">
                            {!! resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->e_symbol_code !!}
                        </div>
                        @endif

                        @if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->samandehi_code != "")
                        <div class="el-trust-symbol d-flex justify-content-center align-items-center border my-2 mx-auto align-items-center p-2" style="border-radius: 15px">
                                {!! resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->samandehi_code !!}
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row text-center p-4">
            <div class="col-12">
                <div class="copy-right" dir="rtl">
                    <p>{{ app(\Modules\Dashboard\Helper\Setting\GeneralSettings::class)->copy_right_text }}</p>
                    <span >
                        <a href="https://rinofy.ir" target="_blank" style="color: #0c1021" class="text-sm">
                            نرم‌افزار حسابداری و فروشگاه اینترنتی یکپارچه
                            <img src="https://rinofy.ir/wp-content/uploads/2024/02/rinofy_logo_website.png" width="90" height="32" alt="نرم افزار حسابداری فروشگاهی یکپارچه">
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
