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
            'ingredients' => 'required|string',
            'price' => 'required|numeric',
            'allergic' => 'string',
            'hot' => 'integer',
            'sauce' => 'string',
            'category' => 'required|exists:categories,id',
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
        $req = $req->only('id', 'name', 'photo', 'ingredients', 'price', 'allergic', 'hot', 'sauce', 'category');
        if (!array_key_exists('id', $req))
            return returnMessage(false, 'The ID field is required!');

        $validate = Validator::make($req, [
            'id' => 'exists:foods',
            'name' => 'string|unique:foods,name,'.$req['id'],
            'photo' => 'mimes:png,jpg,jpeg|max:1024',
            'ingredients' => 'string',
            'price' => 'numeric',
            'allergic' => 'string',
            'hot' => 'integer',
            'sauce' => 'string',
            'category' => 'exists:categories,id',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        $food = Food::find($req['id']);
        $old_photo = $food->photo;

        if (array_key_exists('photo', $req)) {
            $req['photo'] = uploadPhotos($req['photo']);
            $req['flag'] = 0;
        } else {
            $req['photo'] = $old_photo;
            $req['flag'] = 1;
        }

        try {
            $food = $food->update($req);
            if ($req['flag'] === 0) {
                if (file_exists($old_photo))
                    unlink($old_photo);
            }
            return \returnMessage(true, 'Food updated successfully!');
        } catch (Exception $e) {
            if (array_key_exists('photo', $req)) { unlink($req['photo']); }
            return \returnMessage(false, $e->getMessage());
        }
    }

    public function deleteFood() {

    }

    public function listFoods() {
        $food = Food::get();
        return returnMessage(true, 'List of foods retreival successful!', $food);
    }

    public function createBeverage(Request $req) {
        $req = $req->only('name', 'photo', 'ingredients', 'price', 'allergic', 'category');
        $validate = Validator::make($req, [
            'name' => 'required|string|unique:beverages,name',
            'photo' => 'mimes:png,jpg,jpeg|max:1024',
            'ingredients' => 'required|string',
            'price' => 'required|numeric',
            'allergic' => 'string',
            'category' => 'required|exists:categories,id',
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
        $req = $req->only('id', 'name', 'photo', 'ingredients', 'price', 'allergic', 'category');
        if (!array_key_exists('id', $req))
            return returnMessage(false, 'The ID field is required!');

        $validate = Validator::make($req, [
            'id' => 'exists:beverages',
            'name' => 'string|unique:beverages,name,'.$req['id'],
            'photo' => 'mimes:png,jpg,jpeg|max:1024',
            'ingredients' => 'string',
            'price' => 'numeric',
            'allergic' => 'string',
            'category' => 'exists:categories,id',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        $beverage = Beverage::find($req['id']);
        $old_photo = $beverage->photo;

        if (array_key_exists('photo', $req)) {
            $req['photo'] = uploadPhotos($req['photo']);
            $req['flag'] = 0;
        } else {
            $req['photo'] = $old_photo;
            $req['flag'] = 1;
        }

        try {
            $beverage = $beverage->update($req);
            if ($req['flag'] === 0) {
                if (file_exists($old_photo))
                    unlink($old_photo);
            }
            return \returnMessage(true, 'everage updated successfully!');
        } catch (Exception $e) {
            if (array_key_exists('photo', $req)) { unlink($req['photo']); }
            return \returnMessage(false, $e->getMessage());
        }
    }

    public function deleteBeverage() {

    }

    public function listBeverages() {
        $beverage = Beverage::get();
        return returnMessage(true, 'List of beverages retreival successful!', $beverage);
    }
}
