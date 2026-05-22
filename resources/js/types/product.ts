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
  image?: string | null;
  children: {
    data: Category[];
  };
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
export interface ProductVariantAttributeForm {
  attribute_id: number | null;
  value_id: number | null;
  value: string;
  is_new?: boolean;
}
export interface ProductVariantForm {
  sku: string;
  price: number | undefined;
  compare_price: number | undefined;
  stock: number;
  weight: number | undefined;
  image: File | null;
  is_default: boolean;
  attributes: ProductVariantAttributeForm[];
}
export interface ProductForm {
  name: string;
  description: string;
  category_ids: number[];
  is_featured: boolean;
  images: File[];
  video: File | null;
  variants: ProductVariantForm[];
}
