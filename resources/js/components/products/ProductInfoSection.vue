<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import type { Category } from '@/types';

const props = defineProps<{
  categories: Category[];
  errors?: Record<string, string>;
}>();

const name = defineModel<string>('name', { required: true });

const description = defineModel<string>('description', {
  required: true,
});

const categoryIds = defineModel<number[]>('categoryIds', {
  required: true,
});

const isActive = defineModel<boolean>('isActive', {
  required: true,
});

const isFeatured = defineModel<boolean>('isFeatured', {
  required: true,
});
</script>

<template>
  <div class="space-y-6 rounded-2xl border p-6">
    <div>
      <Label>Product Name</Label>

      <Input v-model="name" />
    </div>

    <div>
      <Label>Description</Label>

      <textarea
        v-model="description"
        class="min-h-32 w-full rounded-md border bg-background px-3 py-2"
      />
    </div>

    <div>
      <Label>Categories</Label>

      <select
        v-model="categoryIds"
        multiple
        class="h-40 w-full rounded-md border bg-background"
      >
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
        >
          {{ category.name }}
        </option>
      </select>
    </div>

    <div class="flex gap-6">
      <Label class="flex items-center gap-2">
        <Checkbox v-model:checked="isActive" />

        Active
      </Label>

      <Label class="flex items-center gap-2">
        <Checkbox v-model:checked="isFeatured" />

        Featured
      </Label>
    </div>
  </div>
</template>
