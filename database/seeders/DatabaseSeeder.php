<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Card;
use App\Models\User;
use App\Models\Admin;
use App\Models\Lead;
use App\Models\Board;
use App\Models\CardList;
use Illuminate\Database\Seeder;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\LeadTableSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $admin  = Admin::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]); 

        $this->call([   
            LeadTableSeeder::class
        ]);


        $user = User::factory()->create(['email' => 'jasper@gmail.com', 'password' => bcrypt('secret')]);

        $boards = Board::factory(10)->for($user)->create();

        foreach ($boards as $board) {
            $cardList = CardList::create([
                'board_id' =>$board->id,
                'user_id' => $user->id,
                'name' => 'Todo'
            ]);
            $cardList2 = CardList::create([
                'board_id' =>$board->id,
                'user_id' => $user->id,
                'name' => 'On Going'
            ]);
            $cardList3 = CardList::create([
                'board_id' =>$board->id,
                'user_id' => $user->id,
                'name' => 'Done'
            ]);

            Card::factory(10)->create([
                'board_id' =>$board->id,
                'user_id' => $user->id,
                'card_list_id'=> $cardList->id
            ]);
            Card::factory(10)->create([
                'board_id' =>$board->id,
                'user_id' => $user->id,
                'card_list_id'=> $cardList2->id
            ]);
            Card::factory(10)->create([
                'board_id' =>$board->id,
                'user_id' => $user->id,
                'card_list_id'=> $cardList3->id
            ]);
        }

    }
}
