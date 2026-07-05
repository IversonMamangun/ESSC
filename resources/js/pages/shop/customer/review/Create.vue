<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
  ArrowLeftIcon,
  StarIcon,
  PackageIcon,
  StoreIcon,
  XIcon,
  VideoIcon,
  CameraIcon,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import shop from '@/routes/shop';
import type { User, Order } from '@/types';

const props = defineProps<{
  user: User;
  order: Order;
}>();

const form = useForm({
  items: props.order.items.map((item) => ({
    order_item_id: item.id,
    rating: 5,
    comment: '',
    images: [] as File[],
    video: null as File | null,
  })),
});

// Explicit reactive hover tracking dictionary for star interactions
const hoverRatings = ref<Record<number, number>>({});

const setRating = (itemIndex: number, score: number) => {
  form.items[itemIndex].rating = score;
};
const setHoverRating = (itemIndex: number, score: number) => {
  hoverRatings.value[itemIndex] = score;
};
const clearHoverRating = (itemIndex: number) => {
  delete hoverRatings.value[itemIndex];
};

const MAX_IMAGES = 5;
const MAX_VIDEO = 1;

const imagePreviews = ref<Record<number, string[]>>({});
const videoPreviews = ref<Record<number, string | null>>({});

const getImageCount = (index: number) =>
  computed(() => form.items[index].images.length);

const canAddImages = (index: number) =>
  computed(() => getImageCount(index).value < MAX_IMAGES);

const hasVideo = (index: number) =>
  computed(() => !!form.items[index].video || !!videoPreviews.value[index]);

const handleImages = (index: number, e: Event) => {
  const input = e.target as HTMLInputElement;
  const files = Array.from(input.files ?? []);

  if (!files.length) return;

  if (!imagePreviews.value[index]) {
    imagePreviews.value[index] = [];
  }
  const remaining = MAX_IMAGES - form.items[index].images.length;
  const allowed = files.slice(0, remaining);

  form.items[index].images.push(...allowed);

  imagePreviews.value[index].push(
    ...allowed.map((file) => URL.createObjectURL(file)),
  );
  input.value = '';
};

const removeImage = (itemIndex: number, imageIndex: number) => {
  form.items[itemIndex].images.splice(imageIndex, 1);

  URL.revokeObjectURL(imagePreviews.value[itemIndex][imageIndex]);
  imagePreviews.value[itemIndex].splice(imageIndex, 1);
};

const handleVideo = (index: number, e: Event) => {
  if (hasVideo(index).value) return;

  const file = (e.target as HTMLInputElement).files?.[0];
  if (!file) return;

  form.items[index].video = file;
  videoPreviews.value[index] = URL.createObjectURL(file);
};

const removeVideo = (index: number) => {
  form.items[index].video = null;

  if (videoPreviews.value[index]) {
    URL.revokeObjectURL(videoPreviews.value[index]!);
  }
  videoPreviews.value[index] = null;
};

const submitReview = () => {
  form.post(shop.orders.review.store.url(props.order.order_number));
};
</script>

<template>
  <Head :title="`Rate Order #${order.order_number}`" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="mx-auto w-full max-w-7xl flex-grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <div class="flex flex-col gap-8 lg:flex-row">
        <UserAccountSidebar :name="user.name" :avatar="user.avatar" />

        <div class="min-w-0 flex-1">
          <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <Link
              :href="shop.orders.index.url()"
              class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
            >
              <ArrowLeftIcon class="h-4 w-4" /> Back to Purchases
            </Link>
            <div class="text-sm text-zinc-500">
              Order No:
              <span class="font-bold text-zinc-800 dark:text-white"
                >#{{ order.order_number }}</span
              >
            </div>
          </div>

          <form @submit.prevent="submitReview" class="space-y-6">
            <div
              class="flex items-center gap-2 rounded-2xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900"
            >
              <StoreIcon class="h-5 w-5 text-zinc-400" />
              <span class="text-sm font-black text-zinc-800 dark:text-white"
                >Reviewing Store:
                <span class="text-indigo-500"> {{ order.store_name }}</span>
              </span>
            </div>

            <div
              v-for="(item, index) in order.items"
              :key="item.id"
              class="space-y-6 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
            >
              <div
                class="flex gap-4 border-b border-zinc-100 pb-4 dark:border-zinc-800"
              >
                <a
                  v-if="item.product_image"
                  :href="item.product_image"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <img
                    :src="item.product_image"
                    :alt="item.product_name"
                    class="h-16 w-16 shrink-0 cursor-zoom-in rounded-xl border border-zinc-200 object-cover dark:border-zinc-700"
                  />
                </a>
                <div
                  v-else
                  class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"
                >
                  <PackageIcon class="h-5 w-5 text-zinc-400" />
                </div>

                <div class="min-w-0 flex-1">
                  <h4
                    class="line-clamp-1 font-bold text-zinc-900 dark:text-white"
                  >
                    {{ item.product_name }}
                  </h4>
                  <p
                    v-if="item.variant_name"
                    class="mt-0.5 text-xs text-zinc-400"
                  >
                    Variation: {{ item.variant_name }}
                  </p>
                </div>
              </div>

              <div
                class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-6"
              >
                <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300"
                  >Product Quality:</span
                >
                <div class="flex items-center gap-1.5">
                  <button
                    v-for="star in 5"
                    :key="star"
                    type="button"
                    @click="setRating(index, star)"
                    @mouseenter="setHoverRating(index, star)"
                    @mouseleave="clearHoverRating(index)"
                    class="cursor-pointer p-0.5 transition-transform hover:scale-110 focus:outline-none"
                  >
                    <StarIcon
                      class="h-7 w-7 transition-colors"
                      :class="[
                        star <=
                        (hoverRatings[index] ?? form.items[index].rating)
                          ? 'fill-amber-400 text-amber-400'
                          : 'text-zinc-300 dark:text-zinc-700',
                      ]"
                    />
                  </button>
                  <span class="ml-2 text-xs font-bold text-amber-500 uppercase">
                    {{
                      ['Terrible', 'Poor', 'Fair', 'Good', 'Excellent'][
                        (hoverRatings[index] ?? form.items[index].rating) - 1
                      ]
                    }}
                  </span>
                </div>
              </div>

              <div class="space-y-2">
                <label
                  class="text-sm font-bold text-zinc-700 dark:text-zinc-300"
                  >Share your review</label
                >
                <textarea
                  v-model="form.items[index].comment"
                  rows="4"
                  placeholder="Tell others about the product quality, shipping speed, packaging details..."
                  class="w-full rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 text-sm text-zinc-900 shadow-sm transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                ></textarea>

                <div class="grid grid-cols-[2fr_1fr] gap-8 border-t pt-6">
                  <!-- Images -->
                  <div>
                    <div class="mb-2 flex items-center justify-between">
                      <span
                        class="text-sm font-bold text-zinc-700 dark:text-zinc-300"
                      >
                        Review Images
                      </span>

                      <span class="text-sm text-zinc-500">
                        {{ form.items[index].images.length }}/{{ MAX_IMAGES }}
                      </span>
                    </div>

                    <div class="flex flex-wrap gap-3">
                      <!-- previews -->
                      <div
                        v-for="(src, imgIndex) in imagePreviews[index] ?? []"
                        :key="src"
                        class="group relative h-28 w-28 rounded border border-zinc-200 dark:border-zinc-700"
                      >
                        <img
                          :src="src"
                          class="h-full w-full rounded object-cover"
                        />
                        <button
                          type="button"
                          class="absolute -top-1.5 -right-1.5 hidden cursor-pointer rounded-full bg-red-500 p-0.5 text-white group-hover:flex"
                          @click="removeImage(index, imgIndex)"
                        >
                          <XIcon class="h-4 w-4" />
                        </button>
                      </div>

                      <!-- upload -->
                      <label
                        :class="[
                          'group flex h-28 w-28 flex-col items-center justify-center rounded border-2 border-dashed transition',
                          canAddImages(index).value
                            ? 'cursor-pointer border-zinc-300 bg-zinc-50 text-zinc-500 hover:border-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400'
                            : 'cursor-not-allowed border-zinc-500 opacity-50',
                        ]"
                      >
                        <CameraIcon class="mb-2 h-6 w-6" />
                        <span class="text-xs font-medium">
                          {{
                            canAddImages(index).value
                              ? 'Add Photo'
                              : 'Limit Reached'
                          }}
                        </span>
                        <input
                          type="file"
                          multiple
                          accept="image/*"
                          class="hidden"
                          :disabled="!canAddImages(index).value"
                          @change="handleImages(index, $event)"
                        />
                      </label>
                    </div>
                  </div>

                  <!-- Video -->
                  <div>
                    <div class="mb-2 flex items-center justify-between">
                      <span
                        class="text-sm font-bold text-zinc-700 dark:text-zinc-300"
                      >
                        Review Video
                      </span>
                      <span class="text-sm text-zinc-500">
                        {{ hasVideo(index).value ? 1 : 0 }}/{{ MAX_VIDEO }}
                      </span>
                    </div>

                    <div class="flex flex-wrap gap-3">
                      <div
                        v-if="videoPreviews[index]"
                        class="group relative h-36 w-60 rounded border border-zinc-200 dark:border-zinc-700"
                      >
                        <video
                          :src="videoPreviews[index]!"
                          controls
                          class="h-full w-full rounded object-cover"
                        />
                        <button
                          type="button"
                          class="absolute -top-1.5 -right-1.5 cursor-pointer rounded-full bg-red-500 p-1 text-white"
                          @click="removeVideo(index)"
                        >
                          <XIcon class="h-4 w-4" />
                        </button>
                      </div>

                      <label
                        v-else
                        class="group flex h-28 w-52 cursor-pointer flex-col items-center justify-center rounded border-2 border-dashed border-zinc-300 bg-zinc-50 text-zinc-500 hover:border-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400"
                      >
                        <VideoIcon class="mb-2 h-6 w-6" />
                        <span class="text-xs font-medium"> Add Video </span>
                        <input
                          type="file"
                          accept="video/*"
                          class="hidden"
                          @change="handleVideo(index, $event)"
                        />
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-3">
              <Link
                :href="shop.orders.index.url()"
                class="cursor-pointer rounded-xl border border-zinc-200 bg-white px-6 py-3 text-sm font-bold text-zinc-600 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="cursor-pointer rounded-xl bg-[#009933] px-8 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-[#007722] disabled:opacity-50"
              >
                {{ form.processing ? 'Submitting...' : 'Submit Review' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>
