<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint as MongoBlueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('personal_access_tokens', function (MongoBlueprint $collection) {
            $collection->morphs('tokenable');
            $collection->string('name');
            $collection->string('token', 64)->unique();
            $collection->text('abilities')->nullable();
            $collection->timestamp('last_used_at')->nullable();
            $collection->timestamp('expires_at')->nullable();
            $collection->timestamps();
            // $collection->index('token');
            $collection->index(['tokenable_id', 'tokenable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('personal_access_tokens');
    }
};