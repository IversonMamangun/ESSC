<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import type { Category } from '@/types';
import { computed } from 'vue';
import { CheckIcon, ChevronsUpDownIcon, XIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/components/ui/command';
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover';
import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';

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

const isFeatured = defineModel<boolean>('isFeatured', {
  required: true,
});

const flattenCategories = (
  categories: Category[] = [],
  level = 0,
): Category[] => {
  if (!Array.isArray(categories)) return [];

  return categories.flatMap((category) => [
    {
      ...category,
      name: '— '.repeat(level) + category.name,
    },

    ...flattenCategories(category.children?.data ?? [], level + 1),
  ]);
};

const flatCategories = computed(() =>
  flattenCategories(props.categories ?? []),
);
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

    <div class="space-y-2">
      <Label> Categories </Label>

      <Popover>
        <PopoverTrigger as-child>
          <Button variant="outline" class="w-full justify-between">
            <span class="truncate">
              {{
                categoryIds.length
                  ? `${categoryIds.length} categories selected`
                  : 'Select categories'
              }}
            </span>

            <ChevronsUpDownIcon class="ml-2 h-4 w-4 opacity-50" />
          </Button>
        </PopoverTrigger>

        <PopoverContent class="w-[350px] p-0" align="start">
          <Command>
            <CommandInput placeholder="Search categories..." />
            <CommandEmpty> No category found. </CommandEmpty>
            <CommandList>
              <CommandGroup>
                <CommandItem
                  v-for="category in flatCategories"
                  :key="category.id"
                  :value="category.name"
                  @select="
                    () => {
                      if (categoryIds.includes(category.id)) {
                        categoryIds = categoryIds.filter(
                          (id) => id !== category.id,
                        );
                      } else {
                        categoryIds.push(category.id);
                      }
                    }
                  "
                >
                  <CheckIcon
                    :class="
                      cn(
                        'mr-2 h-4 w-4',
                        categoryIds.includes(category.id)
                          ? 'opacity-100'
                          : 'opacity-0',
                      )
                    "
                  />
                  {{ category.name }}
                </CommandItem>
              </CommandGroup>
            </CommandList>
          </Command>
        </PopoverContent>
      </Popover>

      <!-- selected -->
      <div class="flex flex-wrap gap-2">
        <Badge
          v-for="id in categoryIds"
          :key="id"
          variant="secondary"
          class="gap-1"
        >
          {{ flatCategories.find((c) => c.id === id)?.name }}

          <button
            type="button"
            @click="categoryIds = categoryIds.filter((x) => x !== id)"
          >
            <XIcon class="h-4 w-4" />
          </button>
        </Badge>
      </div>
    </div>

    <div class="flex gap-6">
      <Label class="flex items-center gap-2">
        <Checkbox v-model:checked="isFeatured" />

        Featured
      </Label>
    </div>
  </div>
</template>
