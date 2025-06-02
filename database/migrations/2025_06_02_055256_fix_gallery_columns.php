<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixGalleryColumns extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            // यदि image_path कलम छैन भने थप्ने
            if (!Schema::hasColumn('galleries', 'image_path')) {
                $table->string('image_path')->nullable()->after('id');
            }

            // यदि video_url कलम थप्नु पर्छ भने
            if (!Schema::hasColumn('galleries', 'video_url')) {
                $table->string('video_url')->nullable()->after('image_path');
            }

            // अनावश्यक कलमहरू हटाउने
            if (Schema::hasColumn('galleries', 'file_path')) {
                $table->dropColumn('file_path');
            }
            if (Schema::hasColumn('galleries', 'media_path')) {
                $table->dropColumn('media_path');
            }
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            // रोलब्याकका लागि
            $table->string('file_path')->nullable();
            $table->dropColumn(['image_path', 'video_url']);
        });
    }
}
