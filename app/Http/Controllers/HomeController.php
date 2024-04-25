<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ShopifyOrder;
use App\Mail\TestEmail;
use Storage;
use Osiset\ShopifyApi\ShopifyApi;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Log;
use Osiset\ShopifyApp\Util;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



    public function home()
    {
        $client = new Client();
        $apiEndpoint = 'https://'.env('SHOPIFY_STORE').'/admin/api/2023-10/products.json'; // Update with your shop URL and API version
        $response = $client->get($apiEndpoint, [
            'headers' => [
                'X-Shopify-Access-Token' => env('SHOPIFY_APP_ACCESS_TOKEN'), // Access Token
                'X-Shopify-Api-Key' => env('SHOPIFY_APP_API'), // API Key
                'Content-Type' => 'application/json',
            ],
        ]);

        
        $body = $response->getBody()->getContents();
        $products_data = json_decode($body, true);
        return view('product_grid', ['products' => $products_data['products']]);
    }

        // Function to generate a random image URL
    public function getRandomImageUrl($width = 150, $height = 150) {
            // Construct the URL with desired width and height
            return "https://via.placeholder.com/{$width}x{$height}";
        }

    public function product_store(Request $request)
    {   
        $client = new Client();
        // Product data
        $productData = [
            'product' => [
                'title' => $request->product_name,
                'body_html' => $request->product_desc,
                'vendor' => 'Example Vendor',
                'product_type' => $request->product_type,
                // 'product_category' => 'tshirt', 
                'tags' => 'example, product',
                'images' => [
                    [
                        'src' => 'https://via.placeholder.com/50x50',
                        'position' => 1,
                        'alt' => 'Example Image'
                    ]
                ],
                'variants' => [
                    [
                        'price' => $request->product_price,
                        'inventory_management' => 'shopify', // Assuming Shopify manages inventory
                        'inventory_quantity' => 10, // Adjust as needed
                    ]
                ],
                'published' => true,
                'published_scope' => 'web',
                // 'collections' => [
                //     ['id' => 'collection_id_1'], // Replace 'collection_id_1' with the actual ID of the collection
                //     ['id' => 'collection_id_2'] // Add more collection IDs if needed
                // ]
            ]
        ];

        $apiEndpoint = 'https://' . env('SHOPIFY_STORE') . '/admin/api/2023-10/products.json';
        $response = $client->post($apiEndpoint, [
            'headers' => [
                'X-Shopify-Access-Token' => env('SHOPIFY_APP_ACCESS_TOKEN'),
                'X-Shopify-Api-Key' => env('SHOPIFY_APP_API'),
                'Content-Type' => 'application/json',
            ],
            'json' => $productData,
        ]);
        
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        return redirect()->back()->with('success', 'Data has been stored successfully!');
    }

    public function order_list(Request $request)
    {
        // $graphqlEndpoint = 'https://miraiclinical.myshopify.com/admin/api/2024-04/graphql.json';
        $graphqlEndpoint = 'https://'.env('SHOPIFY_STORE').'/admin/api/2023-10/graphql.json'; // Update with your shop URL and API version

        $query = <<<'GRAPHQL'
        {
            orders(first: 10) {
                edges {
                    node {
                        id
                        name
                        createdAt
                        customer{
                            id
                            firstName
                            email
                            lastName
                            phone
                            defaultAddress {
                                firstName
                                lastName
                                address1
                                city
                                province
                                zip
                                country
                              }
                        }
                        billingAddress {
                            firstName
                            lastName
                            address1
                            city
                            province
                            zip
                            country
                          }
                          shippingAddress {
                            firstName
                            lastName
                            address1
                            city
                            province
                            zip
                            country
                          }

                        totalPriceSet {
                            shopMoney {
                                amount
                                currencyCode
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;
        
        // Make GraphQL Request
        $client = new Client();
        $response = $client->post($graphqlEndpoint, [
            'headers' => [
                'X-Shopify-Access-Token' => env('SHOPIFY_APP_ACCESS_TOKEN'),
                'X-Shopify-Api-Key' => env('SHOPIFY_APP_API'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'query' => $query,
            ],
        ]);
        
        // Process Response
        $data = json_decode($response->getBody()->getContents(), true);
        $orders = $data['data']['orders']['edges'];
        // dd($orders);
        foreach ($orders as $order) {
            $orderData = $order['node'];
            // print_r($orderData);
                // Fetch customer data
                $customerData = $orderData['customer'];
                $customerName = $customerData['firstName'] . ' ' . $customerData['lastName'];
                $customerEmail = $customerData['email'];
                // Check if OrderID already exists
                $existingRecord = ShopifyOrder::where('order_id', $orderData['name'])->first();
                if (!$existingRecord) {
                    // OrderID does not exist, create new record
                    ShopifyOrder::create([
                        'order_id' => $orderData['name'],
                        'customer_name' => $customerName,
                        'customer_email' => $customerEmail,
                        'order_price' => $orderData['totalPriceSet']['shopMoney']['amount'] . ' ' . $orderData['totalPriceSet']['shopMoney']['currencyCode'] ,
                        'order_create' => $orderData['createdAt'],
                        'defualt_address' => $orderData['customer']['defaultAddress']['address1'],
                        'shipping_address' => $orderData['shippingAddress']['address1'],
                        'billing_address' => $orderData['billingAddress']['address1'],

                    ]);
                }
        }
        return view('order_grid', ['orders' => $orders]);


        
        
        //   
        //   
            
        //     // $lastShippingPhotoEvent = null; // Variable to store the last Shipping Photo event
        //     if ($lastShippingPhotoEvent !== null) { // If a Shipping Photo event was found
        //         // Get the index of the last attachment
        //         // Get the file contents from the URL
        //         // $fileContents_base = file_get_contents($attachment_url);
        //         // $filename = basename($attachment_name);
        //         // $path = Storage::disk('public')->put('images/' . $filename, $fileContents_base);
                
        //         // Get the full URL to the stored image
        //         // $imageUrl = asset('storage/' . $imagePath);
        //         // $getFile = asset('storage/images/'.$attachment_name);
        //         // $attachmentData = Storage::disk('images')->get('aoyD9J1uidQm4RmxUapIzgrhLrQFEpL6BtHGJDZs.png');
        //         // $fileContents = file_get_contents($attachmentData);
        //         // $attachment_name = 'images/qrcode.png'; // Ensure the correct path
        //         // $attachmentData = Storage::disk('public')->get($attachment_name);
        //         // $imageInfo = getimagesizefromstring($attachmentData);
                
                

                
    
                    
        //         // } else {
        //         //     // OrderID already exists, handle as needed (e.g., update existing record)
        //         //     // You can add code here to update existing records if necessary
        //         //     $existingRecord->update(['attachment_url' => $attachment_name]);
        //         // }
                
        //         //--------------------------------------------------------------------------
        //         // Load all record whose email is not sent yet!
        //         // $sendEmailToTheseOrders = ShopifyOrders::where('email_sent', 0)->get();
        //         //---------------------------------------------------------------------------
                
        //     }
        // }

    }
}
