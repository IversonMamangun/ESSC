<script setup lang="ts">
import { Input } from '@/components/ui/input';
import type { ProductVariantForm } from '@/types';

const model = defineModel<ProductVariantForm[]>({
  required: true,
});
</script>

<template>
  <div class="space-y-6 rounded-2xl border p-6">
    <h2 class="text-lg font-semibold">Generated Variants</h2>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b">
            <th class="p-3 text-left">Variant</th>
            <th class="p-3 text-left">SKU</th>
            <th class="p-3 text-left">Price</th>
            <th class="p-3 text-left">Compare Price</th>
            <th class="p-3 text-left">Stock</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(variant, index) in model" :key="index" class="border-b">
            <td class="p-3">
              <div class="flex flex-wrap gap-2">
                <div
                  v-for="attribute in variant.attributes"
                  :key="attribute.attribute_name + attribute.value"
                  class="rounded-full bg-muted px-2 py-1 text-xs"
                >
                  {{ attribute.attribute_name }}:
                  {{ attribute.value }}
                </div>
              </div>
            </td>

            <td class="p-3">
              <Input v-model="variant.sku" />
            </td>

            <td class="p-3">
              <Input v-model="variant.price" type="number" />
            </td>

            <td class="p-3">
              <Input v-model="variant.compare_price" type="number" />
            </td>

            <td class="p-3">
              <Input v-model="variant.stock" type="number" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
