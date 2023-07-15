<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('user_type')->default(3);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mail_password')->nullable();
            $table->date('dob')->nullable();
            $table->date('doj')->nullable();
            $table->string('gender')->nullable();
            $table->string('picture')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'user_type' => 1,
                'password'=> Hash::make('password')
            ]
        );

        $this->createUserType();
        $this->permission();
    }


    private function createUserType()
    {
        Schema::create('user_type', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description');
        });

        DB::table('user_type')->insert( [
            [
                'name' => 'admin',
                'description' => "Admin Role, All permissions are assigned"
            ],
            [
                'name' => 'manager',
                'description' => "Staff Role, Limited permission assigned, can access dashboard"
            ],
            [
                'name' => 'employee',
                'description' => "Only front end can access"
            ]
        ]
        );
    }

    private function permission()
    {
        Schema::create('permission', function(Blueprint $table){
            $table->id();
            $table->integer('user_id');
            $table->string('key');
            $table->string('value');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
