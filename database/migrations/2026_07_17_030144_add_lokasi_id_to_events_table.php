<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Lokasi;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_id')->nullable()->after('kategori_id');
        });

        // Migrate data
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            if (isset($event->lokasi) && !empty($event->lokasi)) {
                $lokasiRecord = Lokasi::firstOrCreate(['nama' => $event->lokasi]);
                DB::table('events')->where('id', $event->id)->update(['lokasi_id' => $lokasiRecord->id]);
            }
        }

        Schema::table('events', function (Blueprint $table) {
            // First drop the old string column
            $table->dropColumn('lokasi');
            // Then add foreign key constraint
            $table->foreign('lokasi_id')->references('id')->on('lokasis')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('deskripsi');
        });

        // Migrate back
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            if (isset($event->lokasi_id) && !empty($event->lokasi_id)) {
                $lokasiRecord = DB::table('lokasis')->where('id', $event->lokasi_id)->first();
                if ($lokasiRecord) {
                    DB::table('events')->where('id', $event->id)->update(['lokasi' => $lokasiRecord->nama]);
                }
            }
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['lokasi_id']);
            $table->dropColumn('lokasi_id');
        });
    }
};
