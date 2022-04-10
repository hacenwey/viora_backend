<?php

namespace App\Models;

use TypiCMS\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Category extends Model implements Searchable
{
    use NestableTrait;


    protected $fillable = ['title', 'slug', 'summary', 'photo', 'status', 'is_parent', 'parent_id', 'added_by'];

    public static function treeList()
    {
        return Category::orderByRaw('-title ASC')
            ->get()
            ->nest()
            ->listsFlattened('title');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

    /**
     * Get all of the collection for the product.
     */
    public function collections()
    {
        return $this->morphToMany(Collection::class, 'collectable');
    }

    // public function parent_info()
    // {
    //     return $this->hasOne('App\Models\Tenant\Category', 'id', 'parent_id');
    // }
    public static function getAllCategory()
    {
        return  Category::orderBy('id', 'DESC')->paginate(10);
    }

    public static function shiftChild($cat_id)
    {
        return Category::whereIn('id', $cat_id)->update(['is_parent' => 1]);
    }

    public static function getChildByParentID($id)
    {
        return Category::where('parent_id', $id)->with(['children'])->orderBy('id', 'ASC')->pluck('title', 'id');
    }

    public function child_cat()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->where('status', 'active');
    }

    public static function getAllParentWithChild()
    {
        return Category::with('child_cat')
            ->where('is_parent', 1)
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get();
    }
    // public function products(){
    //     return $this->hasMany('App\Models\Product','cat_id','id')->where('status','active');
    // }

    // public function sub_products(){
    //     return $this->hasMany('App\Models\Product','child_cat_id','id')->where('status','active');
    // }
    public static function getProductByCat($slug)
    {
        // $category = Category::where('slug', $slug)->first();
        return Category::with('products')->where('slug', $slug)->first();
        // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    }
    public static function getProductBySubCat($slug)
    {
        // return $slug;
        return Category::with('sub_products')->where('slug', $slug)->first();
    }
    public static function countActiveCategory()
    {
        $data = Category::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    public function getSearchResult(): SearchResult
     {
        $url = route('admin.category.edit', $this->id);

         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
         );
     }
}
