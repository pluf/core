<?php
namespace Pluf\NoteBook;

use Pluf\Data\Schema;

class Tag extends \Pluf\Data\Model
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'notebook_tags';
        $this->_a['verbose'] = 'Note tag';
        $this->_a['cols'] = [
            // It is mandatory to have an "id" column.
            'id' => [
                'type' => Schema::SEQUENCE,
                'primary' => true,
                // It is automatically added.
                'blank' => true,
                'editable' => false,
                'readable' => true
            ],
            'title' => [
                'type' => Schema::VARCHAR,
                'size' => 100,
                'blank' => false,
                'editable' => false,
                'readable' => true
            ],
            'books' => [
                'type' => Schema::MANY_TO_MANY,
                'joinProperty' => 'id',
                'inverseJoinModel' => Book::class,
                'inverseJoinProperty' => 'id'
            ]
        ];
    }
}

