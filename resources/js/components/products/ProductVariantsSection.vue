<script setup lang="ts">
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { PlusIcon, Trash2Icon, CopyIcon } from 'lucide-vue-next';
import type {
  ProductVariantForm,
  ProductVariantAttributeForm,
  FormAttribute,
} from '@/types';

const props = defineProps<{
  attributes: FormAttribute[];
}>();

const model = defineModel<ProductVariantForm[]>({
  required: true,
});

const newValueInputs = ref<Record<string, string>>({});

const createVariant = (): ProductVariantForm => ({
  sku: '',
  price: undefined,
  compare_price: undefined,
  stock: 0,
  image: null,
  weight: undefined,
  is_default: false,
  attributes: [],
});

const addVariant = () => {
  model.value.push(createVariant());
};

const removeVariant = (index: number) => {
  model.value.splice(index, 1);
};

const duplicateVariant = (variant: ProductVariantForm) => {
  model.value.push({
    sku: '',
    price: variant.price,
    compare_price: variant.compare_price,
    stock: variant.stock,
    image: null,
    weight: variant.weight,
    is_default: false,
    attributes: variant.attributes.map((a) => ({
      ...a,
    })),
  });
};

const addAttribute = (variant: ProductVariantForm) => {
  variant.attributes.push({
    attribute_id: null,
    value_id: null,
    value: '',
  });
};

const removeAttribute = (variant: ProductVariantForm, index: number) => {
  variant.attributes.splice(index, 1);
};

const getAttribute = (attributeId: number | null) => {
  return props.attributes.find((a) => a.id === attributeId);
};

const syncSelectedValue = (attribute: ProductVariantAttributeForm) => {
  const selectedAttribute = getAttribute(attribute.attribute_id);

  const selectedValue = selectedAttribute?.values.find(
    (v) => v.id === attribute.value_id,
  );
  if (!selectedValue) return;

  attribute.value = selectedValue.value;
  attribute.is_new = false;
};

const addCustomValue = (
  variantIndex: number,
  attributeIndex: number,
  attribute: ProductVariantAttributeForm,
) => {
  const key = `${variantIndex}-${attributeIndex}`;
  const value = newValueInputs.value[key];
  if (!value?.trim()) return;

  attribute.value_id = null;
  attribute.value = value.trim();
  attribute.is_new = true;

  newValueInputs.value[key] = '';
};

const setDefaultVariant = (selectedIndex: number) => {
  model.value.forEach((variant, index) => {
    variant.is_default = index === selectedIndex;
  });
};

const variantLabel = computed(() => {
  return (variant: ProductVariantForm) => {
    return variant.attributes
      .map((a) => a.value)
      .filter(Boolean)
      .join(' / ');
  };
});

const imagePreviews = ref<Record<number, string>>({});

const handleVariantImage = (event: Event, variantIndex: number) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file) return;

  model.value[variantIndex].image = file;
  imagePreviews.value[variantIndex] = URL.createObjectURL(file);
};
</script>

<template>
  <div class="space-y-6 rounded-2xl border p-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold">Variants</h2>

        <p class="text-sm text-muted-foreground">
          Create and manage product variants.
        </p>
      </div>

      <Button type="button" @click="addVariant">
        <PlusIcon class="mr-2 h-4 w-4" />

        Add Variant
      </Button>
    </div>

    <div
      v-if="!model.length"
      class="rounded-xl border border-dashed p-10 text-center"
    >
      <p class="text-sm text-muted-foreground">No variants added yet.</p>
    </div>

    <div
      v-for="(variant, variantIndex) in model"
      :key="variantIndex"
      class="space-y-6 rounded-2xl border p-6"
    >
      <div class="flex items-start justify-between">
        <div>
          <h3 class="font-semibold">
            {{ variantLabel(variant) || `Variant #${variantIndex + 1}` }}
          </h3>

          <p class="text-sm text-muted-foreground">
            Configure variant attributes, inventory, and pricing.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <Button
            type="button"
            variant="outline"
            size="sm"
            @click="duplicateVariant(variant)"
          >
            <CopyIcon class="h-4 w-4" />
          </Button>

          <Button
            type="button"
            variant="destructive"
            size="sm"
            @click="removeVariant(variantIndex)"
          >
            <Trash2Icon class="h-4 w-4" />
          </Button>
        </div>
      </div>

      <!-- attributes -->
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <Label>Attributes</Label>

          <Button
            type="button"
            variant="outline"
            size="sm"
            @click="addAttribute(variant)"
          >
            <PlusIcon class="mr-2 h-4 w-4" />

            Add Attribute
          </Button>
        </div>

        <div
          v-for="(attribute, attributeIndex) in variant.attributes"
          :key="attributeIndex"
          class="rounded-xl border p-4"
        >
          <div class="grid gap-4 md:grid-cols-2">
            <!-- attribute -->

            <div class="space-y-2">
              <Label>Attribute</Label>

              <select
                v-model="attribute.attribute_id"
                class="w-full rounded-md border bg-background px-3 py-2"
              >
                <option :value="null">Select Attribute</option>

                <option
                  v-for="attr in attributes"
                  :key="attr.id"
                  :value="attr.id"
                >
                  {{ attr.name }}
                </option>
              </select>
            </div>

            <!-- values -->

            <div class="space-y-2">
              <Label>Value</Label>

              <select
                v-model="attribute.value_id"
                class="w-full rounded-md border bg-background px-3 py-2"
                @change="syncSelectedValue(attribute)"
              >
                <option :value="null">Select Value</option>

                <option
                  v-for="value in getAttribute(attribute.attribute_id)
                    ?.values ?? []"
                  :key="value.id"
                  :value="value.id"
                >
                  {{ value.value }}
                </option>
              </select>
            </div>
          </div>

          <!-- create custom value -->
          <div class="mt-4">
            <Label> Or Create New Value </Label>

            <div class="mt-2 flex gap-2">
              <Input
                v-model="newValueInputs[`${variantIndex}-${attributeIndex}`]"
                placeholder="Enter custom value"
              />

              <Button
                type="button"
                variant="secondary"
                @click="addCustomValue(variantIndex, attributeIndex, attribute)"
              >
                Add
              </Button>
            </div>
          </div>

          <div class="mt-4 flex justify-end">
            <Button
              type="button"
              variant="ghost"
              size="sm"
              @click="removeAttribute(variant, attributeIndex)"
            >
              <Trash2Icon class="mr-2 h-4 w-4" />

              Remove Attribute
            </Button>
          </div>
        </div>
      </div>

      <!-- pricing -->
      <div class="grid gap-4 md:grid-cols-3">
        <div class="space-y-2">
          <Label>SKU</Label>

          <Input v-model="variant.sku" />
        </div>

        <div class="space-y-2">
          <Label>Price</Label>

          <Input v-model="variant.price" type="number" />
        </div>

        <div class="space-y-2">
          <Label>Compare Price</Label>

          <Input v-model="variant.compare_price" type="number" />
        </div>
      </div>

      <!-- inventory -->
      <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
          <Label>Stock</Label>

          <Input v-model="variant.stock" type="number" />
        </div>

        <div class="flex items-center gap-2 pt-8">
          <Checkbox
            :checked="variant.is_default"
            @update:checked="setDefaultVariant(variantIndex)"
          />

          <Label> Default Variant </Label>
        </div>
      </div>

      <!-- shipping -->
      <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
          <Label> Weight (kg) </Label>

          <Input
            v-model="variant.weight"
            type="number"
            step="0.01"
            placeholder="Optional"
          />
        </div>

        <div class="space-y-2">
          <Label> Variant Image </Label>

          <Input
            type="file"
            accept="image/*"
            @change="handleVariantImage($event, variantIndex)"
          />

          <img
            v-if="imagePreviews[variantIndex]"
            :src="imagePreviews[variantIndex]"
            class="h-24 w-24 rounded-xl border object-cover"
          />
        </div>
      </div>
    </div>
  </div>
</template>
