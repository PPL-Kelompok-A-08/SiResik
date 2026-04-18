<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('masyarakat')->after('email');
        });

        DB::table('users')->whereNull('role')->update([
            'role' => 'masyarakat',
        ]);

        $existingEmails = DB::table('users')->pluck('email')->all();

        $demoUsers = [
            [
                'name' => 'Admin SiResik',
                'email' => 'admin@siresik.local',
                'role' => 'admin',
            ],
            [
                'name' => 'Petugas SiResik',
                'email' => 'petugas@siresik.local',
                'role' => 'petugas',
            ],
            [
                'name' => 'Masyarakat Demo',
                'email' => 'masyarakat@siresik.local',
                'role' => 'masyarakat',
            ],
        ];

        $timestamp = now();

        foreach ($demoUsers as $user) {
            if (in_array($user['email'], $existingEmails, true)) {
                continue;
            }

            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'email_verified_at' => $timestamp,
                'password' => Hash::make('password'),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'admin@siresik.local',
            'petugas@siresik.local',
            'masyarakat@siresik.local',
        ])->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
