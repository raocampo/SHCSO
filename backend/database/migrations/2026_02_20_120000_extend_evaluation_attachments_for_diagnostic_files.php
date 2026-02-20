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
        Schema::table('evaluation_attachments', function (Blueprint $table) {
            $table->string('attachment_type', 30)->default('GENERAL')->after('mime_type');
            $table->date('exam_date')->nullable()->after('attachment_type');
            $table->text('notes')->nullable()->after('exam_date');
            $table->unsignedBigInteger('file_size_bytes')->nullable()->after('notes');
            $table->string('original_extension', 12)->nullable()->after('file_size_bytes');

            $table->index(['evaluation_id', 'attachment_type'], 'eval_attach_eval_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_attachments', function (Blueprint $table) {
            $table->dropIndex('eval_attach_eval_type_idx');
            $table->dropColumn([
                'attachment_type',
                'exam_date',
                'notes',
                'file_size_bytes',
                'original_extension',
            ]);
        });
    }
};
