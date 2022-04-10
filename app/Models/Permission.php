<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    public $table = 'permissions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function defaultPermissions()
    {
        return [
            'access_store',
            'access_blog',
            'content_page_access',
            'access_general_settings',
            'access_attributes',
            'access_payments',
            'access_cities',

            'access_users',
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'access_roles',
            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

            'access_permissions',
            'view_permissions',
            'add_permissions',
            'edit_permissions',
            'delete_permissions',

            'access_products',
            'view_products',
            'add_products',
            'edit_products',
            'delete_products',

            'access_categories',
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',

            'access_brands',
            'view_brands',
            'add_brands',
            'edit_brands',
            'delete_brands',

            'access_banners',
            'view_banners',
            'add_banners',
            'edit_banners',
            'delete_banners',

            'access_coupons',
            'view_coupons',
            'add_coupons',
            'edit_coupons',
            'delete_coupons',

            'access_messages',
            'view_messages',
            'add_messages',
            'edit_messages',
            'delete_messages',

            'access_orders',
            'view_orders',
            'add_orders',
            'edit_orders',
            'delete_orders',

            'access_posts',
            'view_posts',
            'add_posts',
            'edit_posts',
            'delete_posts',

            'access_post_categories',
            'view_post_categories',
            'add_post_categories',
            'edit_post_categories',
            'delete_post_categories',

            'access_post_comments',
            'view_post_comments',
            'add_post_comments',
            'edit_post_comments',
            'delete_post_comments',

            'access_post_tags',
            'view_post_tags',
            'add_post_tags',
            'edit_post_tags',
            'delete_post_tags',

            'access_product_reviews',
            'view_product_reviews',
            'add_product_reviews',
            'edit_product_reviews',
            'delete_product_reviews',

            'access_settings',
            'view_settings',
            'add_settings',
            'edit_settings',
            'delete_settings',

            'access_shipping',
            'view_shipping',
            'add_shipping',
            'edit_shipping',
            'delete_shipping',

            'access_users',
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

        ];
    }
}
