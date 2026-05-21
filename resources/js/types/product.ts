export interface Product {
  id: string;
  name: string;
  is_active: boolean;
  is_featured: boolean;
  views: number;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
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

// form purposes
export interface FormAttributeValue {
  id?: number;
  value: string;
  is_new?: boolean;
}
export interface FormAttribute {
  id: number;
  name: string;
  values: FormAttributeValue[];
}
export interface ProductVariantOption {
  attribute_id: number | null;
  values: FormAttributeValue[];
}
export interface ProductVariantAttributeForm {
  attribute_id: number;
  attribute_name: string;
  value_id?: number;
  value: string;
  is_new?: boolean;
}
export interface ProductVariantForm {
  sku: string;
  price: number | undefined;
  compare_price: number | undefined;
  stock: number;
  image: File | null;
  attributes: ProductVariantAttributeForm[];
}
export interface ProductForm {
  name: string;
  description: string;
  category_ids: number[];
  is_active: boolean;
  is_featured: boolean;
  images: File[];
  video: File | null;
  variant_options: ProductVariantOption[];
  variants: ProductVariantForm[];
}
