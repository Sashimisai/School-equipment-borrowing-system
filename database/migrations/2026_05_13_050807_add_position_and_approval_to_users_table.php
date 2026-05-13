<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('position')->nullable()->after('role'); // President, Secretary, etc.
            $table->string('approval_status')->default('pending')->after('status'); // pending, approved, rejected
            $table->text('admin_remarks')->nullable()->after('approval_status');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['position', 'approval_status', 'admin_remarks']);
        });
    }
};