<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillAttributesTable extends Migration {

    protected $attributes = [
        [
            'name' => 'Sent',
            'type' => 'input',
            'description' => 'When was bill sent?',
            'slug' => 'sent',
        ],
        [
            'name' => 'Paid',
            'type' => 'input',
            'description' => 'When was bill paid?',
            'slug' => 'paid',
        ],
        [
            'name' => 'Language',
            'type' => 'select',
            'description' => 'Language of client',
            'slug' => 'lang',
            'options' => '{"en":"English","cs":"Czech"}',
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attributesRepo = app('Platform\Attributes\Repositories\AttributeRepositoryInterface');

        foreach( $this->attributes as $attribute )
        {
            $attributesRepo->firstOrCreate([
                'namespace'   => \Sanatorium\Bill\Models\Bill::getEntityNamespace(),
                'name'        => $attribute['name'],
                'description' => $attribute['description'],
                'type'        => $attribute['type'],
                'slug'        => $attribute['slug'],
                'enabled'     => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $attributesRepo = app('Platform\Attributes\Repositories\AttributeRepositoryInterface');

        foreach( $this->attributes as $attribute )
        {
            $attributesRepo->where('slug', $attribute['slug'])->delete();
        }
    }

}
