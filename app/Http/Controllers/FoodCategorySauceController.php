<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Sauce;
use App\Models\Beverage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodCategorySauceController extends Controller
{
    // for food categories
    public function createCategory(Request $req) {
        $req = $req->only('name', 'before', 'after');
        $validate = Validator::make($req, [
            'name' => 'required|string|unique:categories,name',
            'before' => 'date_format:H:i', 
            'after' => 'date_format:H:i|after:before'
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        $category = Category::create($req);

        if ($category) 
            return returnMessage(true, "Category ".$req['name']." has been created!", $category);
        else 
            return returnMessage(false, "Category ".$req['name']." couldn't been created!");
    }

    public function updateCategory(Request $req) {
        $req = $req->only('id', 'name', 'before', 'after');
        $validate = Validator::make($req, [
            'id' => 'required|exists:categories',
            'name' => 'string|unique:categories,name,'.$req['id'],
            'before' => 'date_format:H:i', 
            'after' => 'date_format:H:i|after:before'
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        $category = Category::find($req['id'])->update($req);

        if ($category) 
            return returnMessage(true, "Category ".$req['name']." has been updated!", $req);
        else 
            return returnMessage(false, "Category ".$req['name']." couldn't been updated!");
    }

    public function deleteCategory($id) {
        $category = Category::find($id);
        if (!$category) 
            return returnMessage(false, 'The selected id is invalid!');
        else {
            $food = Food::where('category', $id)->count();
            $beverage = Beverage::where('category', $id)->count();

            if ($food > 0 || $beverage > 0) {
                return returnMessage(false, 'Category: '.$category->name.' cannot be deleted as food or beverages are associated with this category!');
            } else if ($food === 0 && $beverage === 0) {
                $category->delete();
                return returnMessage(true, 'Category: '.$category->name.' has been deleted!');
            }
        }
    }

    public function listCategories() {
        $list = Category::get();
        return returnMessage(true, 'Category list retreival success!', $list);
    }

    // for food sauces
    public function createSauce(Request $req) {
        $req = $req->only('name', 'allergic');
        $validate = Validator::make($req, [
            'name' => 'required|string|unique:categories,name',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        $sauce = Sauce::create($req);

        if ($sauce) 
            return returnMessage(true, "Sauce ".$req['name']." has been created!", $sauce);
        else 
            return returnMessage(false, "Sauce ".$req['name']." couldn't been created!");
    }

    public function updateSauce(Request $req) {
        $req = $req->only('id', 'name', 'allergic');
        $validate = Validator::make($req, [
            'id' => 'required|exists:sauces',
            'name' => 'string|unique:categories,name,'.$req['id'],
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }

        $sauce = Sauce::find($req['id'])->update($req);

        if ($sauce) 
            return returnMessage(true, "Sauce ".$req['name']." has been udpated!", $sauce);
        else 
            return returnMessage(false, "Sauce ".$req['name']." couldn't been udpated!");
    }

    public function deleteSauce() {

    }

    public function listSauces() {
        $list = Sauce::get();
        return returnMessage(true, 'Sauces list retreival success!', $list);
    }
}
