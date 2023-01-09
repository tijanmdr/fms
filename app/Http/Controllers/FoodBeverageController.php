<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Food;
use App\Models\Beverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodBeverageController extends Controller
{
    public function createFood(Request $req) {
        $req = $req->only('name', 'photo', 'ingredients', 'price', 'allergic', 'hot', 'sauce', 'category');
        $validate = Validator::make($req, [
            'name' => 'required|string|unique:foods,name',
            'photo' => 'mimes:png,jpg,jpeg|max:1024',
            'ingredients' => 'required',
            'price' => 'required|numeric',
            'hot' => 'integer',
            'sauce' => 'string',
            'category' => 'required|numeric|exists:categories,id',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        if (array_key_exists('photo', $req)) {
            $req['photo'] = uploadPhotos($req['photo']);
        }
        if (!array_key_exists('allergic', $req)) {
            $req['allergic'] = "[]";
        }

        try {
            $food = Food::create($req);
            return \returnMessage(true, 'Food added successfully!', $food);
        } catch (Exception $e) {
            if (array_key_exists('photo', $req)) { unlink($req['photo']); }
            return \returnMessage(false, $e->getMessage());
        }
    }

    public function updateFood(Request $req) {

    }

    public function deleteFood() {

    }

    public function listFoods() {

    }

    public function createBeverage(Request $req) {
        $req = $req->only('name', 'photo', 'ingredients', 'price', 'allergic', 'category');
        $validate = Validator::make($req, [
            'name' => 'required|string|unique:beverages,name',
            'photo' => 'mimes:png,jpg,jpeg|max:1024',
            'ingredients' => 'required',
            'price' => 'required|numeric',
            'category' => 'required|numeric|exists:categories,id',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        if (array_key_exists('photo', $req)) {
            $req['photo'] = uploadPhotos($req['photo']);
        }
        if (!array_key_exists('allergic', $req)) {
            $req['allergic'] = "[]";
        }

        try {
            $beverage = Beverage::create($req);
            return \returnMessage(true, 'Beverage added successfully!', $beverage);
        } catch (Exception $e) {
            if (array_key_exists('photo', $req)) { unlink($req['photo']); }
            return \returnMessage(false, $e->getMessage());
        }
    }

    public function updateBeverage(Request $req) {

    }

    public function deleteBeverage() {

    }

    public function listBeverages() {

    }
}
