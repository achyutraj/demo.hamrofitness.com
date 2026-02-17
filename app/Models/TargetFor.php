<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetFor extends Model
{
    use HasFactory;

    protected $table = 'target_for';

    public static function targetFor($business_id)
    {
        $subCat = BusinessSubCategory::select('sub_category_id')->where('detail_id','=',$business_id)->get()->toArray();

        return TargetFor::whereIn('sub_cat_id',$subCat)->get();
    }
}
