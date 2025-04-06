<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\BusinessBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessBlockFactory extends Factory
{
    protected $model = BusinessBlock::class;

    public function definition(): array
    {
        $types = ['hero', 'about', 'services', 'gallery', 'contact', 'testimonials'];
        $type = $this->faker->randomElement($types);

        return [
            'business_id' => Business::factory(),
            'type' => $type,
            'content' => $this->getContentForType($type),
            'order' => $this->faker->numberBetween(1, 10),
            'active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    private function getContentForType(string $type): array
    {
        return match($type) {
            'hero' => [
                'title' => $this->faker->sentence(3),
                'subtitle' => $this->faker->sentence(6),
                'image' => $this->faker->imageUrl(1200, 600),
                'cta_text' => $this->faker->words(3, true),
            ],
            'about' => [
                'title' => 'About Us',
                'content' => $this->faker->paragraphs(3, true),
                'image' => $this->faker->optional(0.7)->imageUrl(),
            ],
            'services' => [
                'title' => 'Our Services',
                'services' => $this->generateServices(),
            ],
            'gallery' => [
                'title' => 'Our Portfolio',
                'images' => $this->generateGalleryImages(),
            ],
            'contact' => [
                'title' => 'Contact Us',
                'address' => $this->faker->address(),
                'email' => $this->faker->email(),
                'phone' => $this->faker->phoneNumber(),
            ],
            'testimonials' => [
                'title' => 'What Our Clients Say',
                'testimonials' => $this->generateTestimonials(),
            ],
            default => ['content' => $this->faker->paragraphs(2, true)],
        };
    }

    private function generateServices(): array
    {
        $services = [];
        $count = $this->faker->numberBetween(2, 5);

        for ($i = 0; $i < $count; $i++) {
            $services[] = [
                'name' => $this->faker->words(3, true),
                'description' => $this->faker->sentence(),
                'icon' => $this->faker->word(),
            ];
        }

        return $services;
    }

    private function generateGalleryImages(): array
    {
        $images = [];
        $count = $this->faker->numberBetween(3, 8);

        for ($i = 0; $i < $count; $i++) {
            $images[] = [
                'url' => $this->faker->imageUrl(),
                'caption' => $this->faker->optional(0.7)->sentence(3),
            ];
        }

        return $images;
    }

    private function generateTestimonials(): array
    {
        $testimonials = [];
        $count = $this->faker->numberBetween(2, 5);

        for ($i = 0; $i < $count; $i++) {
            $testimonials[] = [
                'name' => $this->faker->name(),
                'position' => $this->faker->jobTitle(),
                'content' => $this->faker->paragraph(),
                'avatar' => $this->faker->optional(0.5)->imageUrl(100, 100),
                'rating' => $this->faker->numberBetween(3, 5),
            ];
        }

        return $testimonials;
    }
}
