<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    //
    public function index()
    {

        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Colegios",
            'page_title' => 'Colegios',
            'page_functions_js' => 'functions_colegios.js',
        ];

        return view('colleges.index', compact('data'));
    }

    public function getSelectColleges()
    {
        $colleges = College::where('status', '!=', 0)->get();

        $html = '<option value="0">Seleccione un Colegio</option>';
        foreach ($colleges as $college) {
            $html .= '<option value="' . $college->id . '">' . ucwords($college->name) . '</option>';
        }

        return $html;
    }

}
