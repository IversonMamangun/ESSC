<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { AlertCircleIcon, MessageSquareOffIcon } from 'lucide-vue-next';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Pagination from '@/components/Pagination.vue';
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
        <div
          class="flex items-center justify-between rounded-2xl bg-card/40 px-6 py-4 shadow"
        >
          <div>
            <h1 class="text-2xl font-semibold tracking-tight">Reviews</h1>
            <p class="text-sm text-muted-foreground">
              Feedback customers left on your products.
            </p>
          </div>
          <div
            class="flex items-center gap-2 rounded-lg border bg-card px-3 py-1.5"
          >
            <span class="text-sm font-medium">{{
              props.reviews.meta.total
            }}</span>
            <span class="text-sm text-muted-foreground">total reviews</span>
          </div>
        </div>

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
