<?php

namespace app\model;

use Illuminate\Database\Eloquent\Model;
use Rakit\Validation\Validator;

class Goods extends Model
{

    public $table = 'goods';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
        'weight',
        'price',
        'min',
        'max'
    ];

    protected $rules = [
        'save' => [
            'name' => 'required|max:50',
            'type' => 'required|numeric',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
            'min' => 'required|numeric',
            'max' => 'required|numeric'
        ],
        'update' => [
            'name' => 'max:50',
            'type' => 'numeric',
            'weight' => 'numeric',
            'price' => 'numeric',
            'min' => 'numeric',
            'max' => 'numeric'
        ]
    ];

    public $errors;

    public function validate($method){

        $validator = new Validator;

        $validation = $validator->make($this->getAttributes(), $this->rules[$method]);

        $validation->validate();

        if($validation->fails())
            $this->errors = $validation->errors()->firstOfAll();
    }



}