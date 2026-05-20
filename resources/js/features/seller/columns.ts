import { h } from 'vue';
import type { ColumnDef } from '@tanstack/vue-table';
import type { SellerProduct } from '@/types';
import { Badge } from '@/components/ui/badge';

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(price);
};

export const sellerProductsColumns: ColumnDef<SellerProduct>[] = [
  {
    accessorKey: 'name',
    header: 'Product',
    cell: ({ row }) => {
      const product = row.original;

      return h(
        'div',
        {
          class: 'flex items-center gap-3 font-medium',
        },
        [
          h('img', {
            src: product.thumbnail
              ? `/storage/${product.thumbnail}`
              : '/placeholder.png',

            class: 'h-12 w-12 rounded-lg object-cover border',
          }),

          h('span', product.name),
        ],
      );
    },
  },
  {
    accessorKey: 'is_active',
    header: 'Status',
    cell: ({ row }) => {
      const isActive = row.original.is_active;

      return h(
        Badge,
        {
          variant: isActive ? 'default' : 'destructive',
        },
        () => (isActive ? 'Active' : 'Inactive'),
      );
    },
  },
  {
    accessorKey: 'variant_count',
    header: 'Variants',
  },
  {
    accessorKey: 'total_stock',
    header: 'Stock',
  },
  {
    accessorKey: 'views',
    header: 'Views',
  },
  {
    id: 'price',
    header: 'Price Range',
    cell: ({ row }) => {
      const product = row.original;

      return product.min_price === product.max_price
        ? formatPrice(product.min_price)
        : `${formatPrice(product.min_price)} - ${formatPrice(product.max_price)}`;
    },
  },
];
