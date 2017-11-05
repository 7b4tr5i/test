<?php

namespace app\controller;

use Base;
use app\model\Goods;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Main extends Controller
{

    public function update($id){

        if(($id === NULL) || (($model = Goods::find($id)) == NULL))
            throw new NotFoundHttpException('Not Found');

        $request = Base::$app->getRequest();

        if (($data = $request->request->all()) != NULL) {

            $model->fill($data);
            $model->validate('update');
            if($model->errors !== NULL)
                return ['code' => 400, 'error' => $model->errors];

            $model->update();
        }

        return ['code' => 200, 'data' => $model];
    }


    public function create(){

        $request = Base::$app->getRequest();

        if(($data = $request->request->all()) == NULL)
            return ['code' => 400, 'error' => 'Post data is empty'];

        $model = new Goods;
        $model->fill($data);
        $model->validate('save');

        if($model->errors !== null)
            return ['code' => 400, 'error' => $model->errors];

        $model->save();

        return ['code' => 201];
    }
}