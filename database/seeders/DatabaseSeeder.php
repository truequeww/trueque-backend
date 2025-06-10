<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Thing;
use App\Models\Category;
use App\Models\Material;
use App\Models\Color;
use App\Models\Condition;
use App\Models\OfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        // Create categories
        $categories = [
            "Electronics", "Appliances", "Clothing", "Furniture", "Automotive",
            "Food & Beverage", "Services", "Toys", "Health & Beauty", "Books", "Others"
        ];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        // Create materials
        $materials = [
            "Wood", "Metal", "Plastic", "Glass", "Fabric", "Paper",
            "Stone", "Rubber", "Concrete", "Others"
        ];
        foreach ($materials as $material) {
            Material::create(['name' => $material]);
        }

        // Create colors
        $colors = [
            "Red", "Blue", "Green", "Yellow", "Black", "White",
            "Gray", "Brown", "Orange", "Others"
        ];
        foreach ($colors as $color) {
            Color::create(['name' => $color]);
        }

        // Create conditions
        $conditions = ['New', 'Like New', 'Refurbished', 'Acceptable', 'Good'];
        foreach ($conditions as $condition) {
            Condition::create(['name' => $condition]);
        }

        // Create offer statuses
        $offerStatuses = ['accepted', 'accepted-aware', 'rejected', 'rejected-aware', 'pending'];
        foreach ($offerStatuses as $status) {
            OfferStatus::create(['name' => $status]);
        }

        $truequiEn = User::create([
            'name' => 'Truequi',
            'email' => 'truequeww@gmail.com',
            'email_verified_at' => now(),
            'profile_picture' => "https://images.pexels.com/photos/6302292/pexels-photo-6302292.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
            'password' => bcrypt('password'),
            'language' => 'en',
            'location' => json_encode([
                'street' => $this->faker->streetAddress,
                'latitude' => 40.7128,
                'longitude' => -74.0060
            ])
        ]);

        $truequiEs = User::create([
            'name' => 'Truequi',
            'email' => 'truequees@gmail.com',
            'email_verified_at' => now(),
            'profile_picture' => "https://images.pexels.com/photos/6302292/pexels-photo-6302292.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
            'password' => bcrypt('password'),
            'language' => 'es',
            'location' => json_encode([
                'street' => $this->faker->streetAddress,
                'latitude' => 40.7128,
                'longitude' => -74.0060
            ])
        ]);


        // Create User 1 (English, Global locations)
        $user1 = User::create([
            'name' => 'Alex Johnson',
            'email' => 'alex.johnson@example.com',
            'email_verified_at' => now(),
            'profile_picture' => "https://static.wikia.nocookie.net/megamitensei/images/4/4c/PERSONA5_package_visual.png/revision/latest?cb=20250321105243",
            'password' => bcrypt('password'),
            'language' => 'en',
            'location' => json_encode([
                'street' => $this->faker->streetAddress,
                'latitude' => 40.7128,
                'longitude' => -74.0060
            ])
        ]);

        // Create User 2 (Spanish, Latin America)
        $user2 = User::create([
            'name' => 'María González',
            'email' => 'maria.gonzalez@example.com',
            'email_verified_at' => now(),
            'profile_picture' => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRS-z31-byNeE307ReTDio0-9Y6_k0DnTTAhA&s",
            'password' => bcrypt('password'),
            'language' => 'es',
            'location' => json_encode([
                'street' => $this->faker->streetAddress,
                'latitude' => 19.4326,
                'longitude' => -99.1332
            ])
        ]);

        // Create 100 things for User 1 (English)
        $this->createUser1Things($user1);

        // Create 100 things for User 2 (Spanish)
        $this->createUser2Things($user2);

        $this->createTruequiEnThings($truequiEn);

        $this->createTruequiEsThings($truequiEs);
    }

    private function createUser1Things($user)
    {
        $globalLocations = [
            ['city' => 'New York', 'country' => 'USA', 'lat' => 40.7128, 'lng' => -74.0060],
            ['city' => 'Los Angeles', 'country' => 'USA', 'lat' => 34.0522, 'lng' => -118.2437],
            ['city' => 'London', 'country' => 'UK', 'lat' => 51.5074, 'lng' => -0.1278],
            ['city' => 'Berlin', 'country' => 'Germany', 'lat' => 52.5200, 'lng' => 13.4050],
            ['city' => 'Tokyo', 'country' => 'Japan', 'lat' => 35.6762, 'lng' => 139.6503],
            ['city' => 'Sydney', 'country' => 'Australia', 'lat' => -33.8688, 'lng' => 151.2093],
            ['city' => 'Cape Town', 'country' => 'South Africa', 'lat' => -33.9249, 'lng' => 18.4241],
            ['city' => 'Singapore', 'country' => 'Singapore', 'lat' => 1.3521, 'lng' => 103.8198]
        ];

        $user1Items = [
            // Electronics
            ['name' => 'iPhone 13 Pro', 'desc' => 'Gently used iPhone 13 Pro in excellent condition. 256GB storage, unlocked.', 'price' => 650.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Blue', 'condition' => 'Like New', 'weight' => 0.2, 'image' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?w=500'],
            ['name' => 'MacBook Air M1', 'desc' => 'Reliable MacBook Air with M1 chip. Perfect for students or professionals.', 'price' => 800.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Gray', 'condition' => 'Good', 'weight' => 1.3, 'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500'],
            ['name' => 'Sony WH-1000XM4 Headphones', 'desc' => 'Premium noise-canceling headphones with excellent sound quality.', 'price' => 250.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.25, 'image' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=500'],
            ['name' => 'Nintendo Switch', 'desc' => 'Complete Nintendo Switch console with dock and Joy-Cons.', 'price' => 280.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Others', 'condition' => 'Good', 'weight' => 0.4, 'image' => 'https://images.unsplash.com/photo-1578303512597-81e6cc155b3e?w=500'],
            ['name' => 'Samsung Galaxy Tab S7', 'desc' => 'Android tablet perfect for drawing and productivity. Includes S Pen.', 'price' => 400.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.5, 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500'],
            ['name' => 'Canon EOS R5 Camera', 'desc' => 'Professional mirrorless camera with 45MP sensor. Excellent for photography.', 'price' => 2500.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.7, 'image' => 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500'],
            ['name' => 'iPad Pro 12.9"', 'desc' => 'Large iPad Pro with Apple Pencil support. Great for creative work.', 'price' => 900.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Gray', 'condition' => 'Good', 'weight' => 0.68, 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500'],
            ['name' => 'Apple Watch Series 7', 'desc' => 'Smartwatch with health tracking and GPS. 45mm case.', 'price' => 300.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Blue', 'condition' => 'Like New', 'weight' => 0.05, 'image' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=500'],
            ['name' => 'Gaming PC Setup', 'desc' => 'Custom built gaming PC with RTX 3080, AMD Ryzen 7, 32GB RAM.', 'price' => 1800.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=500'],
            ['name' => 'DJI Mavic Air 2 Drone', 'desc' => 'Compact drone with 4K camera and intelligent flight modes.', 'price' => 600.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Gray', 'condition' => 'Like New', 'weight' => 0.57, 'image' => 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?w=500'],

            // Furniture
            ['name' => 'Mid-Century Modern Sofa', 'desc' => 'Comfortable 3-seater sofa in excellent condition. Perfect for living room.', 'price' => 450.00, 'category' => 'Furniture', 'material' => 'Fabric', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 45.0, 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500'],
            ['name' => 'Oak Dining Table', 'desc' => 'Solid oak dining table seats 6 people. Timeless design.', 'price' => 300.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 35.0, 'image' => 'https://images.unsplash.com/photo-1549497538-303791108f95?w=500'],
            ['name' => 'Office Desk Chair', 'desc' => 'Ergonomic office chair with lumbar support. Great for working from home.', 'price' => 150.00, 'category' => 'Furniture', 'material' => 'Fabric', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 12.0, 'image' => 'https://images.unsplash.com/photo-1541558869434-2840d308329a?w=500'],
            ['name' => 'Bookshelf Unit', 'desc' => '5-tier wooden bookshelf perfect for books and decorative items.', 'price' => 80.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 25.0, 'image' => 'https://images.unsplash.com/photo-1594736797933-d0401ba2fe65?w=500'],
            ['name' => 'Queen Size Bed Frame', 'desc' => 'Minimalist platform bed frame in excellent condition.', 'price' => 200.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Like New', 'weight' => 30.0, 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=500'],

            // Clothing
            ['name' => 'Vintage Leather Jacket', 'desc' => 'Authentic vintage leather jacket, size M. Classic biker style.', 'price' => 120.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Black', 'condition' => 'Good', 'weight' => 1.2, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500'],
            ['name' => 'Designer Handbag', 'desc' => 'Authentic designer handbag in pristine condition. Perfect for special occasions.', 'price' => 350.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Brown', 'condition' => 'Like New', 'weight' => 0.8, 'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500'],
            ['name' => 'Wool Winter Coat', 'desc' => 'High-quality wool coat, size L. Perfect for cold weather.', 'price' => 80.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Gray', 'condition' => 'Good', 'weight' => 1.5, 'image' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=500'],
            ['name' => 'Running Shoes Collection', 'desc' => 'Bundle of 3 pairs of premium running shoes, various sizes 9-10.', 'price' => 200.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Others', 'condition' => 'Good', 'weight' => 2.0, 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500'],
            ['name' => 'Formal Suits (2pc)', 'desc' => 'Two professional suits, sizes 40R and 42R. Dry cleaned and ready.', 'price' => 180.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 2.5, 'image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=500'],

            // Automotive
            ['name' => 'Car Maintenance Tools', 'desc' => 'Complete set of automotive tools including wrenches, sockets, and more.', 'price' => 150.00, 'category' => 'Automotive', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Good', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1530046339160-ce3e530c7d2f?w=500'],
            ['name' => 'Winter Tire Set', 'desc' => 'Set of 4 winter tires, 225/60R16. Good tread remaining.', 'price' => 300.00, 'category' => 'Automotive', 'material' => 'Rubber', 'color' => 'Black', 'condition' => 'Good', 'weight' => 60.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'GPS Navigation System', 'desc' => 'Portable GPS unit with lifetime map updates. Perfect for road trips.', 'price' => 80.00, 'category' => 'Automotive', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.3, 'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500'],

            // Books & Education
            ['name' => 'Programming Books Collection', 'desc' => 'Collection of 15 programming books covering Python, JavaScript, and more.', 'price' => 120.00, 'category' => 'Books', 'material' => 'Paper', 'color' => 'Others', 'condition' => 'Good', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=500'],
            ['name' => 'Classic Literature Set', 'desc' => 'Beautiful hardcover collection of classic novels. Perfect for book lovers.', 'price' => 100.00, 'category' => 'Books', 'material' => 'Paper', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 12.0, 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500'],
            ['name' => 'Medical Textbooks', 'desc' => 'Current edition medical textbooks for students. Anatomy and physiology.', 'price' => 200.00, 'category' => 'Books', 'material' => 'Paper', 'color' => 'Others', 'condition' => 'Good', 'weight' => 10.0, 'image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=500'],

            // Toys & Games
            ['name' => 'LEGO Architecture Collection', 'desc' => 'Rare LEGO Architecture sets including famous landmarks. Complete sets.', 'price' => 180.00, 'category' => 'Toys', 'material' => 'Plastic', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=500'],
            ['name' => 'Board Game Bundle', 'desc' => 'Collection of premium board games including Settlers of Catan and more.', 'price' => 150.00, 'category' => 'Toys', 'material' => 'Paper', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 5.0, 'image' => 'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=500'],
            ['name' => 'Remote Control Helicopter', 'desc' => 'Professional RC helicopter with camera. Perfect for aerial photography.', 'price' => 250.00, 'category' => 'Toys', 'material' => 'Metal', 'color' => 'Red', 'condition' => 'Good', 'weight' => 2.0, 'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500'],

            // Appliances
            ['name' => 'Espresso Machine', 'desc' => 'Professional-grade espresso machine. Make café-quality coffee at home.', 'price' => 400.00, 'category' => 'Appliances', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=500'],
            ['name' => 'Air Fryer', 'desc' => 'Large capacity air fryer for healthy cooking. Barely used.', 'price' => 80.00, 'category' => 'Appliances', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 5.0, 'image' => 'https://images.unsplash.com/photo-1585515656706-e7d6d0ce2e04?w=500'],
            ['name' => 'Stand Mixer', 'desc' => 'Heavy-duty stand mixer perfect for baking. Multiple attachments included.', 'price' => 200.00, 'category' => 'Appliances', 'material' => 'Metal', 'color' => 'Red', 'condition' => 'Good', 'weight' => 10.0, 'image' => 'https://images.unsplash.com/photo-1578643463396-0325bd6eafd4?w=500'],

            // Health & Beauty
            ['name' => 'Yoga Equipment Set', 'desc' => 'Complete yoga set with mat, blocks, straps, and carrying bag.', 'price' => 60.00, 'category' => 'Health & Beauty', 'material' => 'Rubber', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500'],
            ['name' => 'Massage Chair', 'desc' => 'Electric massage chair with multiple settings. Great for relaxation.', 'price' => 800.00, 'category' => 'Health & Beauty', 'material' => 'Fabric', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 50.0, 'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=500'],

            // Services
            ['name' => 'Web Design Service', 'desc' => 'Professional web design service. Custom websites for small businesses.', 'price' => 500.00, 'category' => 'Services', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 0.0, 'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=500'],
            ['name' => 'Photography Session', 'desc' => 'Professional portrait photography session. Includes editing and prints.', 'price' => 200.00, 'category' => 'Services', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 0.0, 'image' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=500'],
            ['name' => 'Music Lessons', 'desc' => 'Guitar lessons for beginners. 4-week course with experienced instructor.', 'price' => 150.00, 'category' => 'Services', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 0.0, 'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=500'],

            // Collectibles & Miscellaneous
            ['name' => 'Vintage Vinyl Records', 'desc' => 'Collection of 50 vintage vinyl records from the 70s and 80s. Rare finds.', 'price' => 300.00, 'category' => 'Others', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Good', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=500'],
            ['name' => 'Art Supplies Kit', 'desc' => 'Professional art supplies including paints, brushes, and canvases.', 'price' => 120.00, 'category' => 'Others', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 5.0, 'image' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=500'],
            ['name' => 'Camping Gear Bundle', 'desc' => 'Complete camping setup: tent, sleeping bags, portable stove, and more.', 'price' => 250.00, 'category' => 'Others', 'material' => 'Fabric', 'color' => 'Green', 'condition' => 'Good', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=500'],
            ['name' => 'Garden Tools Set', 'desc' => 'High-quality garden tools for the green thumb. Includes shovel, rake, pruners.', 'price' => 80.00, 'category' => 'Others', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 6.0, 'image' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=500'],
            ['name' => 'Aquarium Setup', 'desc' => '50-gallon aquarium with filter, heater, and decorations. Fish not included.', 'price' => 200.00, 'category' => 'Others', 'material' => 'Glass', 'color' => 'Others', 'condition' => 'Good', 'weight' => 25.0, 'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=500'],

            // Additional Electronics
            ['name' => 'Smart TV 55"', 'desc' => 'Samsung 55" 4K Smart TV with HDR. Excellent picture quality.', 'price' => 450.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Good', 'weight' => 18.0, 'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=500'],
            ['name' => 'Bluetooth Speaker', 'desc' => 'Portable Bluetooth speaker with excellent bass. Perfect for parties.', 'price' => 60.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Blue', 'condition' => 'Like New', 'weight' => 1.0, 'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500'],
            ['name' => 'Mechanical Keyboard', 'desc' => 'RGB mechanical keyboard with blue switches. Great for gaming and typing.', 'price' => 100.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 1.2, 'image' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=500'],
            ['name' => 'Webcam 4K', 'desc' => 'High-quality 4K webcam for streaming and video calls.', 'price' => 80.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'New', 'weight' => 0.2, 'image' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=500'],
            ['name' => 'External Hard Drive 2TB', 'desc' => 'Portable 2TB external hard drive for backup and storage.', 'price' => 70.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.3, 'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500'],

            // More Furniture
            ['name' => 'Coffee Table', 'desc' => 'Modern glass coffee table perfect for living room centerpiece.', 'price' => 120.00, 'category' => 'Furniture', 'material' => 'Glass', 'color' => 'Others', 'condition' => 'Good', 'weight' => 20.0, 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500'],
            ['name' => 'Wardrobe Cabinet', 'desc' => 'Large wardrobe with mirror doors. Perfect for bedroom storage.', 'price' => 180.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'White', 'condition' => 'Good', 'weight' => 40.0, 'image' => 'https://images.unsplash.com/photo-1549497538-303791108f95?w=500'],
            ['name' => 'Nightstand Set', 'desc' => 'Pair of matching nightstands with drawers. Solid wood construction.', 'price' => 100.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500'],

            // More Clothing
            ['name' => 'Designer Jeans Collection', 'desc' => 'Bundle of 5 premium designer jeans, various sizes and styles.', 'price' => 200.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Blue', 'condition' => 'Good', 'weight' => 2.5, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500'],
            ['name' => 'Luxury Watch', 'desc' => 'Swiss-made luxury watch with leather strap. Automatic movement.', 'price' => 800.00, 'category' => 'Clothing', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 0.1, 'image' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=500'],
            ['name' => 'Athletic Wear Bundle', 'desc' => 'Complete workout wardrobe: leggings, sports bras, tank tops, and more.', 'price' => 80.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Others', 'condition' => 'Good', 'weight' => 1.5, 'image' => 'https://images.unsplash.com/photo-1506629905607-d3ac84e8bd10?w=500'],

            // More Appliances
            ['name' => 'Food Processor', 'desc' => 'Multi-function food processor with various attachments. Great for cooking.', 'price' => 90.00, 'category' => 'Appliances', 'material' => 'Plastic', 'color' => 'White', 'condition' => 'Like New', 'weight' => 4.0, 'image' => 'https://images.unsplash.com/photo-1585515656706-e7d6d0ce2e04?w=500'],
            ['name' => 'Vacuum Cleaner', 'desc' => 'Powerful bagless vacuum cleaner with HEPA filter. Perfect for pet owners.', 'price' => 150.00, 'category' => 'Appliances', 'material' => 'Plastic', 'color' => 'Others', 'condition' => 'Good', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Microwave Oven', 'desc' => 'Countertop microwave with convection feature. Stainless steel finish.', 'price' => 120.00, 'category' => 'Appliances', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Good', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1585515656706-e7d6d0ce2e04?w=500'],

            // More Health & Beauty
            ['name' => 'Exercise Equipment Set', 'desc' => 'Home gym equipment: dumbbells, resistance bands, and exercise mat.', 'price' => 120.00, 'category' => 'Health & Beauty', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 20.0, 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500'],
            ['name' => 'Essential Oils Collection', 'desc' => 'Premium essential oils set with diffuser. Perfect for aromatherapy.', 'price' => 80.00, 'category' => 'Health & Beauty', 'material' => 'Glass', 'color' => 'Others', 'condition' => 'New', 'weight' => 1.0, 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500'],

            // More Services
            ['name' => 'Home Cleaning Service', 'desc' => 'Professional home cleaning service. Deep clean for 3-bedroom house.', 'price' => 100.00, 'category' => 'Services', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 0.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Personal Training Sessions', 'desc' => 'One-on-one personal training sessions. 8-week fitness program.', 'price' => 300.00, 'category' => 'Services', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 0.0, 'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=500'],
            ['name' => 'Tax Preparation Service', 'desc' => 'Professional tax preparation for individuals and small businesses.', 'price' => 80.00, 'category' => 'Services', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 0.0, 'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=500'],

            // More Collectibles & Miscellaneous
            ['name' => 'Musical Instruments Bundle', 'desc' => 'Acoustic guitar, keyboard, and drum pad. Perfect for music enthusiasts.', 'price' => 400.00, 'category' => 'Others', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 10.0, 'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=500'],
            ['name' => 'Coin Collection', 'desc' => 'Rare coin collection including silver dollars and foreign currencies.', 'price' => 250.00, 'category' => 'Others', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Good', 'weight' => 1.0, 'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?w=500'],
            ['name' => 'Power Tools Set', 'desc' => 'Professional power tools including drill, saw, sander, and carrying case.', 'price' => 300.00, 'category' => 'Others', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Good', 'weight' => 12.0, 'image' => 'https://images.unsplash.com/photo-1530046339160-ce3e530c7d2f?w=500'],
            ['name' => 'Chess Set Collection', 'desc' => 'Premium wooden chess sets with hand-carved pieces. Various styles.', 'price' => 150.00, 'category' => 'Others', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Like New', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=500'],
            ['name' => 'Bicycle Accessories', 'desc' => 'Complete bike accessory kit: helmet, lights, lock, pump, and repair tools.', 'price' => 100.00, 'category' => 'Others', 'material' => 'Others', 'color' => 'Others', 'condition' => 'Good', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],

            // Food & Beverage
            ['name' => 'Gourmet Coffee Beans', 'desc' => 'Premium single-origin coffee beans from various regions. 5lb variety pack.', 'price' => 60.00, 'category' => 'Food & Beverage', 'material' => 'Others', 'color' => 'Brown', 'condition' => 'New', 'weight' => 2.3, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=500'],
            ['name' => 'Wine Collection', 'desc' => 'Selection of 12 bottles from renowned vineyards. Perfect for collectors.', 'price' => 300.00, 'category' => 'Food & Beverage', 'material' => 'Glass', 'color' => 'Others', 'condition' => 'New', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=500'],
            ['name' => 'Artisan Honey Set', 'desc' => 'Local artisan honey collection with unique flavors and health benefits.', 'price' => 40.00, 'category' => 'Food & Beverage', 'material' => 'Glass', 'color' => 'Others', 'condition' => 'New', 'weight' => 2.0, 'image' => 'https://images.unsplash.com/photo-1587049633312-d628ae50a8ae?w=500'],

            // More Electronics to reach 100
            ['name' => 'Fitness Tracker', 'desc' => 'Advanced fitness tracker with heart rate monitor and GPS.', 'price' => 120.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.05, 'image' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=500'],
            ['name' => 'Home Security Camera', 'desc' => 'Smart security camera with night vision and mobile app control.', 'price' => 90.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'White', 'condition' => 'New', 'weight' => 0.5, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Wireless Earbuds', 'desc' => 'Premium wireless earbuds with noise cancellation and long battery life.', 'price' => 150.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'White', 'condition' => 'Like New', 'weight' => 0.1, 'image' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=500'],
            ['name' => 'Smart Home Hub', 'desc' => 'Central hub for controlling all smart home devices. Voice activated.', 'price' => 80.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Good', 'weight' => 0.6, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Portable Projector', 'desc' => 'Mini portable projector perfect for presentations and movie nights.', 'price' => 200.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 1.0, 'image' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=500'],

            // Additional unique items to complete the 100
            ['name' => 'Vintage Typewriter', 'desc' => 'Working vintage typewriter from the 1960s. Perfect for writers and collectors.', 'price' => 180.00, 'category' => 'Others', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1516906736502-5d3fedc1fc95?w=500'],
            ['name' => 'Telescope', 'desc' => 'Beginner-friendly telescope for stargazing. Includes tripod and accessories.', 'price' => 220.00, 'category' => 'Others', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 5.0, 'image' => 'https://images.unsplash.com/photo-1446776653964-20c1d3a81b06?w=500'],
            ['name' => 'Sewing Machine', 'desc' => 'Computerized sewing machine with multiple stitch patterns and accessories.', 'price' => 250.00, 'category' => 'Others', 'material' => 'Plastic', 'color' => 'White', 'condition' => 'Like New', 'weight' => 12.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Standing Desk Converter', 'desc' => 'Adjustable standing desk converter. Improve your work posture.', 'price' => 120.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 8.0, 'image' => 'https://images.unsplash.com/photo-1541558869434-2840d308329a?w=500'],
            ['name' => 'Fire Pit', 'desc' => 'Portable fire pit perfect for backyard gatherings and camping trips.', 'price' => 80.00, 'category' => 'Others', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=500'],
            ['name' => 'Pressure Washer', 'desc' => 'Electric pressure washer for cleaning driveways, decks, and vehicles.', 'price' => 150.00, 'category' => 'Others', 'material' => 'Plastic', 'color' => 'Others', 'condition' => 'Good', 'weight' => 12.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Pool Table', 'desc' => 'Full-size pool table with all accessories. Great for game room.', 'price' => 800.00, 'category' => 'Others', 'material' => 'Wood', 'color' => 'Green', 'condition' => 'Good', 'weight' => 100.0, 'image' => 'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=500'],
            ['name' => 'Solar Panel Kit', 'desc' => 'Complete solar panel system for off-grid power generation.', 'price' => 500.00, 'category' => 'Others', 'material' => 'Others', 'color' => 'Black', 'condition' => 'New', 'weight' => 25.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Kayak with Paddle', 'desc' => 'Single-person kayak perfect for rivers and lakes. Includes paddle and seat.', 'price' => 300.00, 'category' => 'Others', 'material' => 'Plastic', 'color' => 'Red', 'condition' => 'Good', 'weight' => 20.0, 'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=500'],
            ['name' => 'Beekeeping Starter Kit', 'desc' => 'Complete beekeeping setup with hive, frames, protective gear, and tools.', 'price' => 200.00, 'category' => 'Others', 'material' => 'Wood', 'color' => 'Others', 'condition' => 'New', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1587049633312-d628ae50a8ae?w=500']
        ];

        foreach ($user1Items as $index => $item) {
            $location = $globalLocations[array_rand($globalLocations)];

            Thing::create([
                'user_id' => $user->id,
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'condition_id' => Condition::where('name', $item['condition'])->first()->id,
                'availability' => true,
                'weight' => $item['weight'],
                'category_id' => Category::where('name', $item['category'])->first()->id,
                'material_id' => Material::where('name', $item['material'])->first()->id,
                'color_id' => Color::where('name', $item['color'])->first()->id,
                'location' => json_encode([
                    'street' => $this->faker->streetAddress,
                    'latitude' => $location['lat'] + (rand(-100, 100) / 1000),
                    'longitude' => $location['lng'] + (rand(-100, 100) / 1000),
                ]),
                'imagesUrl' => $item['image'],
            ]);
        }
    }

    private function createUser2Things($user)
    {
        $latinLocations = [
            ['city' => 'Ciudad de México', 'country' => 'México', 'lat' => 19.4326, 'lng' => -99.1332],
            ['city' => 'Buenos Aires', 'country' => 'Argentina', 'lat' => -34.6118, 'lng' => -58.3960],
            ['city' => 'Bogotá', 'country' => 'Colombia', 'lat' => 4.7110, 'lng' => -74.0721],
            ['city' => 'Lima', 'country' => 'Perú', 'lat' => -12.0464, 'lng' => -77.0428],
            ['city' => 'Santiago', 'country' => 'Chile', 'lat' => -33.4489, 'lng' => -70.6693],
            ['city' => 'Caracas', 'country' => 'Venezuela', 'lat' => 10.4806, 'lng' => -66.9036],
            ['city' => 'Quito', 'country' => 'Ecuador', 'lat' => -0.1807, 'lng' => -78.4678],
            ['city' => 'San José', 'country' => 'Costa Rica', 'lat' => 9.9281, 'lng' => -84.0907],
            ['city' => 'Guatemala', 'country' => 'Guatemala', 'lat' => 14.6349, 'lng' => -90.5069],
            ['city' => 'Montevideo', 'country' => 'Uruguay', 'lat' => -34.9011, 'lng' => -56.1645]
        ];

        $user2Items = [
            // Electrónicos (15 items)
            ['name' => 'iPhone 12 Pro Max', 'desc' => 'iPhone en excelente estado, 128GB, desbloqueado para todas las compañías.', 'price' => 15000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Blue', 'condition' => 'Like New', 'weight' => 0.23, 'image' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?w=500'],
            ['name' => 'Laptop Gaming MSI', 'desc' => 'Laptop para juegos con tarjeta gráfica dedicada, 16GB RAM, SSD 512GB.', 'price' => 25000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 2.5, 'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500'],
            ['name' => 'Auriculares Sony WH-1000XM5', 'desc' => 'Auriculares premium con cancelación de ruido activa y sonido de alta calidad.', 'price' => 6500.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.25, 'image' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=500'],
            ['name' => 'PlayStation 5', 'desc' => 'Consola PS5 con control adicional y 3 juegos incluidos.', 'price' => 12000.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'White', 'condition' => 'Good', 'weight' => 4.5, 'image' => 'https://images.unsplash.com/photo-1578303512597-81e6cc155b3e?w=500'],
            ['name' => 'iPad Air', 'desc' => 'Tablet iPad Air con Apple Pencil incluido, perfecto para diseño y trabajo.', 'price' => 11000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Gray', 'condition' => 'Like New', 'weight' => 0.46, 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500'],
            ['name' => 'Cámara Canon EOS R6', 'desc' => 'Cámara profesional mirrorless con lente 24-105mm incluido.', 'price' => 45000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 1.2, 'image' => 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500'],
            ['name' => 'Apple Watch Series 8', 'desc' => 'Reloj inteligente con GPS y monitoreo de salud avanzado.', 'price' => 8000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 0.05, 'image' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=500'],
            ['name' => 'TV Samsung 65 pulgadas', 'desc' => 'Smart TV 4K HDR con Tizen OS y control por voz incluido.', 'price' => 18000.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Good', 'weight' => 25.0, 'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=500'],
            ['name' => 'Drone DJI Mini 3', 'desc' => 'Drone compacto con cámara 4K y modos de vuelo inteligentes.', 'price' => 12000.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Gray', 'condition' => 'Like New', 'weight' => 0.25, 'image' => 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?w=500'],
            ['name' => 'PC Gamer Completa', 'desc' => 'PC armada con RTX 4070, Ryzen 7, 32GB RAM, monitor 27" incluido.', 'price' => 35000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 20.0, 'image' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=500'],
            ['name' => 'Nintendo Switch OLED', 'desc' => 'Consola portátil con pantalla OLED, controles adicionales y estuche.', 'price' => 7500.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Red', 'condition' => 'Like New', 'weight' => 0.42, 'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500'],
            ['name' => 'Altavoces Bluetooth JBL', 'desc' => 'Sistema de sonido inalámbrico resistente al agua, sonido potente.', 'price' => 2500.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Good', 'weight' => 1.2, 'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500'],
            ['name' => 'Router WiFi 6', 'desc' => 'Router de alta velocidad con cobertura amplia y múltiples antenas.', 'price' => 1800.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.8, 'image' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5d8a?w=500'],
            ['name' => 'Microondas Samsung', 'desc' => 'Horno microondas digital con grill, 28 litros, funciones automáticas.', 'price' => 3000.00, 'category' => 'Appliances', 'material' => 'Metal', 'color' => 'White', 'condition' => 'Good', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=500'],
            ['name' => 'Tablet Android 10"', 'desc' => 'Tablet con pantalla de 10 pulgadas, 64GB almacenamiento, ideal para multimedia.', 'price' => 4000.00, 'category' => 'Electronics', 'material' => 'Metal', 'color' => 'Gray', 'condition' => 'Good', 'weight' => 0.5, 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500'],

            // Muebles (12 items)
            ['name' => 'Sofá de Cuero', 'desc' => 'Sofá de 3 plazas en cuero genuino, muy cómodo y en excelente estado.', 'price' => 8000.00, 'category' => 'Furniture', 'material' => 'Others', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 50.0, 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500'],
            ['name' => 'Mesa de Comedor Rústica', 'desc' => 'Mesa de madera maciza para 8 personas con sillas incluidas.', 'price' => 6000.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 60.0, 'image' => 'https://images.unsplash.com/photo-1549497538-303791108f95?w=500'],
            ['name' => 'Silla Ejecutiva', 'desc' => 'Silla ergonómica de oficina con soporte lumbar y brazos ajustables.', 'price' => 3000.00, 'category' => 'Furniture', 'material' => 'Fabric', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1541558869434-2840d308329a?w=500'],
            ['name' => 'Librero Modular', 'desc' => 'Sistema de librero modular de 6 niveles, perfecto para organizar.', 'price' => 2500.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 30.0, 'image' => 'https://images.unsplash.com/photo-1594736797933-d0401ba2fe65?w=500'],
            ['name' => 'Recámara Matrimonial', 'desc' => 'Juego de recámara completo: cama, buró, cómoda y espejo.', 'price' => 12000.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 80.0, 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=500'],
            ['name' => 'Mesa de Centro Moderna', 'desc' => 'Mesa de centro con diseño minimalista y cajones de almacenamiento.', 'price' => 1800.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'White', 'condition' => 'Like New', 'weight' => 20.0, 'image' => 'https://images.unsplash.com/photo-1506439773649-6e0eb8cfb237?w=500'],
            ['name' => 'Armario Ropero', 'desc' => 'Armario de 4 puertas con espejos y compartimentos organizadores.', 'price' => 4500.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 70.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Juego de Patio', 'desc' => 'Mesa redonda con 4 sillas para exterior, resistente al clima.', 'price' => 3500.00, 'category' => 'Furniture', 'material' => 'Metal', 'color' => 'Green', 'condition' => 'Good', 'weight' => 40.0, 'image' => 'https://images.unsplash.com/photo-1506439773649-6e0eb8cfb237?w=500'],
            ['name' => 'Escritorio de Trabajo', 'desc' => 'Escritorio en L con cajones y espacio para computadora, ideal home office.', 'price' => 2800.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 35.0, 'image' => 'https://images.unsplash.com/photo-1581539250439-c96689b516dd?w=500'],
            ['name' => 'Cama Individual', 'desc' => 'Cama sencilla con cabecera tapizada y base con cajones.', 'price' => 3200.00, 'category' => 'Furniture', 'material' => 'Wood', 'color' => 'Gray', 'condition' => 'Like New', 'weight' => 45.0, 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=500'],
            ['name' => 'Estantería Industrial', 'desc' => 'Estante de metal y madera estilo industrial, 5 repisas.', 'price' => 2200.00, 'category' => 'Furniture', 'material' => 'Metal', 'color' => 'Black', 'condition' => 'Good', 'weight' => 25.0, 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500'],
            ['name' => 'Sillón Reclinable', 'desc' => 'Sillón individual reclinable en tela suave, perfecto para relajarse.', 'price' => 4000.00, 'category' => 'Furniture', 'material' => 'Fabric', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 30.0, 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500'],

            // Ropa y Accesorios (15 items)
            ['name' => 'Chamarra de Piel', 'desc' => 'Chamarra de piel genuina estilo motociclista, talla M, poco uso.', 'price' => 3000.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Black', 'condition' => 'Good', 'weight' => 1.5, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500'],
            ['name' => 'Bolsa de Diseñador', 'desc' => 'Bolsa de mano de marca reconocida, en perfecto estado con certificado.', 'price' => 8000.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Brown', 'condition' => 'Like New', 'weight' => 0.8, 'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500'],
            ['name' => 'Abrigo de Lana', 'desc' => 'Abrigo largo de lana para invierno, talla L, color gris elegante.', 'price' => 2000.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Gray', 'condition' => 'Good', 'weight' => 2.0, 'image' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=500'],
            ['name' => 'Colección de Tenis', 'desc' => 'Lote de 4 pares de tenis de marca, tallas variadas 26-28 mexicano.', 'price' => 4000.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Others', 'condition' => 'Good', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500'],
            ['name' => 'Trajes Formales', 'desc' => 'Dos trajes de vestir completos, tallas 38 y 40, perfectos para eventos.', 'price' => 4500.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=500'],
            ['name' => 'Vestidos de Fiesta', 'desc' => 'Colección de 3 vestidos elegantes para ocasiones especiales, talla M.', 'price' => 2800.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 1.5, 'image' => 'https://images.unsplash.com/photo-1566479179817-02e7ec4fbda8?w=500'],
            ['name' => 'Botas de Cuero', 'desc' => 'Botas cowboy de cuero genuino, talla 27, cómodas y resistentes.', 'price' => 2200.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 1.8, 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500'],
            ['name' => 'Jeans de Marca', 'desc' => 'Lote de 5 pantalones jeans de diferentes marcas, tallas 30-32.', 'price' => 1800.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Blue', 'condition' => 'Good', 'weight' => 2.5, 'image' => 'https://images.unsplash.com/photo-1542272454315-7ad9f8bb3c15?w=500'],
            ['name' => 'Reloj de Lujo', 'desc' => 'Reloj suizo automático con correa de cuero, caja original incluida.', 'price' => 12000.00, 'category' => 'Clothing', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Like New', 'weight' => 0.15, 'image' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=500'],
            ['name' => 'Camisas de Vestir', 'desc' => 'Paquete de 6 camisas formales de diferentes colores, talla M-L.', 'price' => 1500.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'White', 'condition' => 'Good', 'weight' => 1.2, 'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=500'],
            ['name' => 'Gafas de Sol', 'desc' => 'Colección de gafas de sol de diseñador, protección UV certificada.', 'price' => 2500.00, 'category' => 'Clothing', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 0.3, 'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=500'],
            ['name' => 'Cinturones de Cuero', 'desc' => 'Set de 3 cinturones de cuero en diferentes colores y hebillas.', 'price' => 800.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Brown', 'condition' => 'Good', 'weight' => 0.6, 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500'],
            ['name' => 'Zapatos Oxford', 'desc' => 'Zapatos formales de cuero para hombre, talla 27, ideales para oficina.', 'price' => 1800.00, 'category' => 'Clothing', 'material' => 'Others', 'color' => 'Black', 'condition' => 'Good', 'weight' => 1.2, 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500'],
            ['name' => 'Chaqueta Deportiva', 'desc' => 'Chaqueta Nike original para running, talla M, material respirable.', 'price' => 1200.00, 'category' => 'Clothing', 'material' => 'Fabric', 'color' => 'Blue', 'condition' => 'Good', 'weight' => 0.5, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500'],
            ['name' => 'Perfumes Importados', 'desc' => 'Colección de 4 perfumes de marca internacional, frascos casi llenos.', 'price' => 3500.00, 'category' => 'Health & Beauty', 'material' => 'Glass', 'color' => 'Others', 'condition' => 'Good', 'weight' => 1.0, 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=500'],

            // Automotriz (10 items)
            ['name' => 'Herramientas Automotrices', 'desc' => 'Kit completo de herramientas mecánicas con caja organizadora incluida.', 'price' => 3500.00, 'category' => 'Automotive', 'material' => 'Metal', 'color' => 'Others', 'condition' => 'Good', 'weight' => 12.0, 'image' => 'https://images.unsplash.com/photo-1530046339160-ce3edf47ab3c?w=500'],
            ['name' => 'Llantas 17 pulgadas', 'desc' => 'Juego de 4 llantas de aleación con neumáticos 205/50R17, excelente estado.', 'price' => 8000.00, 'category' => 'Automotive', 'material' => 'Metal', 'color' => 'Gray', 'condition' => 'Good', 'weight' => 80.0, 'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=500'],
            ['name' => 'Estéreo Pioneer', 'desc' => 'Radio con pantalla táctil, Bluetooth, Android Auto y Apple CarPlay.', 'price' => 4500.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Like New', 'weight' => 2.0, 'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500'],
            ['name' => 'Batería de Auto', 'desc' => 'Batería nueva de 12V 60Ah, garantía de 2 años, para autos compactos.', 'price' => 2000.00, 'category' => 'Automotive', 'material' => 'Others', 'color' => 'Black', 'condition' => 'New', 'weight' => 15.0, 'image' => 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=500'],
            ['name' => 'Aceites y Filtros', 'desc' => 'Kit de mantenimiento: aceite sintético 5W30 y filtros varios.', 'price' => 800.00, 'category' => 'Automotive', 'material' => 'Others', 'color' => 'Others', 'condition' => 'New', 'weight' => 5.0, 'image' => 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=500'],
            ['name' => 'Cargador de Batería', 'desc' => 'Cargador inteligente automático 12V/24V con pinzas y cables.', 'price' => 1500.00, 'category' => 'Automotive', 'material' => 'Metal', 'color' => 'Red', 'condition' => 'Like New', 'weight' => 3.0, 'image' => 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=500'],
            ['name' => 'Tapetes Universales', 'desc' => 'Juego de tapetes de hule universales, resistentes y fáciles de limpiar.', 'price' => 600.00, 'category' => 'Automotive', 'material' => 'Rubber', 'color' => 'Black', 'condition' => 'Good', 'weight' => 2.5, 'image' => 'https://images.unsplash.com/photo-1517524008279-21d5db50ad38?w=500'],
            ['name' => 'GPS para Auto', 'desc' => 'Navegador GPS con mapas actualizados de Latinoamérica.', 'price' => 2200.00, 'category' => 'Electronics', 'material' => 'Plastic', 'color' => 'Black', 'condition' => 'Good', 'weight' => 0.3, 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500'],
            ['name' => 'Fundas para Asientos', 'desc' => 'Fundas universales de cuero sintético para proteger asientos.', 'price' => 1200.00, 'category' => 'Automotive', 'material' => 'Others', 'color' => 'Black', 'condition' => 'New', 'weight' => 2.0, 'image' => 'https://images.unsplash.com/photo-1517524008279-21d5db50ad38?w=500'],
            ['name' => 'Compresora de Aire', 'desc' => 'Compresora portátil 12V para inflar neumáticos, incluye manómetro.', 'price' => 900.00, 'category' => 'Automotive', 'material' => 'Metal', 'color' => 'Red', 'condition' => 'Good', 'weight' => 1.5, 'image' => 'https://images.unsplash.com/photo-1530046339160-ce3edf47ab3c?w=500'],

            // Electrodomésticos (8 items)
            ['name' => 'Refrigerador LG', 'desc' => 'Refrigerador de 18 pies cúbicos, tecnología inverter, poco uso.', 'price' => 12000.00, 'category' => 'Appliances', 'material' => 'Metal', 'color' => 'White', 'condition' => 'Good', 'weight' => 65.0, 'image' => 'https://images.unsplash.com/photo-1571171637578-41bc2dd41cd2?w=500'],
        ];

        foreach ($user2Items as $index => $item) {
            $location = $latinLocations[array_rand($latinLocations)];

            Thing::create([
                'user_id' => $user->id,
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'condition_id' => Condition::where('name', $item['condition'])->first()->id,
                'availability' => true,
                'weight' => $item['weight'],
                'category_id' => Category::where('name', $item['category'])->first()->id,
                'material_id' => Material::where('name', $item['material'])->first()->id,
                'color_id' => Color::where('name', $item['color'])->first()->id,
                'location' => json_encode([
                    'street' => $this->faker->streetAddress,
                    'latitude' => $location['lat'] + (rand(-100, 100) / 1000),
                    'longitude' => $location['lng'] + (rand(-100, 100) / 1000),
                ]),
                'imagesUrl' => $item['image'],
            ]);
        }
    }

    private function createTruequiEsThings($user)
    {

        $user2Items = [
            [
                'name' => 'CaninoBike 3000',
                'desc' => 'Bicicleta para perros que no se mueve, pero hace "woof woof" cuando la muerdes. Ideal para los velocidogos.',
                'price' => 299.99,
                'category' => 'Toys',
                'material' => 'Metal',
                'color' => 'Red',
                'condition' => 'New',
                'weight' => 0.8,
                'image' => 'https://images.pexels.com/photos/4135359/pexels-photo-4135359.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
            [
                'name' => 'Libro de Perros: Edición Mordida',
                'desc' => 'Un libro de mentiritas lleno de chistes perrunos. Crincrin en cada página, aprobado por 9 de cada 10 chihuahuas.',
                'price' => 149.99,
                'category' => 'Toys',
                'material' => 'Paper',
                'color' => 'Brown',
                'condition' => 'New',
                'weight' => 0.3,
                'image' => 'https://images.pexels.com/photos/9632842/pexels-photo-9632842.jpeg?auto=compress&cs=tinysrgb&w=600',
            ],
            [
                'name' => 'Hachita Chillona',
                'desc' => 'Una hacha de juguete que suena como pato cuando la muerde tu lomito. Ideal para perros que quieren talar árboles de croquetas.',
                'price' => 199.99,
                'category' => 'Toys',
                'material' => 'Wood',
                'color' => 'Gray',
                'condition' => 'New',
                'weight' => 0.5,
                'image' => 'https://images.pexels.com/photos/32423928/pexels-photo-32423928/free-photo-of-playful-dog-balancing-ax-outdoors.png?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
            [
                'name' => 'Ladraptop Pro',
                'desc' => 'Laptop de juguete para el perrito que trabaja remoto desde el jardín. Con teclado chirriador y pantalla comestible.',
                'price' => 249.99,
                'category' => 'Toys',
                'material' => 'Metal',
                'color' => 'Black',
                'condition' => 'New',
                'weight' => 0.6,
                'image' => 'https://images.pexels.com/photos/14935860/pexels-photo-14935860.jpeg?auto=compress&cs=tinysrgb&w=600',
            ],
            [
                'name' => 'Peluchón el Conejo Loco',
                'desc' => 'Peluche indestructible (o eso intentamos). Grita de risa cuando tu lo sacudes.',
                'price' => 179.99,
                'category' => 'Toys',
                'material' => 'Plastic',
                'color' => 'Gray',
                'condition' => 'New',
                'weight' => 0.4,
                'image' => 'https://images.pexels.com/photos/17184902/pexels-photo-17184902/free-photo-of-cute-plush-toy-dog-with-a-blue-bow-tie-sitting-on-a-bed.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
        ];


        foreach ($user2Items as $index => $item) {

            Thing::create([
                'user_id' => $user->id,
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'condition_id' => Condition::where('name', $item['condition'])->first()->id,
                'availability' => true,
                'weight' => $item['weight'],
                'category_id' => Category::where('name', $item['category'])->first()->id,
                'material_id' => Material::where('name', $item['material'])->first()->id,
                'color_id' => Color::where('name', $item['color'])->first()->id,
                'location' => json_encode([
                    'street' => $this->faker->streetAddress,
                    'latitude' => 19.4326,
                    'longitude' => -99.1332,
                ]),
                'imagesUrl' => $item['image'],
            ]);
        }
    }

    private function createTruequiEnThings($user)
    {

        $user2Items = [
            [
                'name' => 'CaninoBike 3000',
                'desc' => 'Bicycle for dogs that doesn’t move, but goes “woof woof” when you bite it. Perfect for speedydogs.',
                'price' => 299.99,
                'category' => 'Toys',
                'material' => 'Metal',
                'color' => 'Red',
                'condition' => 'New',
                'weight' => 0.8,
                'image' => 'https://images.pexels.com/photos/4135359/pexels-photo-4135359.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
            [
                'name' => 'Dog Book: Bite Edition',
                'desc' => 'A fake book full of dog jokes. Squeaks on every page, approved by 9 out of 10 chihuahuas.',
                'price' => 149.99,
                'category' => 'Toys',
                'material' => 'Paper',
                'color' => 'Brown',
                'condition' => 'New',
                'weight' => 0.3,
                'image' => 'https://images.pexels.com/photos/9632842/pexels-photo-9632842.jpeg?auto=compress&cs=tinysrgb&w=600',
            ],
            [
                'name' => 'Squeaky Little Axe',
                'desc' => 'A toy axe that quacks when your dog bites it. Perfect for dogs who dream of chopping down kibble trees.',
                'price' => 199.99,
                'category' => 'Toys',
                'material' => 'Wood',
                'color' => 'Gray',
                'condition' => 'New',
                'weight' => 0.5,
                'image' => 'https://images.pexels.com/photos/32423928/pexels-photo-32423928/free-photo-of-playful-dog-balancing-ax-outdoors.png?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
            [
                'name' => 'Laptail Pro',
                'desc' => 'Toy laptop for the pup who works remotely from the garden. Comes with a squeaky keyboard and edible screen.',
                'price' => 249.99,
                'category' => 'Toys',
                'material' => 'Metal',
                'color' => 'Black',
                'condition' => 'New',
                'weight' => 0.6,
                'image' => 'https://images.pexels.com/photos/14935860/pexels-photo-14935860.jpeg?auto=compress&cs=tinysrgb&w=600',
            ],
            [
                'name' => 'Fluffy the Crazy Bunny',
                'desc' => 'An indestructible plush toy (or so we tried). It laughs when you shake it.',
                'price' => 179.99,
                'category' => 'Toys',
                'material' => 'Plastic',
                'color' => 'Gray',
                'condition' => 'New',
                'weight' => 0.4,
                'image' => 'https://images.pexels.com/photos/17184902/pexels-photo-17184902/free-photo-of-cute-plush-toy-dog-with-a-blue-bow-tie-sitting-on-a-bed.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            ],
        ];


        foreach ($user2Items as $index => $item) {

            Thing::create([
                'user_id' => $user->id,
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'condition_id' => Condition::where('name', $item['condition'])->first()->id,
                'availability' => true,
                'weight' => $item['weight'],
                'category_id' => Category::where('name', $item['category'])->first()->id,
                'material_id' => Material::where('name', $item['material'])->first()->id,
                'color_id' => Color::where('name', $item['color'])->first()->id,
                'location' => json_encode([
                    'street' => $this->faker->streetAddress,
                    'latitude' => 19.4326,
                    'longitude' => -99.1332,
                ]),
                'imagesUrl' => $item['image'],
            ]);
        }
    }


}
