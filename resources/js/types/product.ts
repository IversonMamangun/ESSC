export interface Product {
  id: string;
  name: string;
  is_active: boolean;
  is_featured: boolean;
  views: number;
}

interface VariantAttribute {
  attribute: string;
  value: string;
}

export interface ProductVariant {
  id: number;
  sku: string;
  price: number;
  compare_price?: number | null;
  stock: number;
  image?: string | null;
  is_default: boolean;
  attributes: VariantAttribute[];
}

export interface SellerProduct extends Product {
  thumbnail?: string | null;
  variant_count: number;
  total_stock: number;
  min_price: number;
  max_price: number;
  variants: any[];
}
