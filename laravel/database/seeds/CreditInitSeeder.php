<?php

use App\Models\Credit;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreditInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::doesntHave('credit')->get();
        foreach ($users as $user) {
            $credit          = new Credit();
            $credit->user_id = $user->id;
            $credit->save();
        }
    }
}
