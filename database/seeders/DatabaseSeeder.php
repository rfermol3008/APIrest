<?php

namespace Database\Seeders;

use App\Models\Academy;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $userOne = User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@example.com',
        ]);

        $userTwo = User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
        ]);

        $students = Student::factory(20)->create();

        foreach ([$userOne, $userTwo] as $user) {
            Academy::factory(2)
                ->for($user)
                ->has(Course::factory(3))
                ->create();
        }

        Course::all()->each(function (Course $course) use ($students) {
            $course->students()->attach($students->random(5)->pluck('id')->all());
        });
    }
}