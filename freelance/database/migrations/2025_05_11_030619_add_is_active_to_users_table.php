<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('active')->nullable()->after('role');
        });

        // Set active based on role
        DB::table('users')->where('role', 'docteur')->update(['active' => false]);
        DB::table('users')->where('role', '!=', 'docteur')->update(['active' => true]);

        // Make column non-nullable with default true
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('active')->default(true)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
