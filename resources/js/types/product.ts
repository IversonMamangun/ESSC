export interface Product {
  id: string;
  name: string;
  slug: string;
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
  id?: number | null;
  sku: string;
  price: number | undefined;
  compare_price: number | undefined;
  stock: number;
  weight: number | undefined;
  image: File | null;
  existingImageUrl?: string | null;
  delete_image?: boolean;
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
// update
export interface ExistingProductImage {
  id: number;
  url: string;
  sort_order: number;
}
export interface ExistingProductData extends Omit<
  ProductForm,
  'images' | 'video' | 'variants'
> {
  id: number;
  slug: string;
  is_active: boolean;
  images: ExistingProductImage[];
  video: string | null;
  variants: ExistingProductVariant[];
}
export interface ExistingProductVariant extends Omit<
  ProductVariantForm,
  'price' | 'compare_price' | 'weight' | 'image'
> {
  id: number;
  price: number;
  compare_price: number | null;
  weight: number | null;
  image: string | null;
  attributes: ProductVariantAttributeForm[];
}
export interface ProductUpdateForm extends ProductForm {
  is_active: boolean;
  deleted_image_ids: number[];
  delete_video: boolean;
  deleted_variant_ids: number[];
}
