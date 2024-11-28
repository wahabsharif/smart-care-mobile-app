<?php

// database/migrations/xxxx_xx_xx_create_chats_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'private' or 'group'
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable(); // For group chats, this will store the group name
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
