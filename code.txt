File#1 web.php,
Route::get('/create', [ProductController::class, 'create']);
Route::post('/store', [ProductController::class, 'store']);

File#2 ProductController.php,
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



File#3 create.blade.php,
<!DOCTYPE html>
<html>
<head>
  <title>Croppie Example</title>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
</head>
<body>
  <form method="POST" action="/store" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" id="image-input" accept="image/*">
    <div id="image-preview"></div>
    <input type="hidden" name="base64_image" id="base64-image">
    <button type="submit">Upload</button>
  </form>
  
  <script>
    $(document).ready(function() {
      var preview = new Croppie($('#image-preview')[0], {
        viewport: {
          width: 800,
          height: 400,
          type: 'square'
        },
        boundary: {
          width: 810,
          height: 410
        },
        enableResize: true,
        enableOrientation: true,
        enableExif: true,
      });

      $('#image-input').on('change', function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();

        reader.onload = function() {
          var base64data = reader.result;
          $('#base64-image').val(base64data);

          preview.bind({
            url: base64data
          }).then(function() {
            console.log('Croppie bind complete');
          });
        }

        reader.readAsDataURL(file);
      });

      $('form').on('submit', function(e) {
        e.preventDefault();

        preview.result('base64').then(function(result) {
          $('#base64-image').val(result);
          $('form')[0].submit();
        });
      });
    });
  </script>
</body>
</html>
