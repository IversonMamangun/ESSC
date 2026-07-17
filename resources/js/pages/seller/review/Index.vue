<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
  AlertCircleIcon,
  MessageSquareOffIcon,
  StarIcon,
  AwardIcon,
  BoxIcon,
} from 'lucide-vue-next';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Pagination from '@/components/Pagination.vue';
import SellerStoreHeader from '@/components/SellerStoreHeader.vue';
import SellerReviewCard from '@/components/review/SellerReviewCard.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Card, CardContent } from '@/components/ui/card';
import seller from '@/routes/seller';
import type { Store, PaginatedSellerReview } from '@/types';

const props = defineProps<{
  store: Store;
  reviews: PaginatedSellerReview;
}>();

const breadcrumbs = [
  { title: 'Dashboard', href: seller.dashboard() },
  { title: 'Reviews', href: seller.reviews.index() },
];
</script>

<template>
  <Head title="Reviews" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

    <main class="mx-auto w-full max-w-7xl grow px-4 py-10 sm:px-6 lg:px-8">
      <div class="mb-5 px-5">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>

      <div v-if="props.store.is_active" class="flex flex-col gap-6">
        <!-- Header -->
        <SellerStoreHeader :store="props.store">
          <template #details>
            <div
              class="flex items-center rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-1.5 text-sm text-zinc-600 transition-colors dark:border-zinc-700/50 dark:bg-zinc-800/50 dark:text-zinc-300"
            >
              <StarIcon class="mr-1.5 h-4 w-4 fill-current text-amber-400" />
              <span class="font-bold text-zinc-800 dark:text-zinc-100">
                {{ store.rating.toFixed(1) }}
              </span>
              <span class="ml-1 text-xs text-zinc-600 dark:text-zinc-400">
                ({{ store.reviews_count }} reviews)
              </span>
            </div>

            <div
              class="flex items-center rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-1.5 text-sm text-zinc-600 transition-colors dark:border-zinc-700/50 dark:bg-zinc-800/50 dark:text-zinc-300"
            >
              <BoxIcon
                class="mr-1.5 h-4 w-4 fill-white text-zinc-400 dark:fill-black"
              />
              <span class="font-bold text-zinc-800 dark:text-zinc-100">
                {{ store.sold_count }}
              </span>
              <span class="ml-1 text-xs text-zinc-600 dark:text-zinc-400"
                >sold</span
              >
            </div>
          </template>
          <template #actions>
            <span
              v-if="store.is_official"
              class="mx-auto flex w-max items-center rounded bg-[#009933] py-2 ps-2 pe-3.5 text-[10px] font-black tracking-wider text-white uppercase shadow-sm md:mx-0"
            >
              <AwardIcon class="mr-1.5 h-4 w-4 fill-amber-400" />
              Official Store
            </span>
          </template>
        </SellerStoreHeader>

        <!-- Empty state -->
        <Card v-if="props.reviews.data.length === 0">
          <CardContent
            class="flex flex-col items-center gap-3 py-16 text-center"
          >
            <MessageSquareOffIcon class="h-10 w-10 text-muted-foreground/50" />
            <div>
              <p class="font-medium">No reviews yet</p>
              <p class="text-sm text-muted-foreground">
                Reviews from your customers will show up here once they leave
                one.
              </p>
            </div>
          </CardContent>
        </Card>

        <!-- Review list -->
        <div v-else class="flex flex-col gap-4">
          <SellerReviewCard
            v-for="review in reviews.data"
            :key="review.id"
            :review="review"
          />
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
          <Pagination :links="props.reviews.meta.links" />
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
