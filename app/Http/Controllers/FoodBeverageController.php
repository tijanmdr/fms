<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodBeverageController extends Controller
{
    public function createFood(Request $req) {
        $req = $req->only('name', 'before', 'after');
        $validate = Validator::make($req, [
            'name' => 'required|string|unique:foods,name',
            'photo' => 'required',
            'ingredients' => 'required',
            'price' => 'required|double',
            'hot' => 'integer',
            'sauce' => 'string',
            'category' => 'required|exists:categories,id',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }    

    }

    public function updateFood(Request $req) {

    }

    public function deleteFood() {

    }

    public function listFoods() {

    }

    public function createBeverage(Request $req) {

    }

    public function updateBeverage(Request $req) {

    }

    public function deleteBeverage() {

    }

    public function listBeverages() {

    }
}
