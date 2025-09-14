<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\PostTag;
use App\Models\Product;
use App\Models\Category;
use App\Models\Settings;
use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\User;
use App\Models\PostCategory;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  
        settings()->set('app_name', 'viora');
        settings()->set('description', "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde omnis iste natus error sit voluptatem Excepteu sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspi deserunt mollit anim id est laborum. sed ut perspi.");
        settings()->set('short_des', "Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue, magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.");
        settings()->set('logo', asset('assets/images/parallax/11.jpg'));
        settings()->set('address', "تفرغ زينه شارع وزارة الخارجية، نواكشوط، موريتانيا");
        settings()->set('email', 'contacts@viora.mr');
        settings()->set('phone', '+222 26002222');
        settings()->set('currency_code', 'MRU');
        settings()->set('under_value_section', '1000');
        settings()->set('newsletter_title', "KNOW IT ALL FIRST!");
        settings()->set('newsletter_desc', "Never Miss Anything From viora By Signing Up To Our Newsletter.");
        settings()->set('copyrights', 'Copyright © '.date("Y").' <a href="https://iprod.mr" target="_blank"> 1991 Technologies</a>  -  All Rights Reserved.');
        settings()->set('middle_background', asset('assets/images/parallax/11.jpg'));
        settings()->set('middle_section_content', '<h2>2022</h2><h3>top trends</h3><h4>special offer</h4>');


        Banner::insert([
            [
                'title'         => 'Banner 1',
                'slug'          => 'banner-1',
                'photo'         => 'https://via.placeholder.com/450x150',
                'description'   => '<h2><span style=\"font-weight: bold; color: rgb(99, 99, 99);\">Up to 10%</span></h2>',
                'status'        => 'active',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title'         => 'Banner 2',
                'slug'          => 'banner-2',
                'photo'         => 'https://via.placeholder.com/450x150',
                'description'   => '<h2><span style=\"font-weight: bold; color: rgb(99, 99, 99);\">Up to 5%</span></h2>',
                'status'        => 'active',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        Brand::insert([
            [
                'title'         => 'Adidas',
                'slug'          => 'adidas',
                'status'        => 'active',
                'logo'          => 'https://e-marsa.s3.us-east-2.amazonaws.com/adidas.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title'         => 'Nike',
                'slug'          => 'nike',
                'status'        => 'active',
                'logo'          => 'https://e-marsa.s3.us-east-2.amazonaws.com/nike.jpg',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);


        Attribute::insert([
            [
                'code'          =>  'size',
                'name'          =>  'Size',
                'frontend_type' =>  'radio',
                'is_filterable' =>  1,
                'is_required'   =>  1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'code'          =>  'color',
                'name'          =>  'Color',
                'frontend_type' =>  'radio',
                'is_filterable' =>  1,
                'is_required'   =>  1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);

        AttributeValue::insert([
            [
                'attribute_id'  =>  1,
                'value'         =>  'sm',
                'price'         =>  0,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id'  =>  1,
                'value'         =>  'md',
                'price'         =>  '100.00',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id'  =>  1,
                'value'         =>  'lg',
                'price'         =>  '200.00',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id'  =>  2,
                'value'         =>  'black',
                'price'         =>  0,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id'  =>  2,
                'value'         =>  'blue',
                'price'         =>  0,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id'  =>  2,
                'value'         =>  'red',
                'price'         =>  200.00,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id'  =>  2,
                'value'         =>  'orange',
                'price'         =>  200.00,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

    
    }
}
