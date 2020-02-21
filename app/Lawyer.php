<?php

namespace App;

use App\Http\Requests\StoreLawyer;
use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    protected $guarded = ["id"];

    public function title()
    {
        return $this->belongsTo(Title::class);
    }
//
//    public function rank()
//    {
//        return $this->belongsTo(Rank::class);
//    }

    /**
     * Returns an Array of Validated Attributes
     * @param StoreLawyer $request
     * @return array
     */
    public static function  preProcess(StoreLawyer $request){
        $lawyer_attributes = $request->validated();
        unset($lawyer_attributes['image']);
        $lawyer_attributes["title_id"] = $request->get("title_id");
        $lawyer_attributes["addon_rate"] = $request->validated()["addon_rate"] ?? 0;

        if ($request->hasFile('image')) {
            $lawyer_attributes["image"] =  time().'.'.$request->file('image')->clientExtension();
            $request->file('image')->storeAs('public/images/uploads', $lawyer_attributes["image"], ["visibility" => "public"]);
        }

        return $lawyer_attributes;
    }
}
