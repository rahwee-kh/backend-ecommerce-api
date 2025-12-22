<?php
namespace App\Services;

use App\Models\Api\Product;
use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Http\Tools\ParamTools;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductListResource;


class SVProduct extends BaseService{
    
    public function getListProduct($params)
    {
        $perPage       = ParamTools::get_value($params, 'per_page', 10);
        $search        = ParamTools::get_value($params, 'search', '');
        $sortField     = ParamTools::get_value($params, 'sort_field', 'updated_at');
        $sortDirection = ParamTools::get_value($params, 'sort_direction', 'desc');

        $query = Product::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query
        ->whereNull('deleted_at')
        ->orderBy($sortField, $sortDirection)
        ->paginate($perPage);

        return ProductListResource::collection($products);

    }

    public function store($params)
    {
        $user = auth()->user()->id;
        $params['created_by'] = $user;
        $params['updated_by'] = $user;

        $image = $params['image'] ?? null;

        if($image) {
            $relativePath = $this->saveImage($image);
            $params['image'] = URL::to(Storage::url($relativePath));
            $params['image_mime'] = $image->getClientMimeType(); 
            $params['image_size'] = $image->getSize();
        }

        $product = Product::create($params);

        return new ProductResource($product);
    }

    private function saveImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }

    public function show($product)
    {
        return new ProductResource($product);
    }

    public function update($params, $product)
    {
        $params['updated_by'] = auth()->user()->id;

        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $params['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $params['image'] = URL::to(Storage::url($relativePath));
            $params['image_mime'] = $image->getClientMimeType();
            $params['image_size'] = $image->getSize();

            // If there is an old image, delete it
            if ($product->image) {
                Storage::deleteDirectory('/public/' . dirname($product->image));
            }
        }

        $product->update($params);

        return new ProductResource($product);
    }


    public function delete($product)
    {
        $product->delete();
        return response()->noContent();
    }
}