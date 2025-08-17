<?php

namespace Modules\Dashboard\Entities;

use App\Services\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Unit\Entities\Unit;
use Modules\Warehouse\Entities\Warehouse;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'site_title',
        'logo',
        'desc',
        'email',
        'phone',
        'address',
        'warehouse_id',
        'unit_id',
    ];

    public function getImageUrlAttribute():string
    {
        $companyLogoPath = Common::getFolderPath('companyLogoPath');

        return $this->logo == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($this->logo,$companyLogoPath);
    }

    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function unit():BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }




}
