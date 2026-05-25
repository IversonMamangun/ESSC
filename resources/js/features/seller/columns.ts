import type { ColumnDef } from '@tanstack/vue-table';
import { MoreHorizontal } from 'lucide-vue-next';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { SellerProduct } from '@/types';


const formatPrice = (price: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(price);
};

export const getSellerProductsColumns = ({
  viewProduct,
  editProduct,
  deleteProduct,
}: {
  viewProduct: (slug: string) => void;
  editProduct: (slug: string) => void;
  deleteProduct: (slug: string) => void;
}): ColumnDef<SellerProduct>[] => [
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
  {
    id: 'actions',
    header: () => h('div', { class: 'text-center' }, 'Actions'),
    cell: ({ row }) => {
      const product = row.original;

      return h('div', { class: 'relative text-center' }, [
        h(DropdownMenu, null, () => [
          h(
            DropdownMenuTrigger,
            { asChild: true, class: 'cursor-pointer' },
            () =>
              h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () => [
                h('span', { class: 'sr-only' }, 'Open menu'),
                h(MoreHorizontal, { class: 'h-4 w-4' }),
              ]),
          ),
          h(DropdownMenuContent, { align: 'end', class: 'border-2' }, () => [
            h(DropdownMenuLabel, { class: 'text-gray-500' }, () => 'Actions'),
            h(
              DropdownMenuItem,
              {
                class: 'cursor-pointer',
                onClick: () => viewProduct(product.slug),
              },
              () => 'View Product Details',
            ),
            h(DropdownMenuSeparator),
            h(
              DropdownMenuItem,
              {
                class: 'cursor-pointer text-blue-500 focus:text-blue-600',
                onClick: () => editProduct(product.slug),
              },
              () => 'Edit Product',
            ),
            h(
              DropdownMenuItem,
              {
                class: 'cursor-pointer text-rose-500 focus:text-rose-600',
                onClick: () => deleteProduct(product.slug),
              },
              () => 'Delete Product',
            ),
          ]),
        ]),
      ]);
    },
  },
];
