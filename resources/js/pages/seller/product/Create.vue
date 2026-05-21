<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import ProductMediaSection from '@/components/products/ProductMediaSection.vue';
import ProductInfoSection from '@/components/products/ProductInfoSection.vue';
import VariantOptionsBuilder from '@/components/products/VariantOptionsBuilder.vue';
import ProductVariantsSection from '@/components/products/ProductVariantsSection.vue';
import type { ProductForm, FormAttribute, Category } from '@/types';
import { ArrowLeftIcon, PackageIcon } from 'lucide-vue-next';
import seller from '@/routes/seller';

const props = defineProps<{
  categories: Category[];
  attributes: FormAttribute[];
}>();

const form = useForm<ProductForm>({
  name: '',
  description: '',

  category_ids: [],

  is_active: true,
  is_featured: false,

  images: [],
  video: null,

  variant_options: [],

  variants: [],
});

const cartesian = <T,>(arrays: T[][]): T[][] => {
  return arrays.reduce<T[][]>(
    (acc, curr) => acc.flatMap((x) => curr.map((y) => [...x, y])),
    [[]],
  );
};

watch(
  () => form.variant_options,
  (options) => {
    const validOptions = options.filter(
      (o) => o.attribute_id && o.values.length,
    );

    if (!validOptions.length) {
      form.variants = [];
      return;
    }

    const grouped = validOptions.map((option) => {
      const attribute = props.attributes.find(
        (a) => a.id === option.attribute_id,
      );

      return option.values.map((value) => ({
        attribute_id: option.attribute_id!,
        attribute_name: attribute?.name ?? '',
        value_id: value.id,
        value: value.value,
        is_new: value.is_new,
      }));
    });

    const combinations = cartesian(grouped);

    form.variants = combinations.map((attributes) => ({
      sku: '',

      price: undefined,

      compare_price: undefined,

      stock: 0,

      image: null,

      attributes,
    }));
  },
  {
    deep: true,
  },
);
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
        class="space-y-4 overflow-hidden rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
      >
        <ProductInfoSection
          v-model:name="form.name"
          v-model:description="form.description"
          v-model:category-ids="form.category_ids"
          v-model:is-active="form.is_active"
          v-model:is-featured="form.is_featured"
          :categories="categories"
          :errors="form.errors"
        />

        <ProductMediaSection
          v-model:images="form.images"
          v-model:video="form.video"
          :errors="form.errors"
        />

        <VariantOptionsBuilder
          v-model="form.variant_options"
          :attributes="attributes"
        />

        <ProductVariantsSection v-model="form.variants" />
      </div>
    </div>
  </div>
</template>
