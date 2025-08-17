<?php

namespace App\Services;

use App\Models\PaymentMode;
use Modules\Dashboard\Entities\Company;
use Modules\Product\Entities\Color;
use Modules\Product\Entities\Size;
use Modules\Product\Entities\Variant;
use Modules\Role\Entities\Permission;
use Modules\Role\Entities\Role;
use Modules\Unit\Entities\Unit;

class AppSeeder
{

    private static array $permissionsArray = [
        //Dashboard
        'dashboard_view_menu' => [
            'name' => 'dashboard_view_menu',
            'display_name' => 'مشاهده منو داشبورد'
        ],

        'people_view_menu' => [
            'name' => 'people_manage',
            'display_name' => 'مشاهده مدیریت افراد'
        ],

        'products_view_menu' => [
            'name' => 'products_manage_menu',
            'display_name' => 'مشاهده منو محصولات'
        ],
        'setting_view_menu' => [
            'name' => 'setting_view_menu',
            'display_name' => 'مشاهده منو تنظیمات'
        ],

        'logout_menu' => [
            'name' => 'logout_menu',
            'display_name' => 'منو خروج'
        ],

        // Brand
        'brands_view' => [
            'name' => 'brands_view',
            'display_name' => 'مشاهده برندها'
        ],
        'brands_create' => [
            'name' => 'brands_create',
            'display_name' => 'ایجاد برند'
        ],
        'brands_edit' => [
            'name' => 'brands_edit',
            'display_name' => 'ویرایش برند'
        ],
        'brands_delete' => [
            'name' => 'brands_delete',
            'display_name' => 'حذف برند'
        ],

        // Category
        'categories_view' => [
            'name' => 'categories_view',
            'display_name' => 'مشاهده دسته بندی‌ها'
        ],
        'categories_create' => [
            'name' => 'categories_create',
            'display_name' => 'ایجاد دسته‌بندی '
        ],
        'categories_edit' => [
            'name' => 'categories_edit',
            'display_name' => 'ویرایش دسته‌بندی '
        ],
        'categories_delete' => [
            'name' => 'categories_delete',
            'display_name' => 'حذف دسته‌بندی '
        ],

        // Attribute
        'attributes_view' => [
            'name' => 'attributes_view',
            'display_name' => 'مشاهده ویژگی‌ها'
        ],
        'attributes_create' => [
            'name' => 'attributes_create',
            'display_name' => 'ایجاد ویژگی '
        ],
        'attributes_edit' => [
            'name' => 'attributes_edit',
            'display_name' => 'ویرایش ویژگی '
        ],
        'attributes_delete' => [
            'name' => 'attributes_delete',
            'display_name' => 'حذف ویژگی '
        ],

        // Product
        'products_view' => [
            'name' => 'products_view',
            'display_name' => 'مشاهده محصولات'
        ],
        'products_create' => [
            'name' => 'products_create',
            'display_name' => 'ایجاد محصول'
        ],
        'products_edit' => [
            'name' => 'products_edit',
            'display_name' => 'ویرایش محصول'
        ],
        'products_delete' => [
            'name' => 'products_delete',
            'display_name' => 'حذف محصول'
        ],
        'show_purchase_price' => [
            'name' => 'show_purchase_price',
            'display_name' => ' نمایش قیمت خرید'
        ],

        // Purchase
        'purchases_view' => [
            'name' => 'purchases_view',
            'display_name' => 'مشاهده خرید‌ها'
        ],
        'purchases_create' => [
            'name' => 'purchases_create',
            'display_name' => 'ایجاد خرید'
        ],
        'purchases_edit' => [
            'name' => 'purchases_edit',
            'display_name' => 'ویرایش خرید'
        ],
        'purchases_delete' => [
            'name' => 'purchases_delete',
            'display_name' => 'حذف خرید'
        ],
        'purchase_show_detail' => [
            'name' => 'purchase_show_detail',
            'display_name' => 'مشاهده جزئیات خرید'
        ],

        // Purchase Return
        /*'purchase_returns_view' => [
            'name' => 'purchase_returns_view',
            'display_name' => 'مشاهده بازگشت از خرید‌ها'
        ],
        'purchase_returns_create' => [
            'name' => 'purchase_returns_create',
            'display_name' => 'ایجاد بازگشت از خرید'
        ],
        'purchase_returns_edit' => [
            'name' => 'purchase_returns_edit',
            'display_name' => 'ویرایش بازگشت از خرید'
        ],
        'purchase_returns_delete' => [
            'name' => 'purchase_returns_delete',
            'display_name' => 'حذف بازگشت از خرید'
        ],*/

        // Sales
        'sales_view' => [
            'name' => 'sales_view',
            'display_name' => ' مشاهده فروش‌ها'
        ],
        'sales_create' => [
            'name' => 'sales_create',
            'display_name' => 'ایجاد فروش'
        ],
        'sales_edit' => [
            'name' => 'sales_edit',
            'display_name' => 'ویرایش فروش'
        ],
        'sales_delete' => [
            'name' => 'sales_delete',
            'display_name' => 'حذف فروش'
        ],
        'sales_show_detail' => [
            'name' => 'sales_show_detail',
            'display_name' => 'مشاهده جزئیات فروش'
        ],

        // Sales Return
        /*'sales_returns_view' => [
            'name' => 'sales_returns_view',
            'display_name' => ' مشاهده بازگشت از فروش‌ها'
        ],
        'sales_returns_create' => [
            'name' => 'sales_returns_create',
            'display_name' => 'ایجاد بازگشت از فروش'
        ],
        'sales_returns_edit' => [
            'name' => 'sales_returns_edit',
            'display_name' => 'ویرایش بازگشت از فروش'
        ],
        'sales_returns_delete' => [
            'name' => 'sales_returns_delete',
            'display_name' => 'حذف بازگشت از فروش'
        ],*/

        // Order Payments
        'order_payments_view' => [
            'name' => 'order_payments_view',
            'display_name' => 'مشاهده پرداخت‌ها'
        ],
        'order_payments_create' => [
            'name' => 'order_payments_create',
            'display_name' => 'ایجاد پرداخت'
        ],
        'order_payments_edit' => [
            'name' => 'order_payments_edit',
            'display_name' => 'ویرایش پرداخت'
        ],
        'order_payments_delete' => [
            'name' => 'order_payments_delete',
            'display_name' => 'حذف پرداخت'
        ],

        // Stock Adjustment
        'stock_adjustments_view' => [
            'name' => 'stock_adjustments_view',
            'display_name' => 'مشاهده تنظیم موجودی'
        ],
        'stock_adjustments_create' => [
            'name' => 'stock_adjustments_create',
            'display_name' => 'ایجاد تنظیم موجودی'
        ],
        'stock_adjustments_delete' => [
            'name' => 'stock_adjustments_delete',
            'display_name' => 'حذف تنظیم موجودی'
        ],

        // Expense Category
        /*'expense_categories_view' => [
            'name' => 'expense_categories_view',
            'display_name' => 'مشاهده دسته‌بندی هزینه‌ها'
        ],
        'expense_categories_create' => [
            'name' => 'expense_categories_create',
            'display_name' => 'ایجاد دسته‌بندی هزینه‌ها'
        ],
        'expense_categories_edit' => [
            'name' => 'expense_categories_edit',
            'display_name' => 'ویرایش دسته‌بندی هزینه‌ها'
        ],
        'expense_categories_delete' => [
            'name' => 'expense_categories_delete',
            'display_name' => 'حذف دسته‌بندی هزینه‌ها'
        ],*/

        // Expense
      /*  'expenses_view' => [
            'name' => 'expenses_view',
            'display_name' => 'هزینه‌ها'
        ],
        'expenses_create' => [
            'name' => 'expenses_create',
            'display_name' => 'ایجاد هزینه'
        ],
        'expenses_edit' => [
            'name' => 'expenses_edit',
            'display_name' => 'ویرایش هزینه'
        ],
        'expenses_delete' => [
            'name' => 'expenses_delete',
            'display_name' => ' حذف هزینه'
        ],*/

        // Unit
        'units_view' => [
            'name' => 'units_view',
            'display_name' => 'مشاهده واحد‌های شمارش'
        ],
        'units_create' => [
            'name' => 'units_create',
            'display_name' => 'ایجاد واحد شمارش'
        ],
        'units_edit' => [
            'name' => 'units_edit',
            'display_name' => 'ویرایش واحد شمارش'
        ],
        'units_delete' => [
            'name' => 'units_delete',
            'display_name' => 'حذف واحد شمارش'
        ],

        // Tax
        'taxes_view' => [
            'name' => 'taxes_view',
            'display_name' => 'مشاهده مالیات'
        ],
        'taxes_create' => [
            'name' => 'taxes_create',
            'display_name' => 'ایجاد مالیات'
        ],
        'taxes_edit' => [
            'name' => 'taxes_edit',
            'display_name' => 'ویرایش مالیات'
        ],
        'taxes_delete' => [
            'name' => 'taxes_delete',
            'display_name' => 'حذف مالیات'
        ],

        // Role
        'roles_view' => [
            'name' => 'roles_view',
            'display_name' => ' مشاهده نقش‌ها'
        ],
        'roles_create' => [
            'name' => 'roles_create',
            'display_name' => 'ایجاد نقش'
        ],
        'roles_edit' => [
            'name' => 'roles_edit',
            'display_name' => 'ویرایش نقش'
        ],
        'roles_delete' => [
            'name' => 'roles_delete',
            'display_name' => 'حذف نقش'
        ],

        // Warehouse
        'warehouses_view' => [
            'name' => 'warehouses_view',
            'display_name' => 'مشاهده انبار'
        ],
        'warehouses_create' => [
            'name' => 'warehouses_create',
            'display_name' => 'ایجاد انبار'
        ],
        'warehouses_edit' => [
            'name' => 'warehouses_edit',
            'display_name' => 'ویرایش انبار'
        ],
        'warehouses_delete' => [
            'name' => 'warehouses_delete',
            'display_name' => 'حذف انبار'
        ],

        // Company
        'companies_edit' => [
            'name' => 'companies_edit',
            'display_name' => 'ویرایش کسب‌و‌کار'
        ],
        'companies_view' => [
            'name' => 'companies_view',
            'display_name' => 'مشاهده کسب‌و‌کار'
        ],

        // Staff Member
        'users_view' => [
            'name' => 'users_view',
            'display_name' => 'مشاهده کاربران'
        ],
        'users_create' => [
            'name' => 'users_create',
            'display_name' => 'ایجاد کاربر'
        ],
        'users_edit' => [
            'name' => 'users_edit',
            'display_name' => 'ویرایش کاربر'
        ],
        'users_delete' => [
            'name' => 'users_delete',
            'display_name' => 'حذف کاربر'
        ],

        'users_detail_view' => [
            'name' => 'users_detail_view',
            'display_name' => 'مشاهده جزئیات کاربران'
        ],

        // Customer
        'customers_view' => [
            'name' => 'customers_view',
            'display_name' => 'مشاهده مشتریان'
        ],
        'customers_create' => [
            'name' => 'customers_create',
            'display_name' => 'ایجاد مشتری'
        ],
        'customers_edit' => [
            'name' => 'customers_edit',
            'display_name' => 'ویرایش مشتری'
        ],
        'customers_delete' => [
            'name' => 'customers_delete',
            'display_name' => 'حذف مشتری'
        ],
        'customers_detail_view' => [
            'name' => 'customers_detail_view',
            'display_name' => 'مشاهده جزئیات مشتریان'
        ],

        // Supplier
        'suppliers_view' => [
            'name' => 'suppliers_view',
            'display_name' => 'مشاهده تامین‌کنندگان'
        ],
        'suppliers_create' => [
            'name' => 'suppliers_create',
            'display_name' => 'ایجاد تامین‌کننده'
        ],
        'suppliers_edit' => [
            'name' => 'suppliers_edit',
            'display_name' => 'ویرایش تامین‌کننده'
        ],
        'suppliers_delete' => [
            'name' => 'suppliers_delete',
            'display_name' => 'حذف تامین‌کننده'
        ],
        'suppliers_detail_view' => [
            'name' => 'suppliers_detail_view',
            'display_name' => 'مشاهده جزئیات تامین‌کننده'
        ],

        // POS
        'pos_view' => [
            'name' => 'pos_view',
            'display_name' => 'مشاهده صندوق فروش'
        ],
        'reports_view' => [
            'name' => 'reports_view',
            'display_name' => 'مشاهده گزارشات'
        ],
        //Variant
        'view_variants' => [
            'name' => 'view_variants',
            'display_name' => 'مشاهده تنوع'
        ],
        'view_categories_variants' => [
            'name' => 'view_categories_variants',
            'display_name' => 'مشاهده تنوع‌های دسته‌بندی'
        ],
        'view_values_variants' => [
            'name' => 'view_values_variants',
            'display_name' => 'مشاهده مقادیر تنوع‌ها'
        ],
        'add_variant_to_category' => [
            'name' => 'add_variant_to_category',
            'display_name' => 'افزودن تنوع‌به‌دسته‌بندی'
        ],
        'empty_variant_from_category' => [
            'name' => 'empty_variant_from_category',
            'display_name' => 'حذف تنوع از دسته‌بندی'
        ],
        'add_value_to_variant' => [
            'name' => 'add_value_to_variant',
            'display_name' => 'افزودن مقدار به تنوع'
        ],
        'delete_value_from_variant' => [
            'name' => 'delete_value_from_variant',
            'display_name' => 'حذف مقدار از تنوع'
        ],

        //product variant
        'view_product_variants' => [
            'name' => 'view_product_variants',
            'display_name' => 'مشاهده تنوع محصول'
        ],
        'create_product_variants' => [
            'name' => 'create_product_variants',
            'display_name' => 'ایجاد تنوع محصول'
        ],
        'delete_product_variants' => [
            'name' => 'delete_product_variants',
            'display_name' => 'حذف تنوع محصول'
        ],
        'show_self_profile' => [
            'name' => 'show_self_profile',
            'display_name' => 'مشاهده پروفایل'
        ],
        'update_self_profile' => [
            'name' => 'update_self_profile',
            'display_name' => 'بروزرسانی پروفایل'
        ],
        'show_print_barcode' => [
            'name' => 'show_print_barcode',
            'display_name' => 'مشاهده پرینت بارکد'
        ],
        'add_print_barcode' => [
            'name' => 'add_print_barcode',
            'display_name' => 'ایجاد پرینت بارکد'
        ],

        //order online
        'online_shop_menu' => [
            'name' => 'online_shop_menu',
            'display_name' => 'مشاهده منو فروشگاه آنلاین'
        ],
        'order_view' => [
            'name' => 'order_view',
            'display_name' => 'مشاهده لیست سفارشات آنلاین'
        ],
        'order_details_view' => [
            'name' => 'order_details_view',
            'display_name' => 'مشاهده جزئیات سفارش آنلاین'
        ],
        'change_order_status' => [
            'name' => 'change_order_status',
            'display_name' => 'تغییر وضعیت سفارش'
        ],
        'change_order_payment_status' => [
            'name' => 'change_order_payment_status',
            'display_name' => 'تغییر وضعیت پرداخت سفارش'
        ],
        'insert_order_tracking_number' => [
            'name' => 'insert_order_tracking_number',
            'display_name' => 'درج کدرهگیری مرسوله'
        ],
        'view_transaction' => [
            'name' => 'view_transaction',
            'display_name' => 'مشاهده تراکنش‌های آنلاین'
        ],
        'view_merchants' => [
            'name' => 'view_merchants',
            'display_name' => 'مشاهده تسویه حساب'
        ],
        'view_front_menu' => [
            'name' => 'view_front_menu',
            'display_name' => 'مشاهده چیدمان منو'
        ],
        'update_front_menu' => [
            'name' => 'update_front_menu',
            'display_name' => 'بروزرسانی چیدمان منو'
        ],
        'page_builder' => [
            'name' => 'page_builder',
            'display_name' => 'صفحه‌ساز فروشگاه'
        ],
        'order_histories' => [
            'name' => 'order_histories',
            'display_name' => 'مشاهده تاریخچه سفارش'
        ],
        'order_cost' => [
            'name' => 'order_cost',
            'display_name' => 'مشاهده هزینه‌های سفارش'
        ],
        //settings
        'general_setting_view' => [
            'name' => 'general_setting_view',
            'display_name' => 'مشاهده تنظیمات عمومی'
        ],
        'general_setting_update' => [
            'name' => 'general_setting_update',
            'display_name' => 'بروزرسانی تنظیمات عمومی'
        ],
        'shipping_setting_view' => [
            'name' => 'shipping_setting_view',
            'display_name' => 'مشاهده تنظیمات حمل‌ونقل'
        ],
        'shipping_setting_update' => [
            'name' => 'shipping_setting_update',
            'display_name' => 'بروزرسانی تنظیمات حمل‌ونقل'
        ],
        'third_party_setting_view' => [
            'name' => 'third_party_setting_view',
            'display_name' => 'مشاهده تنظیمات سرویس افزودنی'
        ],
        'third_party_enamad_setting_update' => [
            'name' => 'third_party_enamad_setting_update',
            'display_name' => 'بروزرسانی تنظیمات نماد اعتماد'
        ],
        'third_party_samandehi_setting_update' => [
            'name' => 'third_party_samandehi_setting_update',
            'display_name' => 'بروزرسانی تنظیمات ساماندهی'
        ],
        'third_party_mediaad_setting_update' => [
            'name' => 'third_party_mediaad_setting_update',
            'display_name' => 'بروزرسانی تنظیمات مدیااد'
        ],
        'third_party_goftino_setting_update' => [
            'name' => 'third_party_goftino_setting_update',
            'display_name' => 'بروزرسانی تنظیمات گفتینو'
        ],
        'third_party_gtag_setting_update' => [
            'name' => 'third_party_gtag_setting_update',
            'display_name' => 'بروزرسانی تنظیمات گوگل آنالیتیکس'
        ],
        'third_party_ippanel_setting_update' => [
            'name' => 'third_party_ippanel_setting_update',
            'display_name' => 'بروزرسانی تنظیمات ippanel'
        ],
        'third_party_zibal_setting_update' => [
            'name' => 'third_party_zibal_setting_update',
            'display_name' => 'بروزرسانی تنظیمات زیبال'
        ],
        'third_party_telegram_setting_update' => [
            'name' => 'third_party_telegram_setting_update',
            'display_name' => 'بروزرسانی تنظیمات تلگرام'
        ],

        //Slider
        'sliders_view' => [
            'name' => 'sliders_view',
            'display_name' => 'مشاهده اسلایدر‌ها'
        ],
        'sliders_create' => [
            'name' => 'sliders_create',
            'display_name' => 'ایجاد اسلایدر'
        ],
        'sliders_edit' => [
            'name' => 'sliders_edit',
            'display_name' => 'ویرایش اسلایدر'
        ],
        'sliders_delete' => [
            'name' => 'sliders_delete',
            'display_name' => 'حذف اسلایدر'
        ],
        'slider_item_create' => [
            'name' => 'slider_item_create',
            'display_name' => 'ایجاد تصاویر اسلایدر'
        ],
        'slider_item_delete' => [
            'name' => 'slider_item_delete',
            'display_name' => 'حذف تصاویر اسلایدر'
        ],
        //Banner
        'banners_view' => [
            'name' => 'banners_view',
            'display_name' => 'مشاهده بنر‌ها'
        ],
        'banners_create' => [
            'name' => 'banners_create',
            'display_name' => 'ایجاد بنر'
        ],
        'banners_edit' => [
            'name' => 'banners_edit',
            'display_name' => 'ویرایش بنر'
        ],
        'banners_delete' => [
            'name' => 'banners_delete',
            'display_name' => 'حذف بنر'
        ],
        //Home
        'homes_view' => [
            'name' => 'homes_view',
            'display_name' => 'مشاهده چیدمان‌ها'
        ],
        'homes_create' => [
            'name' => 'homes_create',
            'display_name' => 'ایجاد چیدمان'
        ],
        'homes_edit' => [
            'name' => 'homes_edit',
            'display_name' => 'ویرایش چیدمان'
        ],
        'homes_delete' => [
            'name' => 'homes_delete',
            'display_name' => 'حذف چیدمان'
        ],
        'home_item_create' => [
            'name' => 'home_item_create',
            'display_name' => 'ایجاد آیتم چیدمان'
        ],
        'home_item_delete' => [
            'name' => 'slider_item_delete',
            'display_name' => 'حذف آیتم چیدمان'
        ],

        'attribute_group_view' => [
            'name' => 'attribute_group_view',
            'display_name' => 'مشاهده گروه‌ویژگی‌ها'
        ],
        'attribute_group_create' => [
            'name' => 'attribute_group_create',
            'display_name' => 'ایجاد گروه‌ویژگی'
        ],
        'attribute_group_edit' => [
            'name' => 'attribute_group_edit',
            'display_name' => 'ویرایش گروه‌ویژگی'
        ],
        'attribute_group_delete' => [
            'name' => 'attribute_group_delete',
            'display_name' => 'حذف گروه‌ویژگی'
        ],

        'size_guide_view' => [
            'name' => 'size_guide_view',
            'display_name' => 'مشاهده گروه‌ویژگی‌ها'
        ],
        'size_guide_create' => [
            'name' => 'size_guide_create',
            'display_name' => 'ایجاد گروه‌ویژگی'
        ],
        'size_guide_edit' => [
            'name' => 'size_guide_edit',
            'display_name' => 'ویرایش گروه‌ویژگی'
        ],
        'size_guide_delete' => [
            'name' => 'size_guide_delete',
            'display_name' => 'حذف گروه‌ویژگی'
        ],
        'show_reports_profit_loss' => [
            'name' => 'show_reports_profit_loss',
            'display_name' => 'مشاهده گزارش سود و زیان'
        ],

        //TODO: add permission for pages. IMPORTANT!
    ];

    private static $mainRoleArray = [
        'admin' => [
            'name' => 'technicalAdmin',
            'display_name' => 'مدیرفنی'
        ],
    ];

    private static array $paymentModeList = [
        'Cash' => [
            'name' => 'cash',
            'display_name' => 'نقدی',
        ],
        'Card' => [
            'name' => 'card',
            'display_name' => 'کارتخوان',
        ],
        'Card_to_card' => [
            'name' => 'card_to_card',
            'display_name' => 'کارت به کارت',
        ],
        'Check' => [
            'name' => 'check',
            'display_name' => 'چک',
        ],
        'Online' => [
            'name' => 'online_gateway',
            'display_name' => 'درگاه آنلاین',
        ],
    ];


    private static array $colorList = [
        'IndianRed' => [
            'title' => 'جگری',
            'code' => 'cd5c5c',
        ],
        'Red' => [
            'title' => 'قرمز',
            'code' => 'ff0000',
        ],
        'Crimson' => [
            'title' => 'زرشکی',
            'code' => 'dc143c',
        ],
        'DarkRed' => [
            'title' => 'عنابی تند',
            'code' => '8b0000',
        ],
        'Pink' => [
            'title' => 'صورتی',
            'code' => 'ffc0cb',
        ],
        'HotPink' => [
            'title' => 'سرخابی',
            'code' => 'ff69b4',
        ],
        'MediumVioletRed' => [
            'title' => 'ارغوانی',
            'code' => 'c71585',
        ],
        'Orange' => [
            'title' => 'نارنجی',
            'code' => 'ffa500',
        ],
        'DarkOrange' => [
            'title' => 'نارنجی سیر',
            'code' => 'ff8c00',
        ],
        'OrangeRed' => [
            'title' => 'قرمز نارنجی',
            'code' => 'ff4500',
        ],
        'Yellow' => [
            'title' => 'زرد',
            'code' => 'ffff00',
        ],
        'DarkKhaki' => [
            'title' => 'ماشی',
            'code' => 'bdb76b',
        ],
        'GreenYellow' => [
            'title' => 'مغزپسته‌ای',
            'code' => 'adff2f',
        ],
        'PaleGreen' => [
            'title' => 'سبز کمرنگ',
            'code' => '98fb98',
        ],
        'YellowGreen' => [
            'title' => 'سبز لجنی',
            'code' => '9acd32',
        ],
        'Green' => [
            'title' => 'سبز',
            'code' => '008000',
        ],
        'Olive' => [
            'title' => 'زیتونی',
            'code' => '808000',
        ],
        'Aqua' => [
            'title' => 'فیروزه‌ای',
            'code' => '00ffff',
        ],
        'blue' => [
            'title' => 'آبی',
            'code' => '0000ff',
        ],
        'DarkBlue' => [
            'title' => 'سرمه‌ای',
            'code' => '00008b',
        ],
        'Thistle' => [
            'title' => 'بادمجانی روشن',
            'code' => 'd8bfd8',
        ],
        'Violet' => [
            'title' => 'بنفش روشن',
            'code' => 'ee82ee',
        ],
        'Fuchsia' => [
            'title' => 'سرخابی',
            'code' => 'ff00ff',
        ],
        'DarkViolet' => [
            'title' => 'بنفش باز',
            'code' => '9400d3',
        ],
        'Purple' => [
            'title' => 'بنفش',
            'code' => '800080',
        ],
        'Indigo' => [
            'title' => 'نیلی سیر',
            'code' => '4b0082',
        ],
        'Bisque' => [
            'title' => 'کرم',
            'code' => 'ffe4c4',
        ],
        'BurlyWood' => [
            'title' => 'خاکی',
            'code' => 'deb887',
        ],
        'Goldenrod' => [
            'title' => 'خردلی',
            'code' => 'daa520',
        ],
        'Chocolate' => [
            'title' => 'عسلی پررنگ',
            'code' => 'd2691e',
        ],
        'SaddleBrown' => [
            'title' => 'کاکائویی',
            'code' => '8b4513',
        ],
        'Brown' => [
            'title' => 'قهوه‌ای',
            'code' => 'a52a2a',
        ],
        'white' => [
            'title' => 'سفید',
            'code' => 'ffffff',
        ],
        'Seashell' => [
            'title' => 'بژ باز',
            'code' => 'fff5ee',
        ],
        'MistyRose' => [
            'title' => 'بژ',
            'code' => 'ffe4e1',
        ],
        'Gainsboro' => [
            'title' => 'خاکستری مات',
            'code' => 'dcdcdc',
        ],
        'DarkSlateGray' => [
            'title' => 'لجنی تیره',
            'code' => '2f4f4f',
        ],
        'Black' => [
            'title' => 'مشکی',
            'code' => '000000',
        ],
    ];

    private static array $sizeList = [
        '34' => [
            'title' => '34',
        ],
        '35' => [
            'title' => '35',
        ],
        '36' => [
            'title' => '36',
        ],
        '37' => [
            'title' => '37',
        ],
        '38' => [
            'title' => '38',
        ],
        '39' => [
            'title' => '39',
        ],
        '40' => [
            'title' => '40',
        ],
        '40.5' => [
            'title' => '40.5',
        ],
        '41' => [
            'title' => '41',
        ],
        '42' => [
            'title' => '42',
        ],
        '42.5' => [
            'title' => '42.5',
        ],
        '43' => [
            'title' => '43',
        ],
        '44' => [
            'title' => '44',
        ],
        '44.5' => [
            'title' => '44.5',
        ],
        '45' => [
            'title' => '45',
        ],
        '46' => [
            'title' => '46',
        ],
        '46.5' => [
            'title' => '46.5',
        ],
        '47' => [
            'title' => '47',
        ],
        '48' => [
            'title' => '48',
        ],
        '47.5' => [
            'title' => '47.5',
        ],
    ];

    private static array $variants = [
      'color' => [
          'title' => 'رنگ',
          'type' => 'color',
      ],
      'size' => [
          'title' => 'سایز یا اندازه',
          'type' => 'size',
      ],
    ];

    public static function seedVariants()
    {
        foreach (self::$variants as $variant) {
            Variant::create($variant);
        }
    }

    private static function getPermissionArray($moduleName): array
    {
        if ($moduleName == 'Estore') {
            return self::$eStorePermissions;
        }

        return self::$permissionsArray;
    }

    public static function seedPermissions($moduleName = '')
    {
        $permissions = self::getPermissionArray($moduleName);

        foreach ($permissions as $group => $permission) {
            $permissionCount = Permission::where('name', $permission['name'])->count();

            if ($permissionCount == 0) {
                $newPermission = new Permission();
                $newPermission->name = $permission['name'];
                $newPermission->display_name = $permission['display_name'];
                $newPermission->save();
            }
        }
    }

    public static function seedRoles()
    {
        $roles = self::$mainRoleArray;
        foreach ($roles as $group => $role) {
            $roleCount = Role::where('name', $role['name'])->count();
            if ($roleCount == 0) {
                $newRole = new Role();
                $newRole->name = $role['name'];
                $newRole->display_name = $role['display_name'];
                $newRole->save();
            }
        }

    }

    public static function seedUser()
    {
        $user = new \Modules\User\Entities\User();
        $user->first_name = "محمد";
        $user->last_name = "رمضانی";
        $user->mobile = "09118534581";
        $user->email = "pars.techco@yahoo.com";
        $user->user_type = "staff_members";
        $user->password = \Hash::make("416**2885");
        $user->save();

        $role = Role::findOrFail(1);
        $role->givePermissionTo(['roles_view', 'roles_edit']);

        $user->refreshRoles($role->name);

    }

    public static function seedColor(): bool
    {
        $colors = self::$colorList;
        foreach ($colors as $color){
            Color::create($color);
        }
        return true;
    }

    public static function seedSize() :bool
    {
        $sizes = self::$sizeList;
        foreach ($sizes as $size){
            Size::create($size);
        }
        return true;
    }

    public static function seedCompany()
    {
        $company = new Company();
        $company->site_title = "این عنوان را از بخش تنظیمات>اطلاعات بیزینس تغییر دهید";
        $company->warehouse_id = \Modules\Warehouse\Entities\Warehouse::first()->id;
        $company->unit_id = \Modules\Unit\Entities\Unit::first()->id;
        $company->save();
    }
    /**
     * To create all counting units for the first time in the database
     * @return array
     */
    public static function allUnits(): array
    {
        return [
            'piece' => [
                'name' => 'عدد',
                'short_name' => 'عدد',
                'operator' => 'multiply',
                'operator_value' => '1',
            ],
            'carton' => [
                'name' => 'کارتن',
                'short_name' => 'کارتن',
                'operator' => 'multiply',
                'operator_value' => '1',
            ],
            'liter' => [
                'name' => 'لیتر',
                'short_name' => 'لیتر',
                'operator' => 'multiply',
                'operator_value' => '1',
            ],
            'kilogram' => [
                'name' => 'کیلوگرم',
                'short_name' => 'کیلوگرم',
                'operator' => 'multiply',
                'operator_value' => '1',
            ],
            'milligram' => [
                'name' => 'میلی‌گرم',
                'short_name' => 'میلی‌گرم',
                'operator' => 'multiply',
                'operator_value' => '1',
            ],
        ];
    }

    public static function seedPayMode()
    {
        $payModes = self::$paymentModeList;

        foreach ($payModes as $payMode){
            $paymentMode = new PaymentMode();
            $paymentMode->name = $payMode['name'];
            $paymentMode->display_name = $payMode['display_name'];
            $paymentMode->save();
        }
    }

    public static function seedUnits()
    {
        $allUnits = self::allUnits();

        foreach ($allUnits as $allUnit) {
            $unit = new Unit();
            $unit->name = $allUnit['name'];
            $unit->short_name = $allUnit['short_name'];
            $unit->operator = $allUnit['operator'];
            $unit->operator_value = $allUnit['operator_value'];
            $unit->is_deletable = false;
            $unit->save();
        }
    }

    public static function seedDefaultWarehouse()
    {
        $warehouse = new \Modules\Warehouse\Entities\Warehouse();
        $warehouse->name = "فروشگاه مرکزی";
        $warehouse->save();
    }

    public static function seedDefaultTax()
    {
        $tax = new \Modules\Tax\Entities\Tax();
        $tax->name = "مالیات بر ارزش افزوده";
        $tax->rate = .9;
        $tax->save();
    }
}
