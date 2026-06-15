<script setup lang="ts">
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import {
  StoreIcon,
  PackageIcon,
  TrendingUpIcon,
  ShoppingBagIcon,
  PlusIcon,
  AlertCircleIcon,
  Edit,
  Trash2,
  ExternalLinkIcon,
  Clock,
  Truck,
  CheckCircle2,
} from 'lucide-vue-next';
import { ref, computed, h } from 'vue';
import DataTable from '@/components/DataTable.vue';
import ProductVariantsTable from '@/components/products/ProductVariantsTable.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Pagination from '@/components/Pagination.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { getSellerProductsColumns } from '@/features/seller/columns';
import seller from '@/routes/seller';
import type { Store, PaginatedSellerProducts } from '@/types';

const props = defineProps<{
  store: Store;
  products: PaginatedSellerProducts;
  filters: {
    tab: string;
  };
  counts: {
    active: number;
    inactive: number;
    out_of_stock: number;
  };
}>();

// tab state
const activeTab = computed(() => props.filters.tab);
const tabTitles: Record<string, string> = {
  active: 'Active Products',
  inactive: 'Inactive Products',
  'out-of-stock': 'Out of Stock',
};
const currentTabLabel = computed(() => {
  return tabTitles[props.filters.tab] || 'Products';
});

const viewProduct = (productSlug: string) => {
  // router.visit(seller.products.show(productSlug));
};

const editProduct = (productSlug: string) => {
  router.visit(seller.products.edit(productSlug));
};

const deleteProduct = (productSlug: string) => {
  // router.visit(seller.products.destroy(productSlug));
};

const productColumns = getSellerProductsColumns({
  viewProduct,
  editProduct,
  deleteProduct,
});

const breadcrumbs = [
  {
    title: 'Dashboard',
    href: seller.dashboard(),
  },
  {
    title: 'Products',
    href: seller.products.index(),
  },
];

function changeTab(tab: string) {
  router.get(
    seller.products.index(),
    {
      tab,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    },
  );
}
</script>

<template>
  <Head title="Seller Products" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

    <main class="mx-auto w-full max-w-7xl grow px-4 py-10 sm:px-6 lg:px-8">
      <div class="mb-5 px-5">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>

      <div v-if="props.store.is_active" class="flex flex-col gap-4">
        <div
          class="flex flex-col justify-between gap-6 rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors md:flex-row md:items-center dark:border-zinc-800 dark:bg-zinc-900"
        >
          <div class="flex items-center gap-6">
            <div
              class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#009933] text-3xl font-black text-white shadow-md"
              :class="{
                'bg-transparent': props.store.logo,
              }"
            >
              <img
                v-if="props.store.logo"
                :src="'/storage/' + props.store.logo"
                class="h-full w-full object-cover"
              />
              <span v-else>{{ props.store.name.charAt(0) }}</span>
            </div>
            <div>
              <h2 class="text-2xl font-black text-zinc-900 dark:text-white">
                {{ props.store.name }}
              </h2>
              <Link
                :href="seller.store.edit(props.store.slug)"
                class="mt-1 flex items-center gap-1 text-sm font-medium text-zinc-500 transition-colors hover:text-[#009933] dark:text-zinc-400"
              >
                Edit Store Profile

                <ExternalLinkIcon class="h-3 w-3" />
              </Link>
            </div>
          </div>

          <Link
            :href="seller.products.create()"
            class="flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#009933] px-6 py-3.5 font-bold text-white shadow-md transition-colors hover:bg-green-700 active:scale-95"
          >
            <PlusIcon class="h-5 w-5" /> Add New Product
          </Link>
        </div>
        <div
          class="overflow-hidden rounded-3xl border border-zinc-200 bg-zinc-50 shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
        >
          <div
            class="flex border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900/50"
          >
            <button
              @click="changeTab('active')"
              :class="
                activeTab === 'active'
                  ? 'border-[#009933] bg-green-50/50 text-[#009933] dark:bg-green-900/10'
                  : 'cursor-pointer border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'
              "
              :disabled="activeTab === 'active'"
              class="flex flex-1 items-center justify-center gap-2 border-b-2 py-4 text-center font-black transition-all"
            >
              Active Products
              <span
                class="rounded-full bg-zinc-200 px-2 py-0.5 text-xs text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300"
                >{{ props.counts.active }}</span
              >
            </button>
            <button
              @click="changeTab('inactive')"
              :class="
                activeTab === 'inactive'
                  ? 'border-red-500 bg-red-50/50 text-red-600 dark:bg-red-900/10'
                  : 'cursor-pointer border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'
              "
              :disabled="activeTab === 'inactive'"
              class="flex flex-1 items-center justify-center gap-2 border-b-2 py-4 text-center font-black transition-all"
            >
              Inactive Products
              <span
                class="rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-600 dark:bg-red-900/30 dark:text-red-400"
                >{{ props.counts.inactive }}</span
              >
            </button>
            <button
              @click="changeTab('out-of-stock')"
              :class="
                activeTab === 'out-of-stock'
                  ? 'border-[#009933] bg-green-50/50 text-[#009933] dark:bg-green-900/10'
                  : 'cursor-pointer border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'
              "
              :disabled="activeTab === 'out-of-stock'"
              class="flex flex-1 items-center justify-center gap-2 border-b-2 py-4 text-center font-black transition-all"
            >
              Out of Stock
              <span
                class="rounded-full bg-red-500 px-2 py-0.5 text-xs text-white"
                >{{ props.counts.out_of_stock }}</span
              >
            </button>
          </div>

          <div v-if="products.data.length === 0" class="p-16 text-center">
            <div
              class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800"
            >
              <PackageIcon class="h-10 w-10 text-zinc-400" />
            </div>
            <h3 class="mb-2 text-xl font-bold text-zinc-800 dark:text-white">
              No {{ currentTabLabel }}
            </h3>
          </div>

          <div v-else class="custom-scrollbar overflow-x-auto">
            <DataTable :columns="productColumns" :data="products.data">
              <template #expanded-row="{ row }">
                <ProductVariantsTable :variants="row.variants" />
              </template>
            </DataTable>
          </div>
        </div>
        <div class="-mt-4">
          <Pagination :links="props.products.meta.links" />
        </div>
      </div>

      <div v-else class="flex flex-col gap-8">
        <Alert variant="destructive">
          <AlertCircleIcon class="mt-1 h-5 w-5" />
          <AlertTitle class="text-xl font-semibold">Store Inactive</AlertTitle>
          <AlertDescription class="mt-1">
            The store {{ props.store.name }} is currently deactivated.
            <span> Please contact support for more information. </span>
          </AlertDescription>
        </Alert>
      </div>
    </main>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e4e4e7;
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: #3f3f46;
}
</style>
