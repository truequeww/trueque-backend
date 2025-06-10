<?php


// database/seeders/DatabaseSeeder.php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\User;
// use App\Models\Thing;
// use App\Models\Category;
// use App\Models\Material;
// use App\Models\Color;
// use App\Models\Condition;
// use App\Models\OfferStatus;
// use App\Models\Like;
// use App\Models\Dislike;
// use App\Models\Chat;
// use App\Models\Message;
// use App\Models\Offer;
// use App\Models\OfferThing;
// use App\Models\Rating;
// use App\Models\Notification;

// class DatabaseSeeder extends Seeder
// {
//     public function run()
//     {
//         // Create categories
//         $categories = [
//             "Electronics", "Appliances", "Clothing", "Furniture", "Automotive", "Food & Beverage", "Services", "Toys", "Health & Beauty", "Books", 'Others'
//         ];
//         foreach ($categories as $category) {
//             Category::create(['name' => $category]);
//         }

//         // Create materials
//         $materials = [
//             "Wood", "Metal", "Plastic", "Glass", "Fabric", "Paper", "Stone", "Rubber", "Concrete", "Others"
//         ];
//         foreach ($materials as $material) {
//             Material::create(['name' => $material]);
//         }

//         $colors = [
//             "Red", "Blue", "Green", "Yellow", "Black", "White", "Gray", "Brown", "Orange", "Others"
//         ];
//         foreach ($colors as $color) {
//             Color::create(['name' => $color]);
//         }

//         // Create conditions
//         $conditions = ['New', 'Like New', 'Refurbished', 'Acceptable', 'Good'];
//         foreach ($conditions as $condition) {
//             Condition::create(['name' => $condition]);
//         }

//         // Create offer statuses
//         $offerStatuses = ['accepted', 'accepted-aware', 'rejected', 'rejected-aware', 'pending',];
//         foreach ($offerStatuses as $status) {
//             OfferStatus::create(['name' => $status]);
//         }

//         // Create users
//         User::factory()->count(10)->create()->each(function ($user) {
//             // Create things for each user
//             $things = Thing::factory()->count(5)->create([
//                 'user_id' => $user->id,
//                 'category_id' => Category::inRandomOrder()->first()->id,
//                 'material_id' => Material::inRandomOrder()->first()->id,
//                 'condition_id' => Condition::inRandomOrder()->first()->id,
//                 'color_id' => Condition::inRandomOrder()->first()->id,
//             ]);

//             // Create likes and dislikes
//             $things->random(3)->each(function ($thing) use ($user) {
//                 Like::factory()->create(['user_id' => $user->id, 'thing_id' => $thing->id]);
//             });

//             $things->random(2)->each(function ($thing) use ($user) {
//                 Dislike::factory()->create(['user_id' => $user->id, 'thing_id' => $thing->id]);
//             });

//             // Create chats and messages
//             $otherUser = User::factory()->create();
//             $chat = Chat::factory()->create(['user1_id' => $user->id, 'user2_id' => $otherUser->id]);
//             Message::factory()->count(5)->create(['chat_id' => $chat->id, 'user_id' => $user->id]);

//             // Create ratings
//             Rating::factory()->count(3)->create(['user_id' => $user->id, 'rated_user_id' => $otherUser->id]);

//             // Create notifications
//             Notification::factory()->count(5)->create(['user_id' => $user->id]);

//             // Create offers
//             $offers = Offer::factory()->count(2)->create([
//                 'chat_id' => $chat->id,
//                 'from_user_id' => $user->id,
//                 'to_user_id' => $otherUser->id,
//                 'status_id' => OfferStatus::inRandomOrder()->first()->id,
//             ]);

//             // Create OfferThings for each offer
//             foreach ($offers as $offer) {
//                 OfferThing::factory()->count(2)->create([
//                     'offer_id' => $offer->id,
//                     'thing_id' => $things->random()->id,
//                     'is_offered' => true,
//                 ]);
//             }
//         });
//     }
// }
