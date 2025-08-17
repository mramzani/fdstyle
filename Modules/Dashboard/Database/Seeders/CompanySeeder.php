<?php

namespace Modules\Dashboard\Database\Seeders;

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{

    /**
     * @return void
     */
    public function run()
    {
        $company = new \Modules\Dashboard\Entities\Company();
        $company->site_title = "این عنوان را از بخش تنظیمات>اطلاعات بیزینس تغییر دهید";
        $company->warehouse_id = \Modules\Warehouse\Entities\Warehouse::first()->id;
        $company->unit_id = \Modules\Unit\Entities\Unit::first()->id;
        $company->save();
    }
}
