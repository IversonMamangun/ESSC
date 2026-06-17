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
import OrderItemsTable from '@/components/orders/OrderItemsTable.vue';
import SellerTab, { type SellerTabItem } from '@/components/SellerTab.vue';
import SellerStoreHeader from '@/components/SellerStoreHeader.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Pagination from '@/components/Pagination.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { getSellerOrdersColumns } from '@/features/seller/columns';
import seller from '@/routes/seller';
import type { Store, PaginatedSellerOrders } from '@/types';

const props = defineProps<{
  store: Store;
  orders: PaginatedSellerOrders;
  filters: {
    tab: string;
  };
  counts: {
    to_confirm: number;
    to_ship: number;
    to_deliver: number;
    cancellation: number;
  };
}>();

// tab state
const activeTab = computed(() => props.filters.tab);
const orderTabs = computed<SellerTabItem[]>(() => [
  {
    label: 'To Confirm Orders',
    value: 'to-confirm',
    count: props.counts.to_confirm,
  },
  {
    label: 'To Ship Orders',
    value: 'to-ship',
    count: props.counts.to_ship,
  },
  {
    label: 'To Deliver Orders',
    value: 'to-deliver',
    count: props.counts.to_deliver,
  },
  {
    label: 'Cancelled Orders',
    value: 'cancellation',
    count: props.counts.cancellation,
  },
]);
const currentTabLabel = computed(() => {
  const currentTab = orderTabs.value.find(
    (tab) => tab.value === activeTab.value,
  );
  return currentTab ? currentTab.label : 'Orders';
});

const viewOrder = (orderNo: string) => {
  // router.visit(seller.orders.show(orderNo));
};

const editOrder = (orderNo: string) => {
  // router.visit(seller.orders.edit(orderNo));
};

const orderColumns = getSellerOrdersColumns({
  viewOrder,
  editOrder,
});

const breadcrumbs = [
  {
    title: 'Dashboard',
    href: seller.dashboard(),
  },
  {
    title: 'Orders',
    href: seller.orders.index(),
  },
];

function changeTab(tab: string) {
  router.get(
    seller.orders.index(),
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
  <Head title="Seller Orders" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

    <main class="mx-auto w-full max-w-7xl grow px-4 py-10 sm:px-6 lg:px-8">
      <div class="mb-5 px-5">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>

      <div v-if="props.store.is_active" class="flex flex-col gap-4">
        <SellerStoreHeader
          :store="props.store"
          :edit-store-href="seller.store.edit.url(props.store.slug)"
        >
          <template #actions>
            <Link
              :href="seller.products.create()"
              class="flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#009933] px-6 py-3.5 font-bold text-white shadow-md transition-colors hover:bg-green-700 active:scale-95"
            >
              <PlusIcon class="h-5 w-5" /> Add New Product
            </Link>
          </template>
        </SellerStoreHeader>
        <div
          class="overflow-hidden rounded-3xl border border-zinc-200 bg-zinc-50 shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
        >
          <SellerTab
            :model-value="activeTab"
            :tabs="orderTabs"
            @change="changeTab"
          />

          <div v-if="orders.data.length === 0" class="p-16 text-center">
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
            <DataTable :columns="orderColumns" :data="orders.data">
              <template #expanded-row="{ row }">
                <OrderItemsTable :items="row.items" />
              </template>
            </DataTable>
          </div>
        </div>
        <div class="-mt-4">
          <Pagination :links="props.orders.meta.links" />
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
