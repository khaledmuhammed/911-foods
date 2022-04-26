<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        $slides = array();
        foreach (Slide::all() as $slide) {
            if ($slide->has_media) {
                array_unshift($slides, [
                    "id" => $slide->id,
                    "text" => $slide->text,
                    "image" => $slide->getFirstMediaUrl('image')
                ]);
            }
        }
        if (count($slides) == 0) {
            array_unshift($slides, [
                "id" => 1,
                "text" => 'Add Slides from admin pannel !!',
                "image" => asset('/images/empty.jpg')
            ]);
        };
        return response()->json($slides);
    }
}
