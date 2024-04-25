<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a default user
        User::create([
            'name' => 'product-custom-field.myshopify.com',
            'email' => 'shop@product-custom-field.myshopify.com',
            'password' => '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',
            'shopify_grandfathered' => 0,
            'shopify_freemium' => 0,

        ]);
    }
}
//             'password' => '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',

// product-custom-field.myshopify.com
// shop@product-custom-field.myshopify.com
// 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
// 0