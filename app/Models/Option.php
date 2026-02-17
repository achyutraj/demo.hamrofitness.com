<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillables= ['key', 'value'];
    public static function defaultDiet($details_id,$key){
        $val = static::find($details_id, $key);
        return ( $val !== null) ? $val->value : null ;
    }
    public static function find($details_id, $key){
        $val =  static::where('key', $key)->where('details_id', $details_id)->first();
        if($val){
            return $val;
        }
        return null;
    }
    public static function set($details_id,$key, $value){
        $option = Option::find($details_id,$key);
        if($option){
            $option->value = serialize($value);
            $option->save();
        }else{
            $option = new Option();
            $option->key = $key;
            $option->details_id = $details_id;
            $option->value = serialize($value);
            $option->save();
        }
        $option->value = unserialize($option->value);
        return $option;
    }
}
