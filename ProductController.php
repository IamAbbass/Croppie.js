<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function create()
    {
        return view('create');
    }

    public function store(REquest $request)
    {
        $base64_image         = $request->base64_image;
        list($type, $data)  = explode(';', $base64_image);
        list(, $data)       = explode(',', $data);
        $data               = base64_decode($data);
        $thumb_name         = "thumb_".date('YmdHis').'.png';
        $thumb_path         = public_path("uploads/" . $thumb_name);
        file_put_contents($thumb_path, $data);

        dd($thumb_path);

    }
    
}
