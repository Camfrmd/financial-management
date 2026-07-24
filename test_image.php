<?php
require __DIR__ . '/vendor/autoload.php';
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

try {
    $manager = new ImageManager(new Driver());
    // Create a dummy image
    $image = $manager->create(200, 200)->fill('ff0000');
    $encoded = $image->toJpeg(75);
    file_put_contents('test.jpg', $encoded->toString());
    echo "Success!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
