<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, PackageIcon } from 'lucide-vue-next';
import ProductInfoSection from '@/components/products/ProductInfoSection.vue';
import ProductMediaSection from '@/components/products/ProductMediaSection.vue';
import ProductVariantsSection from '@/components/products/ProductVariantsSection.vue';
import seller from '@/routes/seller';
import type { ProductForm, FormAttribute, Category } from '@/types';

defineProps<{
  categories: Category[];
  attributes: FormAttribute[];
}>();

const form = useForm<ProductForm>({
  name: '',
  description: '',
  category_ids: [],
  is_featured: false,
  images: [],
  video: null,
  variants: [],
});

const submit = () => {
  form.post(seller.products.store.url());
};
</script>

<template>
  <Head title="Create Product" />
  <div class="min-h-screen py-10 transition-colors duration-300">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <div
        class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"
      >
        <div>
          <h1
            class="flex items-center gap-2 text-3xl font-black text-zinc-900 dark:text-white"
          >
            <PackageIcon class="h-8 w-8 text-[#009933]" /> Add New Product
          </h1>
          <p class="mt-1 font-medium text-zinc-500 dark:text-zinc-400">
            Fill in the details to list your item.
          </p>
        </div>
        <Link
          :href="seller.dashboard.url()"
          class="inline-flex items-center text-sm font-bold text-zinc-500 transition-colors hover:text-[#009933] dark:text-zinc-400"
        >
          <ArrowLeftIcon class="mr-1 h-4 w-4" /> Back to Dashboard
        </Link>
      </div>

      <div
        class="overflow-hidden rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
      >
        <form class="space-y-6" @submit.prevent="submit">
          <ProductInfoSection
            v-model:name="form.name"
            v-model:description="form.description"
            v-model:category-ids="form.category_ids"
            v-model:is-featured="form.is_featured"
            :categories="categories"
            :errors="form.errors"
          />

          <ProductMediaSection
            v-model:images="form.images"
            v-model:video="form.video"
            :errors="form.errors"
          />

          <ProductVariantsSection
            v-model="form.variants"
            :attributes="attributes"
            :errors="form.errors"
          />

          <button
            type="submit"
            class="rounded-xl bg-black px-6 py-3 text-white"
            :disabled="form.processing"
          >
            Create Product
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
