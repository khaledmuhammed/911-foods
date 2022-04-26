<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Faq_categorie;

class FaqController extends Controller
{
    public function indexFaq()
    {
        $faqs = array();
        foreach (Faq::latest()->get() as $faq) {
            array_push($faqs, [
                "faq" => $faq,
                "categorie" => $faq->category,
            ]);
        }
        return response()->json([
            "faqs" => $faqs
        ]);
    }
    public function indexCategorys()
    {
        return response()->json([
            "categories" => Faq_categorie::latest()->get()
        ]);
    }
}
