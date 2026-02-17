<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('membership_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('gym_memberships')->onDelete('cascade');
            $table->enum('action_type', ['created', 'updated', 'deleted']);
            $table->string('field_name')->nullable(); // Which field was changed
            $table->text('old_value')->nullable(); // Previous value
            $table->text('new_value')->nullable(); // New value
            $table->foreignId('changed_by')->constrained('merchants')->onDelete('cascade');
            $table->text('change_reason')->nullable(); // Reason for the change
            $table->foreignId('branch_id')->constrained('merchant_businesses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membership_histories');
    }
};
