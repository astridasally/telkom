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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('regional');
            $table->string('witel');
            $table->string('sto')->nullable();
            $table->string('site')->nullable();
            $table->string('category')->nullable();
            $table->string('catuan_id')->nullable();
            $table->string('ihld')->nullable();

            //khusus Mitra
            $table->date('plan_survey')->nullable();
            $table->date('realisasi_survey')->nullable();
            $table->date('plan_delivery')->nullable();
            $table->date('realisasi_delivery')->nullable();
            $table->date('plan_instalasi')->nullable();
            $table->date('realisasi_instalasi')->nullable();
            $table->date('plan_integrasi')->nullable();
            $table->date('realisasi_integrasi')->nullable();
            $table->string('drop_data')->nullable();
            $table->string('bukti_drop')->nullable();
            $table->string('relok_regional')->nullable();
            $table->string('relok_witel')->nullable();
            $table->string('relok_sto')->nullable();
            $table->string('relok_site')->nullable();
            $table->text('remark')->nullable();
            
            // Khusus TA
            $table->string('priority_ta')->nullable();
            $table->string('dependensi')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('status_osp')->nullable();
            $table->string('scenario_uplink')->nullable();
            $table->string('status_uplink')->nullable();
            $table->string('remark_ta')->nullable();
            $table->string('jumlah_port')->nullable();
            $table->string('golive_status')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
