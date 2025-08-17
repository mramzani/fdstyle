<?php

namespace Modules\User\Traits;

use Modules\User\Entities\User;

trait Sortable
{
    public string $sortField = 'created_at';
    public string $sortLabel = '(جدید‌ترین)';
    public string $sortDirection = 'desc';
    public string $search = '';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        }
        else {
            $this->sortDirection = 'asc';
        }
        if ($this->sortDirection === 'asc'){
            $this->sortLabel = '(قدیمی‌ترین)';
        }
        else {
            $this->sortLabel = '(جدیدترین)';
        }
        $this->sortField = $field;
    }

    public static function searchBy($term,$sortField,$sortDirection)
    {
        $result = self::search(\DB::raw("concat(first_name, ' ', last_name)"), $term)
            ->orWhere('mobile', 'LIKE', '%' . $term . '%');

            if ($result->first() AND $result->first() instanceof User){
                $result->dontGetFirstUser();
            }

            return $result->orderBy($sortField, $sortDirection)
            ->latest()->paginate(10);
    }

    public static function searchWithRelation($term,$sortField,$sortDirection,$relation)
    {
        return self::search(\DB::raw("concat(first_name, ' ', last_name)"), $term)
            ->orWhere('mobile', 'LIKE', '%' . $term . '%')
            ->with($relation)
            ->orderBy($sortField, $sortDirection)
            ->latest()->paginate(10);
    }

}
