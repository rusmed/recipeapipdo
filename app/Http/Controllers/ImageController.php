<?php

namespace App\Http\Controllers;

use App\Models\Db\Image;
use Illuminate\Http\Request;

/**
 * Class ImageController
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{

    /**
     * Upload image and move to directory UPLOADS_DIR
     * @param Request $request
     */
    public function uploadImage(Request $request)
    {
        $imageFile = $request->file('image');
        if (!$imageFile) {
            return response()->json(['image' => 'The image field is required.'],400);
        }

        $ext = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $request->file('image')->move(UPLOADS_DIR, $filename);

        $image = new \App\Models\Image();
        $image->setUrl($filename);

        $dbImage = new Image();
        $dbImage->save($image);

        return response()->json($dbImage->modelToArray($image), 201);
    }

}
