<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



use Modules\Product\Entities\Color;
use Modules\Product\Http\Controllers\AttributeController;
use Modules\Product\Http\Controllers\AttributeGroupController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\SizeGuideController;
use Modules\Product\Http\Controllers\StockAdjustmentController;

Route::prefix('dashboard')
   /* ->as('dashboard.')*/ //TODO:: adding
    ->middleware(['web','auth:admin'])
    ->group(function() {

        Route::get('products','ProductController@index')->name('products.index')->can('products_view');
        Route::get('products/create','ProductController@create')->name('products.create')->can('products_create');
        Route::post('products','ProductController@store')->name('products.store')->can('products_create');
        Route::get('products/{product}/edit','ProductController@edit')->name('products.edit')->can('products_edit');
        Route::put('products/{product}','ProductController@update')->name('products.update')->can('products_edit');
        Route::delete('products/{product}','ProductController@destroy')->name('products.destroy')->can('products_delete');
        Route::get('products/generate-barcode','ProductController@generateBarcode')->name('products.generate-barcode');
        Route::post('variants/list','ProductController@variantList')->name('product.variants.list')->can('products_view');
        Route::post('stock-histories','ProductController@stockHistories')->name('product.stock-histories'); //->can('stock_histories_view'); // TODO : new permission add
        Route::post('autocomplete-search',[ProductController::class,'search'])->name('autocomplete-search')->can('products_view');
        Route::post('autocomplete-adjustment-search',[ProductController::class,'search'])->name('autocomplete-search')->can('products_view');

        Route::post('/products/update-price',[ProductController::class,'updatePrice'])->name('product.update-price');

        // variants
        Route::get('variants','VariantsController@index')->name('variants.index')->can('view_variants');
        Route::post('variants/attach/category','VariantsController@variantAttach')->name('variants.attach.category')->can('add_variant_to_category');
        Route::delete('variants/{category}/empty','VariantsController@emptyVariant')->name('variants.empty.category')->can('empty_variant_from_category');

        // variant-values
        Route::post('variant-values','VariantValuesController@store')->name('variant-values.store')->can('add_value_to_variant');
        Route::delete('variant-values/{value}','VariantValuesController@destroy')->name('variant-values.destroy')->can('delete_value_from_variant');
        Route::post('variant-values/categories','VariantValuesController@getCategories')->name('attributes.get-categories')->can('add_value_to_variant');

        // add and delete product variant
        Route::get('products/{product}/variants','ProductVariantsController@index')->name('products.variants.index')->can('view_product_variants');
        Route::post('products/{product}/variants','ProductVariantsController@store')->name('products.variants.store')->can('create_product_variants');
        Route::delete('variants/{productVariant}','ProductVariantsController@destroy')->name('products.variants.destroy')->can('delete_product_variants');

        Route::get('adjustments','StockAdjustmentController@index')->name('adjustments.index')->can('stock_adjustments_view');
        Route::get('adjustments/create','StockAdjustmentController@create')->name('adjustments.create')->can('stock_adjustments_create');
        Route::delete('adjustments/{stockAdjustment}/destroy','StockAdjustmentController@destroy')->name('adjustments.destroy')->can('stock_adjustments_delete');
        Route::post('adjustments','StockAdjustmentController@store')->name('adjustments.store')->can('stock_adjustments_create');
        Route::post('autocomplete-adjustment-search',[StockAdjustmentController::class,'search'])->name('autocomplete-adjustment-search')->can('stock_adjustments_create');

        Route::get('barcode/print',[ProductController::class,'printIndex'])->name('barcode.print')->can('add_print_barcode');

        Route::get('colors/code',function (\Illuminate\Http\Request $request){
            return Color::where('id',$request->input('id'))->firstOrFail()->code;
        })->name('get-color-code');


        Route::get('product/{product}/attribute',[AttributeController::class,'edit'])->name('product.attribute.edit');
        Route::put('product/{product}/attribute',[AttributeController::class,'update'])->name('product.attribute.update');
        Route::post('product/attribute/values',[AttributeController::class,'getValue'])->name('product.attribute.values');

        Route::get('attribute-groups',[AttributeGroupController::class,'index'])->name('attribute-group.index')->can('attribute_group_view');
        Route::post('attribute-groups',[AttributeGroupController::class,'store'])->name('attribute-group.store')->can('attribute_group_create');
        Route::get('attribute-groups/{attribute_group}/edit',[AttributeGroupController::class,'edit'])->name('attribute-group.edit')->can('attribute_group_edit');
        Route::put('attribute-groups/{attribute_group}/edit',[AttributeGroupController::class,'update'])->name('attribute-group.update')->can('attribute_group_edit');
        Route::delete('attribute-groups/{attribute_group}/destroy',[AttributeGroupController::class,'destroy'])->name('attribute-group.destroy')->can('attribute_group_delete');
        Route::post('attribute-groups/{attribute_group}/add',[AttributeGroupController::class,'addAttribute'])->name('attribute-group.add-attribute')->can('attribute_group_create');

        //size guide route
        Route::get('size-guides',[SizeGuideController::class,'index'])->name('guide-size.index')->can('size_guide_view');
        Route::get('size-guides/create',[SizeGuideController::class,'create'])->name('guide-size.create')->can('size_guide_create');
        Route::post('size-guides/create',[SizeGuideController::class,'store'])->name('guide-size.store')->can('size_guide_create');
        Route::get('size-guides/{size_guide}/edit',[SizeGuideController::class,'edit'])->name('guide-size.edit')->can('size_guide_edit');
        Route::put('size-guides/{size_guide}/edit',[SizeGuideController::class,'update'])->name('guide-size.update')->can('size_guide_edit');
        Route::delete('size-guides/{size_guide}/destroy',[SizeGuideController::class,'destroy'])->name('guide-size.destroy')->can('size_guide_delete');


        //digikala importer
        Route::get('/products/smart-import',[ProductController::class,'smartImport'])->name('product.smart-import');
        Route::get('/products/smart-create',[ProductController::class,'getSmartImportData'])->name('product.smart-store');
        Route::post('/product/smart',[ProductController::class,'storeSmartProduct'])->name('product.store-smart-product');


    });
