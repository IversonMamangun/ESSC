<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ProductCardResource;
use App\Http\Resources\Shop\ProductShowResource;
use App\Http\Resources\Shop\ProductReviewShowResource;
use Illuminate\Support\Facades\Cache;
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
            'type' => ['sometimes', 'string', Rule::in(['top-deals', 'discover'])],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $filters = [
            'type' => $validated['type'] ?? 'top-deals',
            'search' => $validated['search'] ?? null,
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
                'rating',
                'reviews_count',
                'sold_count',
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
                fn (Builder $query) => $query->where('is_featured', true),
            )
            ->when(
                filled($filters['search'] ?? null),
                fn (Builder $query) => $query->where('name', 'like', '%' . $filters['search'] . '%'),
            )
            ->latest();
    }

    public function show(Request $request, Product $product)
    {
        $this->recordView($request, $product);

        return Inertia::render('shop/public/product/Show', [
            'product' => fn () => ProductShowResource::make(
                $product->load([
                    'store',
                    'categories',
                    'images',
                    'variants.attributeValues.attribute',
                ])
            )->resolve(),

            'reviews' => fn () => ProductReviewShowResource::collection(
                $product->reviews()
                    ->with(['user', 'images'])
                    ->latest()
                    ->paginate(10, ['*'], 'reviews_page')
                    ->withQueryString()
            ),
        ]);
    }

    private function recordView(Request $request, Product $product): void
    {
        $identifier = $request->user()?->id ?? $request->ip();
        if ($request->user()?->id === $product->store->user_id) {
            return;
        }

        $cacheKey = "product-view:{$product->id}:{$identifier}";
        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, now()->addHours(24));

        $product->increment('views');
    }
}