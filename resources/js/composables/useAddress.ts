import { computed, nextTick, onMounted, ref, watch } from 'vue';

interface PsgcApiItem {
  code: string;
  name: string;
}

export interface AddressInitValues {
  region: string;
  province: string;
  city: string;
  barangay: string;
}

export function useAddress() {
  const regions = ref<PsgcApiItem[]>([]);
  const provinces = ref<PsgcApiItem[]>([]);
  const cities = ref<PsgcApiItem[]>([]);
  const barangays = ref<PsgcApiItem[]>([]);

  const selectedRegion = ref('');
  const selectedProvince = ref('');
  const selectedCity = ref('');
  const selectedBarangay = ref('');

  const isLoadingRegions = ref(false);
  const isLoadingProvinces = ref(false);
  const isLoadingCities = ref(false);
  const isLoadingBarangays = ref(false);

  // Suppresses watcher cascades while initialising edit values
  const isInitializing = ref(false);

  const isNcr = computed(() => {
    const region = regions.value.find((r) => r.name === selectedRegion.value);

    return region
      ? region.code === '130000000' || region.name.includes('NCR')
      : false;
  });

  // ─── API helpers ──────────────────────────────────────────────────────────

  async function fetchRegions() {
    isLoadingRegions.value = true;

    try {
      const res = await fetch('https://psgc.gitlab.io/api/regions/');
      regions.value = await res.json();
    } catch (e) {
      console.error('Failed to fetch regions:', e);
    } finally {
      isLoadingRegions.value = false;
    }
  }

  async function fetchProvinces(regionCode: string) {
    isLoadingProvinces.value = true;

    try {
      const res = await fetch(
        `https://psgc.gitlab.io/api/regions/${regionCode}/provinces/`,
      );
      provinces.value = await res.json();
    } catch (e) {
      console.error('Failed to fetch provinces:', e);
    } finally {
      isLoadingProvinces.value = false;
    }
  }

  async function fetchCities(code: string, forNcr = false) {
    isLoadingCities.value = true;
    const url = forNcr
      ? `https://psgc.gitlab.io/api/regions/${code}/cities-municipalities/`
      : `https://psgc.gitlab.io/api/provinces/${code}/cities-municipalities/`;

    try {
      const res = await fetch(url);
      cities.value = await res.json();
    } catch (e) {
      console.error('Failed to fetch cities:', e);
    } finally {
      isLoadingCities.value = false;
    }
  }

  async function fetchBarangays(cityCode: string) {
    isLoadingBarangays.value = true;

    try {
      const res = await fetch(
        `https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`,
      );
      barangays.value = await res.json();
    } catch (e) {
      console.error('Failed to fetch barangays:', e);
    } finally {
      isLoadingBarangays.value = false;
    }
  }

  // ─── Watchers (normal user interaction) ───────────────────────────────────

  watch(selectedRegion, async (name) => {
    if (isInitializing.value) {
return;
}

    selectedProvince.value = '';
    selectedCity.value = '';
    selectedBarangay.value = '';
    provinces.value = [];
    cities.value = [];
    barangays.value = [];

    if (!name) {
return;
}

    const region = regions.value.find((r) => r.name === name);

    if (!region) {
return;
}

    if (isNcr.value) {
      await fetchCities(region.code, true);
    } else {
      await fetchProvinces(region.code);
    }
  });

  watch(selectedProvince, async (name) => {
    if (isInitializing.value || isNcr.value || !name) {
return;
}

    selectedCity.value = '';
    selectedBarangay.value = '';
    cities.value = [];
    barangays.value = [];

    const province = provinces.value.find((p) => p.name === name);

    if (province) {
await fetchCities(province.code, false);
}
  });

  watch(selectedCity, async (name) => {
    if (isInitializing.value || !name) {
return;
}

    selectedBarangay.value = '';
    barangays.value = [];

    const city = cities.value.find((c) => c.name === name);

    if (city) {
await fetchBarangays(city.code);
}
  });

  // ─── Edit initialisation ──────────────────────────────────────────────────

  /**
   * Pre-loads all cascading dropdowns for an existing address without
   * triggering the watchers' downstream-clear side-effects.
   */
  async function initializeForEdit(address: AddressInitValues) {
    isInitializing.value = true;

    try {
      if (regions.value.length === 0) {
await fetchRegions();
}

      const region = regions.value.find((r) => r.name === address.region);

      if (!region) {
return;
}

      const ncr = region.code === '130000000' || region.name.includes('NCR');

      if (ncr) {
        await fetchCities(region.code, true);
      } else {
        await fetchProvinces(region.code);
        const province = provinces.value.find(
          (p) => p.name === address.province,
        );

        if (province) {
await fetchCities(province.code, false);
}
      }

      const city = cities.value.find((c) => c.name === address.city);

      if (city) {
await fetchBarangays(city.code);
}

      // Set values after all data is loaded — watchers are suppressed
      selectedRegion.value = address.region;
      selectedProvince.value = address.province;
      selectedCity.value = address.city;
      selectedBarangay.value = address.barangay;

      // Vue queues watchers as microtasks — they do not run synchronously when
      // a ref changes. Without this await, finally would flip isInitializing
      // to false BEFORE the queued watchers execute, causing them to run
      // normally and cascade-clear the selections set.
      // nextTick() lets all pending watchers flush while the lock is still
      // held, so every watcher hits: if (isInitializing.value) return
      await nextTick();
    } finally {
      isInitializing.value = false;
    }
  }

  // ─── Lifecycle & utils ────────────────────────────────────────────────────

  onMounted(fetchRegions);

  function reset() {
    selectedRegion.value = '';
    selectedProvince.value = '';
    selectedCity.value = '';
    selectedBarangay.value = '';
    provinces.value = [];
    cities.value = [];
    barangays.value = [];
  }

  return {
    regions,
    provinces,
    cities,
    barangays,
    selectedRegion,
    selectedProvince,
    selectedCity,
    selectedBarangay,
    isNcr,
    isLoadingRegions,
    isLoadingProvinces,
    isLoadingCities,
    isLoadingBarangays,
    initializeForEdit,
    reset,
  };
}
