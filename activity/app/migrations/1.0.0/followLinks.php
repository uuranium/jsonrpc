<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class FollowlinksMigration_100
 */
class FollowlinksMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('follow_links', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type'          => Column::TYPE_INTEGER,
                            'size'          => 10,
                            'unsigned'      => true,
                            'notNull'       => true,
                            'autoIncrement' => true
                        ]
                    ),
                    new Column(
                        'url',
                        [
                            'type'     => Column::TYPE_TEXT,
                            'notNull'  => true,
                            'after'    => 'id',
                        ]
                    ),
                    new Column(
                        'date',
                        [
                            'type'    => Column::TYPE_TIMESTAMP,
                            'notNull' => true,
                            'default' => 'CURRENT_TIMESTAMP',
                            'after'    => 'url'
                        ]
                    ),
                ],
                'indexes' => [
                    new Index(
                        'PRIMARY',
                        [
                            'id',
                        ]
                    )
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
