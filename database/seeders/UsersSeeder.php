<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $user = User::create([
            'email'=>'admin@piikmall.comt',
            'password'=>Hash::make('P@$$w0rd'),
            'name'=>'API Admin',
        ]);
        $user->roles()->attach(Role::where('slug', 'admin')->first());
    }
}
