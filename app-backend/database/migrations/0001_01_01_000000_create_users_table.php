<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the users table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique(); // Unique username
            $table->string('email')->unique(); // Unique email
            $table->string('phone')->nullable(); // Nullable phone number
            $table->string('password'); // Encrypted password
            $table->rememberToken(); // For "remember me" functionality
            $table->timestamps(); // Created_at and updated_at
        });

        // Create the password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Primary key is the email
            $table->string('token'); // Reset token
            $table->timestamp('created_at')->nullable(); // Token creation time
        });

        // Create the sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Primary key is session ID
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // Foreign key to users table
            $table->string('ip_address', 45)->nullable(); // IP address (supports IPv6)
            $table->text('user_agent')->nullable(); // User agent details
            $table->longText('payload'); // Session payload data
            $table->integer('last_activity')->index(); // Timestamp of last activity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the tables in reverse order of creation
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
