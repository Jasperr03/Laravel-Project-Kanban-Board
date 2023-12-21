<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Models\User;

class LeadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $lead = Lead::factory()->create([
            'name' => 'ProdLead',
            'email' => 'lead@lead.com',
            'email_verified_at' => now(),
            'password' => 'lead3', // password
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(50)->create([
            'lead_id' => $lead->id,
        ]);
    }
}
