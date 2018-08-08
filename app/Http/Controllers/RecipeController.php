<?php

namespace App\Http\Controllers;

use App\Models\Db\Image;
use App\Models\Db\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{

    /**
     * Create a new recipe
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRecipe(Request $request)
    {
        $this->validate($request, [
            'title'     => 'required|string',
            'body'      => 'required|string',
            'image_id'  => 'required|integer'
        ]);

        $dbImage = new Image();
        $image = $dbImage->fetchEntryById($request->input('image_id'));
        if (!is_object($image)) {
            return response()->json(['message' => 'Image not found'], 400);
        }

        $recipe = new \App\Models\Recipe();
        $recipe->setTitle($request->input('title'));
        $recipe->setBody($request->input('body'));
        $recipe->setAuthorId($request->user()->getId());
        $recipe->setImageId($request->input('image_id'));

        $dbRecipe = new Recipe();
        $dbRecipe->save($recipe);

        $recipe->setAuthor($request->user());
        $recipe->setImage($image);

        return response()->json($dbRecipe->relModelToArray($recipe), 201);
    }

    /**
     * Update recipe
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecipe(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            return response()->json(['message' => 'Must be provided ID'], 400);
        }

        $this->validate($request, [
            'title'     => 'required|string',
            'body'      => 'required|string',
            'image_id'  => 'required|integer'
        ]);

        $dbRecipe = new Recipe();
        $recipe = $dbRecipe->fetchEntryById($id);
        if (!is_object($recipe)) {
            return response()->json(['message' => 'Recipe not found'], 400);
        }

        if ($recipe->getAuthorId() != $request->user()->getId()) {
            return response()->json(['message' => 'Access denied!'], 403);
        }

        if ($request->input('image_id') != $recipe->getImageId()) {
            $dbImage = new Image();
            $image = $dbImage->fetchEntryById($request->input('image_id'));
            if (!is_object($image)) {
                return response()->json(['message' => 'Image not found'], 400);
            }
            $recipe->setImage($image);
        }

        $recipe->setTitle($request->input('title'));
        $recipe->setBody($request->input('body'));
        $recipe->setImageId($request->input('image_id'));
        $dbRecipe->save($recipe);

        return response()->json($dbRecipe->relModelToArray($recipe), 200);
    }

    /**
     * Delete recipe
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRecipe(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            return response()->json(['message' => 'Must be provided ID'], 400);
        }

        $dbRecipe = new Recipe();
        $recipe = $dbRecipe->fetchEntryById($id);
        if (!is_object($recipe)) {
            return response()->json(['message' => 'Recipe not found'], 400);
        }

        if ($recipe->getAuthorId() != $request->user()->getId()) {
            return response()->json(['message' => 'Access denied!'], 403);
        }

        $dbRecipe->delete($id);

        return response()->json(null, 204);
    }

    /**
     * Get list recipes
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipes()
    {
        $dbRecipe = new Recipe();
        $recipes = $dbRecipe->fetchEntries();

        return response()->json($dbRecipe->relModelsToArray($recipes));
    }

    /**
     * Get recipe by id
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipe($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $dbRecipe = new Recipe();
        $recipe = $dbRecipe->fetchEntryById($id);
        if (!is_object($recipe)) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        return response()->json($dbRecipe->relModelToArray($recipe));
    }
    
}
