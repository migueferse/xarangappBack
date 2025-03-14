<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('musicians', function ($collection) {
            $collection->index('name');
            $collection->index('lastName');
            $collection->index('nickname');
            $collection->index('instrument');
            $collection->index('phone');
            $collection->index('email');
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('musicians');
    }
};
