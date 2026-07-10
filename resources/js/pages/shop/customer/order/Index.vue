<script setup lang="ts">
import { Head, useHttp, router } from '@inertiajs/vue3';
import {
  PackageIcon,
  SearchIcon,
  CircleCheckBigIcon,
  CircleXIcon,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import OrderCard from '@/components/orders/OrderCard.vue';
import OrderReviewDetailsDialog from '@/components/orders/OrderReviewDetailsDialog.vue';
import Pagination from '@/components/Pagination.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import shop from '@/routes/shop';
import type {
  User,
  OrderDisplayStatus,
  PaginatedOrders,
  ReviewShow,
} from '@/types';

const props = defineProps<{
  user: User;
  orders: PaginatedOrders;
  filters: {
    status: OrderDisplayStatus;
  };
}>();

const tabs = [
  {
    label: 'All',
    value: 'all',
  },
  {
    label: 'To Pay',
    value: 'to-pay',
  },
  {
    label: 'To Ship',
    value: 'to-ship',
  },
  {
    label: 'To Receive',
    value: 'to-receive',
  },
  {
    label: 'Delivered',
    value: 'delivered',
  },
  {
    label: 'Completed',
    value: 'completed',
  },
  {
    label: 'Cancelled',
    value: 'cancelled',
  },
  {
    label: 'Returned',
    value: 'returned',
  },
];

function changeStatus(status: string) {
  router.get(
    shop.orders.index.url(),
    {
      status,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    },
  );
}

// cancel & complete
type ActionType = 'complete' | 'cancel';
interface Action {
  type: ActionType;
  orderNo: string;
}
const action = ref<Action | null>(null);
const confirmOpen = computed({
  get: () => action.value !== null,
  set: (val: boolean) => {
    if (!val) action.value = null;
  },
});
// config per action type
const dialogConfig = computed(() => {
  switch (action.value?.type) {
    case 'complete':
      return {
        title: 'Complete this order?',
        description:
          "Confirm that you've received your order. This can't be undone.",
        confirmText: 'Complete Order',
        confirmVariant: 'default' as const,
        icon: CircleCheckBigIcon,
      };
    case 'cancel':
      return {
        title: 'Cancel this order?',
        description: 'This will cancel your order and cannot be undone.',
        confirmText: 'Cancel Order',
        confirmVariant: 'destructive' as const,
        icon: CircleXIcon,
      };
    default:
      return {
        title: '',
        description: '',
        confirmText: 'Confirm',
        confirmVariant: 'default' as const,
        icon: undefined,
      };
  }
});
const cancelOrder = (orderNo: string) => {
  action.value = { type: 'cancel', orderNo };
};
const completeOrder = (orderNo: string) => {
  action.value = { type: 'complete', orderNo };
};
// confirm endpoint
const handleConfirmAction = () => {
  if (!action.value) return;
  const { type, orderNo } = action.value;

  const requests: Record<ActionType, () => void> = {
    complete: () =>
      router.patch(shop.orders.complete(orderNo), {}, { preserveScroll: true }),
    cancel: () =>
      router.patch(shop.orders.cancel(orderNo), {}, { preserveScroll: true }),
  };

  requests[type]();
  action.value = null;
};

const requestReturnOrder = (orderNo: string) => {
  router.visit(shop.orders.return.create(orderNo));
};

const rateOrder = (orderNo: string) => {
  router.visit(shop.orders.review.create(orderNo));
};

// rating details logic
const ratingDialogOpen = ref(false);
const ratingItems = ref<ReviewShow[]>([]);
const ratingHttp = useHttp({});
const ratingOrderNo = ref<string | null>(null);
const ratingCanEdit = ref(false);

const viewRatingOrder = async (orderNo: string) => {
  const orderData = props.orders.data.find((o) => o.order_number === orderNo);

  ratingCanEdit.value = orderData?.is_edit_rate_eligible ?? false;
  ratingOrderNo.value = orderNo;
  ratingDialogOpen.value = true;
  ratingItems.value = [];

  const response = (await ratingHttp.get(
    shop.orders.review.show.url(orderNo),
  )) as { items: ReviewShow[] };

  ratingItems.value = response.items;
};
</script>

<template>
  <Head title="My Purchases" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="mx-auto w-full max-w-7xl grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <div class="flex flex-col gap-8 lg:flex-row">
        <UserAccountSidebar :name="user.name" :avatar="user.avatar" />

        <div class="min-w-0 flex-1">
          <div
            class="mb-6 overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
          >
            <div class="custom-scrollbar flex overflow-x-auto">
              <button
                v-for="tab in tabs"
                :key="tab.value"
                @click="changeStatus(tab.value)"
                class="min-w-[100px] flex-1 border-b-2 py-4 text-center text-sm font-black whitespace-nowrap transition-all"
                :class="
                  props.filters.status === tab.value
                    ? 'border-[#009933] bg-green-50/30 text-[#009933] dark:bg-green-900/10'
                    : 'cursor-pointer border-transparent text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200'
                "
                :disabled="props.filters.status === tab.value"
              >
                {{ tab.label }}
              </button>
            </div>
          </div>

          <div class="relative mb-6">
            <SearchIcon class="absolute top-3.5 left-4 h-5 w-5 text-zinc-400" />
            <input
              type="text"
              placeholder="Search by Order ID or Product Name"
              class="w-full rounded-xl border border-zinc-200 bg-white py-3.5 pr-4 pl-12 text-sm text-zinc-900 shadow-sm transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
            />
          </div>

          <div
            v-if="orders.data.length === 0"
            class="rounded-3xl border border-zinc-200 bg-zinc-50 p-16 text-center shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
          >
            <div
              class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800"
            >
              <PackageIcon class="h-10 w-10 text-zinc-300 dark:text-zinc-600" />
            </div>
            <h3 class="mb-2 text-xl font-black text-zinc-800 dark:text-white">
              No orders yet
            </h3>
            <p class="text-zinc-500 dark:text-zinc-400">
              You don't have any orders with this status.
            </p>
          </div>

          <div v-else class="flex max-h-[calc(100vh-320px)] flex-col">
            <div class="custom-scrollbar space-y-6 overflow-y-auto pr-2 pb-3">
              <OrderCard
                v-for="order in orders.data"
                :key="order.id"
                :order="order"
                @cancel="cancelOrder"
                @complete="completeOrder"
                @requestReturn="requestReturnOrder"
                @rate="rateOrder"
                @viewRating="viewRatingOrder"
              />
            </div>

            <div class="shrink-0">
              <Pagination :links="orders.meta.links" />
            </div>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>

  <ConfirmDialog
    v-model:open="confirmOpen"
    :title="dialogConfig.title"
    :confirm-text="dialogConfig.confirmText"
    :confirm-variant="dialogConfig.confirmVariant"
    :icon="dialogConfig.icon"
    :confirmDisabled="!action"
    @confirm="handleConfirmAction"
    @cancel="action = null"
  >
    <template #description>{{ dialogConfig.description }}</template>
  </ConfirmDialog>

  <OrderReviewDetailsDialog
    v-model:open="ratingDialogOpen"
    :loading="ratingHttp.processing"
    :items="ratingItems"
    :order-number="ratingOrderNo"
    :can-edit="ratingCanEdit"
  />
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  height: 4px;
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
