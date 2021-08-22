<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ExtendTaxonomiesTables
 */
class ExtendTaxonomiesTables extends Migration
{
    /**
     * Table names.
     *
     * @var string  $terms       The terms table name.
     * @var string  $taxonomies  The taxonomies table name.
     */
    protected $terms;
    protected $taxonomies;

    /**
     * Create a new migration instance.
     */
    public function __construct()
    {
        $this->terms      = config('lecturize.taxonomies.terms.table',      config('lecturize.taxonomies.terms_table',      'terms'));
        $this->taxonomies = config('lecturize.taxonomies.taxonomies.table', config('lecturize.taxonomies.taxonomies_table', 'taxonomies'));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->terms, function(Blueprint $table) {
            $table->longText('content')->nullable()->after('slug');
            $table->text('lead')->nullable()->after('content');
        });

        Schema::table($this->taxonomies, function(Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');

            $table->integer('parent_id')->nullable()->unsigned()->index()->after('uuid');
            $table->foreign('parent_id')
                  ->references('id')
                  ->on($this->taxonomies)
                  ->onDelete('cascade');

            $table->longText('content')->nullable()->after('description');
            $table->text('lead')->nullable()->after('content');

            $table->json('properties')->nullable()->after('sort');
        });

        DB::table($this->taxonomies)->where('parent', '>', 0)->update([
            'parent_id' => DB::raw('parent')
        ]);

        Schema::table($this->taxonomies, function(Blueprint $table) {
            $table->dropColumn('parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->terms, function(Blueprint $table) {
            $table->dropColumn('content');
            $table->dropColumn('lead');

            $table->renameColumn('title', 'name');
        });

        Schema::table($this->taxonomies, function(Blueprint $table) {
            $table->dropColumn('uuid');

            $table->integer('parent')->unsigned()->default(0)->after('description');

            $table->dropColumn('content');
            $table->dropColumn('lead');

            $table->dropColumn('properties');

            $table->renameColumn('description', 'desc');
        });

        DB::table($this->taxonomies)->where('parent_id', '!=', null)->update([
            'parent' => DB::raw('parent_id')
        ]);

        Schema::table($this->taxonomies, function(Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
