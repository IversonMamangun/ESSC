<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { FormAttribute, ProductVariantOption } from '@/types';

defineProps<{
  attributes: FormAttribute[];
}>();

const model = defineModel<ProductVariantOption[]>({
  required: true,
});

const addOption = () => {
  model.value.push({
    attribute_id: null,
    values: [],
  });
};

const addValue = (option: ProductVariantOption, value: string) => {
  if (!value.trim()) return;

  option.values.push({
    value,
    is_new: true,
  });
};
</script>

<template>
  <div class="space-y-6 rounded-2xl border p-6">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold">Variant Options</h2>

      <Button type="button" @click="addOption"> Add Option </Button>
    </div>

    <div
      v-for="(option, index) in model"
      :key="index"
      class="space-y-4 rounded-xl border p-4"
    >
      <select
        v-model="option.attribute_id"
        class="w-full rounded-md border bg-background"
      >
        <option :value="null">Select attribute</option>

        <option
          v-for="attribute in attributes"
          :key="attribute.id"
          :value="attribute.id"
        >
          {{ attribute.name }}
        </option>
      </select>

      <div v-if="option.attribute_id" class="flex flex-wrap gap-2">
        <button
          v-for="value in attributes.find((a) => a.id === option.attribute_id)
            ?.values ?? []"
          :key="value.id"
          type="button"
          class="rounded-full border px-3 py-1 text-sm"
          @click="option.values.push(value)"
        >
          {{ value.value }}
        </button>
      </div>

      <Input
        placeholder="Create new value..."
        @keydown.enter.prevent="
          addValue(option, ($event.target as HTMLInputElement).value);

          ($event.target as HTMLInputElement).value = '';
        "
      />

      <div class="flex flex-wrap gap-2">
        <div
          v-for="value in option.values"
          :key="value.value"
          class="rounded-full bg-muted px-3 py-1 text-sm"
        >
          {{ value.value }}
        </div>
      </div>
    </div>
  </div>
</template>
