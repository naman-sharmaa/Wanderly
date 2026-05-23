<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the raw MongoDB collection
        $client = DB::connection('mongodb')->getMongoClient();
        $collection = $client->selectDatabase('travel_planner')->selectCollection('users');
        
        // Simply update all documents to add missing fields
        // Don't create index here - let's handle it manually
        $collection->updateMany(
            [],
            ['$set' => ['auth_provider' => 'local']],
            ['upsert' => false]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $client = DB::connection('mongodb')->getMongoClient();
        $collection = $client->selectDatabase('travel_planner')->selectCollection('users');
        
        // Remove fields from all documents
        $collection->updateMany(
            [],
            ['$unset' => ['firebase_uid' => '', 'auth_provider' => '']]
        );
    }
};
