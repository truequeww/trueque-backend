<?php

namespace App\Providers;

use Faker\Provider\Base as BaseProvider;

class ProductProvider extends BaseProvider {
   protected static $productNames = [
    'Laptop', 'Smartphone', 'Headphones', 'Backpack', 'Coffee Maker', 'Bluetooth Speaker', 
    'Smartwatch', 'Tablet', 'Gaming Console', 'Camera', 'Electric Toothbrush', 'Hair Dryer', 
    'Mixer', 'Vacuum Cleaner', 'Fitness Tracker', 'Water Bottle', 'Wireless Charger', 
    'Desk Lamp', 'Portable Fan', 'Electric Kettle', 'Air Purifier', 'Portable Speaker', 
    'Smart Home Hub', 'Digital Camera', 'Smart Thermostat', 'Electric Shaver', 'Cordless Drill', 
    'Smart Scale', 'Electric Heater', 'Blender', 'Refrigerator', 'Dishwasher', 
    'Dehumidifier', 'Coffee Grinder', 'Electric Grill', 'Slow Cooker', 'Air Fryer', 
    'Robot Vacuum', 'Induction Cooktop', 'Pressure Cooker', 'Soundbar', 'Home Theater System', 
    'Security Camera', 'Electric Bike', 'Electric Scooter', 'Treadmill', 'Yoga Mat', 
    'Fitness Ball', 'Dumbbells', 'Rowing Machine', 'Resistance Bands', 'Protein Shaker', 
    'Smart Speaker', 'Smart Light Bulb', 'Electric Blanket', 'Rice Cooker', 'Coffee Capsule Machine',
    'Mini Fridge', 'Digital Thermometer', 'Smart Lock', 'Portable Heater', 'Outdoor Speaker', 
    'Coffee Bean Roaster', 'Smart Water Bottle', 'Electric Skillet', 'Food Processor', 
    'Compact Refrigerator', 'Cordless Vacuum', 'Wall Clock', 'Portable Charger', 'Smart Plugs'
    ];

    protected static $productDescriptions = [
    'High-quality and durable, designed to withstand everyday wear and tear while offering unmatched performance.', 
    'Innovative design focused on seamless integration into your lifestyle, offering both functionality and aesthetic appeal.',
    'Compact and portable, perfect for those who need convenience without compromising on performance.',
    'User-friendly interface with intuitive controls, ensuring a smooth and hassle-free experience for everyone.',
    'Energy-efficient with cutting-edge technology to help reduce power consumption and increase cost savings.',
    'Sleek and stylish, combining modern aesthetics with practicality to complement any environment.',
    'Versatile and multi-functional, designed to adapt to various needs and provide exceptional value for its features.',
    'Eco-friendly materials and sustainable production methods ensure you’re making an environmentally conscious purchase.',
    'Advanced technology integrated with smart features for seamless connectivity and enhanced usability.',
    'Affordable and budget-friendly, offering top-notch performance without breaking the bank.',
    'Reliable performance backed by extensive testing, ensuring consistent functionality in any situation.',
    'Easy to clean and maintain, designed with practical features that allow for effortless upkeep and longevity.',
    'Lightweight and comfortable for all-day use, minimizing strain without sacrificing utility.',
    'Modern look with minimalist design principles, offering an elegant yet functional addition to any home or workspace.',
    'Ergonomically designed to provide optimal comfort and reduce strain, making it perfect for long hours of use.',
    'Perfect for everyday use, delivering a seamless experience that meets your daily needs with efficiency and ease.',
    'Convenient and practical, designed to solve real-world problems and simplify your daily routine.',
    'High-performance materials and design for exceptional results every time you use it.',
    'Suitable for all ages, offering a universal design that’s ideal for both children and adults alike.',
    'Premium build quality ensuring long-lasting durability and top-tier performance in every use.',
    'Compact and space-saving, perfect for small apartments or offices without compromising on functionality.',
    'A high-end product that delivers superior quality and sophistication in every aspect of its design and operation.',
    'Designed for professionals, delivering top-tier features and durability for the most demanding tasks.',
    'Built to endure, with robust materials that stand the test of time and continue performing at their best.',
    'Smart technology integration for improved functionality, making your daily tasks easier and more efficient.',
    'With a focus on innovation, this product delivers advanced features that keep you ahead of the curve.',
    'Flexible and adaptive to different environments, ensuring excellent performance no matter where you are.',
    'Offers a high level of customization, allowing you to personalize the experience to your exact preferences.',
    'Compact, lightweight, and portable, providing an easy solution to on-the-go needs without any bulk.',
    'Revolutionary design with cutting-edge technology that enhances usability and optimizes user experience.',
    'A reliable, hassle-free solution that’s designed for longevity, providing consistent results over time.',
    'Packed with modern features that cater to both function and form, making it an all-in-one solution.',
    'Provides the perfect balance between power and size, delivering exceptional performance without taking up too much space.',
    'Incorporating the latest technology to offer superior functionality and ease of use for all users.',
    'Rugged and durable construction ensures that this product can withstand even the harshest conditions.',
    'High-tech features designed to improve overall performance while maintaining an easy-to-use interface.',
    'A sophisticated yet user-friendly solution that meets a variety of needs without any unnecessary complexity.',
    'Customizable settings allow for a personalized experience, adapting to your lifestyle and needs.',
    'Efficient design focuses on maximizing energy usage while minimizing environmental impact.',
    'Crafted for those who appreciate attention to detail and care about both form and function in their devices.',
    'Portable and travel-friendly, designed for people who are always on the move and require convenience at their fingertips.',
    'A seamless blend of practicality and cutting-edge design to enhance your daily experience.',
    'Reliable and dependable, built to offer uninterrupted performance under various conditions.',
    'Optimized for high performance, ensuring that every feature works seamlessly together to create an exceptional product.',
    'Sturdy and well-built, this product is crafted to last and withstand the rigors of everyday use.',
    'Designed to help simplify your life, making everyday tasks easier with intuitive controls and features.',
    'Incorporating smart sensors and intuitive functions that enhance user experience for all skill levels.',
    'Simple yet highly effective, offering just what you need without the distractions of unnecessary features.',
    'Maximizes efficiency and minimizes energy usage, offering high performance while being eco-conscious.',
    'Blends seamlessly into your lifestyle, offering ease of use without compromising on performance or style.',
    'Engineered for excellence, combining state-of-the-art technology with practical functionality for unparalleled results.',
    'Compact and portable, easy to take with you wherever you go without sacrificing quality.',
    'Perfect for both beginners and professionals, with adjustable settings to suit different levels of expertise.',
    'Combines ease of use with cutting-edge technology, making it accessible for anyone to enjoy its benefits.',
    'Efficiently designed to handle high demands while maintaining exceptional results across a variety of uses.',
    'Supports a wide range of activities, making it suitable for everything from casual to more advanced needs.',
    'Provides all the features you need with none of the hassle, delivering outstanding results with minimal effort.',
    'Delivers top-notch quality with every use, ensuring satisfaction through precision craftsmanship and innovative features.',
    'Built with the latest materials, making it lightweight yet durable, ideal for long-term use.',
    'Maximizes your productivity while maintaining comfort and ease of use, even during extended periods of operation.',
    'Delivers high value at a fraction of the cost, offering incredible performance without the premium price tag.'
    ];

    public function productName() {
        return static::randomElement(static::$productNames);
    }

    public function productDescription() {
        return static::randomElement(static::$productDescriptions);
    }
}
