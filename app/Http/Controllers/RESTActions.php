<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;


trait RESTActions {

    protected $statusCodes = [
        'done' => 200,
        'created' => 201,
        'removed' => 204,
        'not_valid' => 400,
        'not_found' => 404,
        'conflict' => 409,
        'permissions' => 401,
        'error' => 400
    ];

    public function index()
    {
        try {
            $m = self::MODEL;
            return $this->respond('done', $m::all());
        } catch (Exception $ex) {
            return $this->respond('error', $ex->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $m = self::MODEL;
            $model = $m::find($id);
            if(is_null($model)){
                return $this->respond('not_found');
            }
            return $this->respond('done', $model);
        } catch (Exception $ex) {
            return $this->respond('error', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $m = self::MODEL;
            $this->validate($request, $m::$rules, $m::$messages);
            return $this->respond('created', $m::create($request->all()));
        } catch (Exception $ex) {
            return $this->respond('error', $ex->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $m = self::MODEL;
            $this->validate($request, $m::$rules, $m::$messages);
            $model = $m::find($id);
            if (is_null($model)) {
                return $this->respond('not_found');
            }
            $model->update($request->all());
            return $this->respond('done', $model);
        } catch (Exception $ex) {
            return $this->respond('error', $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $m = self::MODEL;
            if (is_null($m::find($id))) {
                return $this->respond('not_found');
            }
            $m::destroy($id);
            return $this->respond('removed');
        } catch (Exception $ex) {
            return $this->respond('error', $ex->getMessage());
        }
    }

    protected function respond($status, $data = [])
    {
        return response()->json($data, $this->statusCodes[$status]);
    }

}