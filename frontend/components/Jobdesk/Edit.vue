<template>
  <!-- Customer Information (if available) -->
  <div
    v-if="draftDatas.order && draftDatas.order.customer?.name"
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6"
  >
    <div class="flex items-center gap-3 mb-4">
      <div
        class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center"
      >
        <Icon
          name="lucide:users"
          class="text-blue-600 dark:text-blue-400"
          size="0.9em"
        />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
        Informasi Konsumen
      </h3>
      <Button
        v-if="!draftDatas.id"
        @click="resetOrder"
        class="ml-auto flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 text-sm"
      >
        <Icon name="lucide:x" size="0.8em" />
        <span>Reset</span>
      </Button>
    </div>

    <div
      class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border border-slate-200 dark:border-zinc-600"
    >
      <div class="flex items-center gap-3">
        <Icon
          name="lucide:user-circle"
          class="text-slate-500 dark:text-slate-400"
          size="1em"
        />
        <div>
          <div class="text-sm text-slate-500 dark:text-slate-400">Nama</div>
          <div class="font-semibold text-slate-800 dark:text-white">
            {{ draftDatas.order.customer.name }}
          </div>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <Icon
          name="lucide:map-pin"
          class="text-slate-500 dark:text-slate-400"
          size="1em"
        />
        <div>
          <div class="text-sm text-slate-500 dark:text-slate-400">Alamat</div>
          <div class="font-semibold text-slate-800 dark:text-white">
            {{ draftDatas.order.customer.address }}
          </div>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <Icon
          name="lucide:phone"
          class="text-slate-500 dark:text-slate-400"
          size="1em"
        />
        <div>
          <div class="text-sm text-slate-500 dark:text-slate-400">Telepon</div>
          <div class="font-semibold text-slate-800 dark:text-white">
            {{ draftDatas.order.customer.phone }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Section -->
  <div
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6"
  >
    <div class="flex items-center gap-3 mb-6">
      <div
        class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center"
      >
        <Icon
          name="lucide:edit"
          class="text-purple-600 dark:text-purple-400"
          size="0.9em"
        />
      </div>
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
          {{ draftDatas.id ? "Edit Jobdesk" : "Tambah Jobdesk Baru" }}
        </h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Lengkapi informasi jobdesk di bawah ini
        </p>
      </div>
    </div>

    <Form
      v-slot="$form"
      ref="form"
      :resolver="resolver"
      :validateOnValueUpdate="true"
      :validateOnBlur="true"
      @submit="handleSubmit"
      class="space-y-6"
    >
      <!-- Order Selection -->
      <div class="space-y-2">
        <label
          for="order_id"
          class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2"
        >
          <Icon name="lucide:package" size="0.9em" />
          Data Order
        </label>
        <Select
          id="order_id"
          name="order_id"
          v-model="draftDatas.order_id"
          :options="listOrder"
          filter
          optionLabel="label"
          optionValue="id"
          class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !bg-white dark:!bg-zinc-800"
          :disabled="draftDatas.id ? true : false"
          placeholder="Pilih order..."
          showClear
        />
        <Message
          v-if="$form.order_id?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.order_id.error.message }}</Message
        >
      </div>

      <!-- Employee Selection -->
      <div class="space-y-2">
        <label
          for="user_id"
          class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2"
        >
          <Icon name="lucide:user" size="0.9em" />
          Pilih Karyawan (Penanggung Jawab)
        </label>
        <Select
          id="user_id"
          name="user_id"
          v-model="draftDatas.user_id"
          :options="listKaryawan"
          filter
          optionLabel="name"
          optionValue="id"
          class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !bg-white dark:!bg-zinc-800"
          placeholder="Pilih karyawan..."
        />
        <Message
          v-if="$form.user_id?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.user_id.error.message }}</Message
        >
      </div>

      <!-- Job Description -->
      <div class="space-y-2">
        <label
          for="description"
          class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2"
        >
          <Icon name="lucide:file-text" size="0.9em" />
          Deskripsi Pekerjaan
        </label>
        <Textarea
          id="description"
          name="description"
          v-model="draftDatas.description"
          class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !bg-white dark:!bg-zinc-800 min-h-[120px]"
          placeholder="Masukkan deskripsi pekerjaan..."
        />
        <Message
          v-if="$form.description?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.description.error.message }}</Message
        >
      </div>

      <!-- Date Inputs Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Start Date -->
        <div class="space-y-2">
          <label
            for="tanggal_pengerjaan"
            class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2"
          >
            <Icon name="lucide:calendar-plus" size="0.9em" />
            Tanggal Pengerjaan
          </label>
          <DatePicker
            id="tanggal_pengerjaan"
            name="tanggal_pengerjaan"
            v-model="draftDatas.tanggal_pengerjaan"
            class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600"
            dateFormat="d MM yy"
            placeholder="Pilih tanggal mulai..."
          />
        </div>

        <!-- End Date -->
        <div class="space-y-2">
          <label
            for="tanggal_selesai"
            class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2"
          >
            <Icon name="lucide:calendar-check" size="0.9em" />
            Tanggal Selesai
          </label>
          <DatePicker
            id="tanggal_selesai"
            name="tanggal_selesai"
            v-model="draftDatas.tanggal_selesai"
            class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600"
            dateFormat="d MM yy"
            placeholder="Pilih tanggal selesai..."
          />
        </div>
      </div>

      <!-- Status Selection -->
      <div class="space-y-2">
        <label
          for="status"
          class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2"
        >
          <Icon name="lucide:flag" size="0.9em" />
          Status Pekerjaan
        </label>
        <Select
          id="status"
          v-model="draftDatas.status"
          :options="liststatus"
          optionLabel="name"
          optionValue="name"
          class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !bg-white dark:!bg-zinc-800"
          placeholder="Pilih status..."
        />
      </div>

      <!-- Action Buttons -->
      <div
        class="flex flex-wrap gap-3 justify-end pt-4 border-t border-slate-200 dark:border-zinc-700"
      >
        <Button
          v-if="isLoading"
          :disabled="true"
          class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-xl shadow-lg font-medium"
        >
          <Icon name="lucide:loader-2" class="animate-spin" size="1em" />
          <span>Memproses...</span>
        </Button>

        <Button
          v-else
          type="submit"
          class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:save" size="1em" />
          <span>{{
            !draftDatas.id ? "Tambah Jobdesk" : "Update Jobdesk"
          }}</span>
        </Button>

        <Button
          v-if="draftDatas.id"
          @click="emits('goToDetail', draftDatas)"
          class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:eye" size="1em" />
          <span>Detail Jobdesk</span>
        </Button>

        <Button
          @click="emits('closeDialog')"
          class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:x" size="1em" />
          <span>Tutup</span>
        </Button>
      </div>
    </Form>
  </div>
</template>

<script setup lang="ts">
const emits = defineEmits([
  "error",
  "closeDialog",
  "goToDetail",
  "goToAdd",
  "updateData",
  "addData",
]);
const { datas } = defineProps(["datas"]);
const draftDatas = ref(JSON.parse(JSON.stringify(datas)));
const isLoading = ref(false);
const listKaryawan = ref([]);
const listOrder = ref([]);
const route = useRoute();
const client = useSanctumClient();
const orders = ref([] as any[]);

onMounted(async () => {
  draftDatas.value.order ??= {} as any;
  draftDatas.value.status ??= "Masuk";
  await fetchKaryawanData();
  await fetchOrders();

  if (typeof draftDatas.value.user_id === "string") {
    draftDatas.value.user_id = Number(draftDatas.value.user_id);
  }
  if (typeof draftDatas.value.order_id === "string") {
    draftDatas.value.order_id = Number(draftDatas.value.order_id);
  }
  if (route.query.order_id && !draftDatas.value.order_id) {
    draftDatas.value.order_id = Number(route.query.order_id);
  }
  applySelectedOrder();
});

const liststatus = ref([
  { name: "Masuk" },
  { name: "Progress" },
  { name: "Selesai" },
]);

const fetchKaryawanData = async () => {
  try {
    const res = await client("/karyawans/min?jobdesk_roles=true");
    const list = Array.isArray(res)
      ? res
      : Array.isArray((res as any)?.data)
        ? (res as any).data
        : [];
    listKaryawan.value = list.map((item: any) => ({
      id: item.id,
      name: item.name,
    }));
  } catch (error: any) {
    console.error("Error fetching karyawan:", error);
    emits(
      "error",
      error?.data?.message ||
        error?.response?._data?.message ||
        error?.message ||
        "Gagal memuat list karyawan",
    );
  }
};

const fetchOrders = async () => {
  try {
    const response = await client("/orders?paginate=false");
    orders.value = Array.isArray(response) ? response : [];
    listOrder.value = orders.value.map((item: any) => ({
      id: item.id,
      label: `${item.no_order || `#${item.id}`} - ${item.customer?.name || "-"} - ${item.product?.name || "-"}`,
    }));
  } catch (error) {
    console.error("Error loading orders:", error);
  }
};

const resetOrder = () => {
  draftDatas.value.order_id = null;
  draftDatas.value.order = {} as any;
  emits("goToAdd");
};

const getApiErrorMessage = (err: any) => {
  const data =
    err?.data ??
    err?.response?._data ??
    err?.response?.data ??
    err?.cause?.data;

  if (typeof data === "string" && data.trim()) return data.trim();

  if (data && typeof data === "object") {
    const errors = (data as any).errors;
    if (errors && typeof errors === "object") {
      const first = Object.values(errors)
        .flat()
        .find((v: any) => typeof v === "string" && v.trim());
      if (first) return String(first).trim();
    }

    if (typeof (data as any).error === "string" && (data as any).error.trim()) {
      return (data as any).error.trim();
    }
    if (
      typeof (data as any).message === "string" &&
      (data as any).message.trim()
    ) {
      return (data as any).message.trim();
    }
  }

  if (typeof err?.statusMessage === "string" && err.statusMessage.trim())
    return err.statusMessage.trim();
  if (typeof err?.message === "string" && err.message.trim())
    return err.message.trim();
  return null;
};

const handleSubmit = async ({ valid }: { valid: boolean }) => {
  if (!valid) return;
  isLoading.value = true;

  // Format dates to YYYY-MM-DD
  draftDatas.value.tanggal_pengerjaan = draftDatas.value.tanggal_pengerjaan
    ? formatToYYYYMMDD(draftDatas.value.tanggal_pengerjaan)
    : null;
  draftDatas.value.tanggal_selesai = draftDatas.value.tanggal_selesai
    ? formatToYYYYMMDD(draftDatas.value.tanggal_selesai)
    : null;

  try {
    if (draftDatas.value.id) {
      const responseUpdate = await client(
        `/jobdesks/${draftDatas.value.id}`,
        { method: "PUT", body: draftDatas.value },
      );
      emits("updateData", responseUpdate);
    } else {
      const response = await client("/jobdesks", {
        method: "POST",
        body: draftDatas.value,
      });
      emits("addData", response);
    }
  } catch (error: any) {
    const status = error?.status ?? error?.response?.status;
    const fallback = draftDatas.value.id
      ? "Update jobdesk gagal! Periksa kembali data anda."
      : "Tambah jobdesk gagal! Periksa kembali data anda.";
    const detail = getApiErrorMessage(error);
    const message =
      status === 401
        ? "Sesi berakhir. Silakan login ulang."
        : detail || fallback;
    emits("error", message);
  } finally {
    isLoading.value = false;
  }
};

const formatToYYYYMMDD = (date: any) => {
  if (!date) return "";
  const d = new Date(date);
  if (isNaN(d.getTime())) return "";
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
};

const resolver = () => {
  const errors = {} as any;
  if (!draftDatas.value.description) {
    errors.description = { message: "Layanan harus diisi!" };
  }
  if (!draftDatas.value.order_id) {
    errors.order_id = { message: "Order harus dipilih!" };
  }
  if (!draftDatas.value.user_id) {
    errors.user_id = { message: "Karyawan harus diisi!" };
  }
  if (!draftDatas.value.status) {
    errors.status = { message: "Status harus diisi!" };
  }
  return { errors };
};

const applySelectedOrder = () => {
  if (!draftDatas.value.order_id) {
    draftDatas.value.order = {} as any;
    return;
  }
  const selected = orders.value.find(
    (o: any) => Number(o.id) === Number(draftDatas.value.order_id),
  );
  if (selected) {
    draftDatas.value.order = selected;
  }
};

watch(
  () => draftDatas.value.order_id,
  () => {
    applySelectedOrder();
  },
);

// Watchers for updating status based on dates
watch(
  () => draftDatas.value.tanggal_pengerjaan,
  (newVal) => {
    if (newVal) {
      if (!draftDatas.value.tanggal_selesai) {
        draftDatas.value.status = "Progress";
      }
    } else if (!draftDatas.value.tanggal_selesai) {
      draftDatas.value.status = "Masuk";
    }
  },
);

watch(
  () => draftDatas.value.tanggal_selesai,
  (newVal) => {
    if (newVal) {
      draftDatas.value.status = "Selesai";
    } else if (!newVal && draftDatas.value.tanggal_pengerjaan) {
      draftDatas.value.status = "Progress";
    }
  },
);
</script>
