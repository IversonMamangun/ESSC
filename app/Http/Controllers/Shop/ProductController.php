<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ProductCardResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(['top-deals', 'discover'])],
        ]);

        $filters = [
            'type' => $validated['type'] ?? 'top-deals',
        ];

        return Inertia::render('shop/public/product/Index', [
            'products' => ProductCardResource::collection(
                $this->buildBaseQuery($filters)
                ->paginate(20)
                ->withQueryString()
            ),
            'filters' => $filters,
        ]);
    }

    private function buildBaseQuery(array $filters): Builder
    {
        return Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'is_featured',
                'created_at',
            ])
            ->with([
                'images:id,product_id,image,sort_order',
                'defaultVariant:id,product_id,price,compare_price',
            ])
            ->withSum('variants as total_stock', 'stock')
            ->having('total_stock', '>', 0)
            ->where('is_active', true)
            ->when(
                $filters['type'] === 'top-deals',
                fn (Builder $query) => $query
                    ->where('is_featured', true)
                    ->latest(),

                fn (Builder $query) => $query
                    ->latest()
            );
    }
}
