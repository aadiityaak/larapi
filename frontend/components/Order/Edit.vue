<template>
  <!-- Header Section with Form Info -->
  <div
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6"
  >
    <div class="flex items-center gap-4">
      <div
        class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg"
      >
        <Icon
          :name="!draftDatas.id ? 'lucide:shopping-cart-plus' : 'lucide:edit-3'"
          class="text-white"
          size="1.2em"
        />
      </div>
      <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-1">
          {{ !draftDatas.id ? "Tambah Order Baru" : "Edit Data Order" }}
        </h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          {{
            !draftDatas.id
              ? "Lengkapi form untuk menambah order baru"
              : `Perbarui informasi order #${draftDatas.no_order || draftDatas.id}`
          }}
        </p>
      </div>
    </div>
  </div>

  <!-- Selected Customer Info -->
  <div
    v-if="draftDatas.customer?.name"
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6"
  >
    <div class="flex items-center gap-2 mb-4">
      <div
        class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center"
      >
        <Icon
          name="lucide:user-check"
          class="text-blue-600 dark:text-blue-400"
          size="0.9em"
        />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
        Konsumen Terpilih
      </h3>
    </div>

    <div
      class="flex items-start gap-4 p-4 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl border border-blue-200/50 dark:border-blue-800/50"
    >
      <div
        class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg flex-shrink-0"
      >
        <span class="text-white font-bold text-lg">{{
          draftDatas.customer?.name?.charAt(0).toUpperCase()
        }}</span>
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="font-semibold text-slate-800 dark:text-white text-lg mb-1">
          {{ draftDatas.customer?.name }}
        </h4>
        <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
          <div class="flex items-center gap-2">
            <Icon name="lucide:map-pin" size="0.8em" />
            <span class="truncate">{{ draftDatas.customer?.address }}</span>
          </div>
          <div class="flex items-center gap-2">
            <Icon name="lucide:phone" size="0.8em" />
            <span>{{ draftDatas.customer?.phone }}</span>
          </div>
        </div>
      </div>
      <Button
        @click="resetKonsumen"
        class="px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm font-medium flex-shrink-0"
      >
        <Icon name="lucide:x" size="0.9em" />
        <span>Reset</span>
      </Button>
    </div>
  </div>

  <!-- Form Section -->
  <div
    v-if="draftDatas.customer?.name"
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6"
  >
    <Form
      v-slot="$form"
      ref="form"
      id="order-form"
      :resolver
      :validateOnValueUpdate="true"
      :validateOnBlur="true"
      @submit="handleSubmit"
      class="space-y-6"
    >
      <!-- Order Information Section -->
      <div class="space-y-4">
        <div class="flex items-center gap-2 mb-4">
          <div
            class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 flex items-center justify-center"
          >
            <Icon
              name="lucide:calendar"
              class="text-green-600 dark:text-green-400"
              size="0.9em"
            />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
            Informasi Order
          </h3>
        </div>

        <!-- Order Date Field -->
        <div class="space-y-2">
          <label
            for="order_date"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon
              name="lucide:calendar-days"
              class="inline mr-2"
              size="0.9em"
            />
            Tanggal Order *
          </label>
          <DatePicker
            id="order_date"
            name="order_date"
            v-model="draftDatas.order_date"
            dateFormat="dd/mm/yy"
            placeholder="Pilih tanggal order..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500 bg-white dark:bg-zinc-900"
            :class="{
              'border-red-500 focus:border-red-500 focus:ring-red-500':
                $form.order_date?.invalid,
            }"
            showIcon
          />
          <Message
            v-if="$form.order_date?.invalid"
            severity="error"
            size="small"
            variant="simple"
            class="text-red-600 dark:text-red-400 text-sm"
          >
            {{ $form.order_date.error.message }}
          </Message>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-2">
            <label
              for="pemberi_order"
              class="block text-sm font-medium text-slate-700 dark:text-slate-300"
            >
              <Icon name="lucide:user" class="inline mr-2" size="0.9em" />
              Pemberi Order
            </label>
            <InputText
              id="pemberi_order"
              name="pemberi_order"
              v-model="draftDatas.pemberi_order"
              placeholder="Nama pemberi order (opsional)"
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500 bg-white dark:bg-zinc-900 px-4 py-3"
            />
          </div>

          <div class="space-y-2">
            <label
              for="pemberi_phone"
              class="block text-sm font-medium text-slate-700 dark:text-slate-300"
            >
              <Icon name="lucide:phone" class="inline mr-2" size="0.9em" />
              HP Pemberi Order
            </label>
            <InputText
              id="pemberi_phone"
              name="pemberi_phone"
              v-model="draftDatas.pemberi_phone"
              type="tel"
              inputmode="tel"
              placeholder="Nomor HP pemberi order (opsional)"
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500 bg-white dark:bg-zinc-900 px-4 py-3"
            />
          </div>
        </div>

        <div class="space-y-2">
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            <Icon name="lucide:users" class="inline mr-2" size="0.9em" />
            Contact Person (opsional)
          </label>
          <div class="space-y-3">
            <div
              v-for="(cp, idx) in draftDatas.contact_persons"
              :key="idx"
              class="p-3 rounded-xl border border-slate-200/70 dark:border-zinc-700/70 bg-slate-50/50 dark:bg-zinc-800/30"
            >
              <div class="grid grid-cols-1 sm:grid-cols-[1fr_1fr_70px] gap-3">
                <InputText
                  v-model="cp.name"
                  :name="`contact_persons[${idx}][name]`"
                  placeholder="Nama (opsional)"
                  class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500 bg-white dark:bg-zinc-900 px-4 py-3"
                />
                <InputText
                  v-model="cp.phone"
                  :name="`contact_persons[${idx}][phone]`"
                  type="tel"
                  inputmode="tel"
                  placeholder="No. HP (opsional)"
                  class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500 bg-white dark:bg-zinc-900 px-4 py-3"
                />
                <Button
                  type="button"
                  @click="removeContactPerson(idx)"
                  class="w-[70px] min-w-[70px] px-0 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center"
                >
                  <Icon name="lucide:trash-2" size="0.9em" />
                </Button>
              </div>
            </div>
            <Button
              type="button"
              @click="addContactPerson"
              class="w-full px-4 py-3 bg-gradient-to-r from-slate-500 to-slate-600 hover:from-slate-600 hover:to-slate-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center gap-2 font-medium"
            >
              <Icon name="lucide:plus" size="1em" />
              Tambah Contact Person
            </Button>
          </div>
        </div>

        <div class="space-y-2">
          <label
            for="custom_client_html"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:users" class="inline mr-2" size="0.9em" />
            Custom Klien (untuk Print)
          </label>
          <div
            class="bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-slate-300 dark:border-zinc-600 backdrop-blur-sm overflow-hidden"
          >
            <Editor
              id="custom_client_html"
              v-model="draftDatas.meta.custom_client_html"
              editorStyle="height: 150px; background: transparent;"
              :pt="{
                root: { class: 'w-full' },
                content: { class: 'bg-transparent border-0' },
              }"
            />
          </div>
          <small class="text-xs text-slate-500 dark:text-slate-400">
            Jika diisi, data klien di PDF print akan memakai konten ini. Contoh:
            PJL:Purnomo &lt;br&gt; PBL:Aditya
          </small>
        </div>

        <!-- Maker Field -->
        <div class="space-y-2">
          <label
            for="maker_id"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:pen-tool" class="inline mr-2" size="0.9em" />
            Maker (mengolah surat)
          </label>
          <Select
            id="maker_id"
            name="maker_id"
            v-model="draftDatas.maker_id"
            :options="users"
            optionLabel="name"
            optionValue="id"
            placeholder="Pilih maker (opsional)..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500"
            showClear
            filter
          />
        </div>

        <!-- PIC Field -->
        <!-- PIC Field handled below with dedicated PIC selector -->

        <!-- PIC Field -->
        <div class="space-y-2">
          <label
            for="pic_id"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:user-check" class="inline mr-2" size="0.9em" />
            Pilih PIC
          </label>
          <Select
            v-model="draftDatas.pic_id"
            :options="picOptions"
            filter
            optionLabel="name"
            optionValue="id"
            placeholder="Pilih PIC..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500"
          >
            <template #value="slotProps">
              <div v-if="slotProps.value" class="flex items-center gap-2">
                <Icon
                  name="lucide:user-check"
                  size="0.8em"
                  class="text-slate-500 dark:text-slate-400"
                />
                <span>{{
                  picOptions.find((p) => p.id === slotProps.value)?.name
                }}</span>
              </div>
              <span v-else class="text-slate-400">Pilih PIC...</span>
            </template>
            <template #option="slotProps">
              <div class="flex items-center gap-2 p-2">
                <Icon
                  name="lucide:user-check"
                  size="0.8em"
                  class="text-slate-500 dark:text-slate-400"
                />
                <span>{{ slotProps.option.name }}</span>
              </div>
            </template>
          </Select>
        </div>

        <!-- Related Order Section -->
        <div
          class="space-y-4 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50"
        >
          <div class="flex items-center gap-2 mb-4">
            <div
              class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/30 dark:to-red-900/30 flex items-center justify-center"
            >
              <Icon
                name="lucide:link-2"
                class="text-orange-600 dark:text-orange-400"
                size="0.9em"
              />
            </div>
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
              Relasi Order
            </h3>
          </div>

          <!-- Related Order Field -->
          <div class="space-y-2">
            <label
              for="related_order_id"
              class="block text-sm font-medium text-slate-700 dark:text-slate-300"
            >
              <Icon
                name="lucide:shopping-cart"
                class="inline mr-2"
                size="0.9em"
              />
              Order Terkait
            </label>
            <Select
              id="related_order_id"
              name="related_order_id"
              v-model="draftDatas.related_order_id"
              :options="orderList"
              filter
              optionLabel="label"
              optionValue="id"
              placeholder="Pilih order terkait (opsional)..."
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500"
              showClear
            >
              <template #value="slotProps">
                <div v-if="slotProps.value" class="flex items-center gap-2">
                  <Icon
                    name="lucide:shopping-cart"
                    size="0.8em"
                    class="text-slate-500 dark:text-slate-400"
                  />
                  <span>{{
                    getOrderLabel(
                      orderList.find((o) => o.id === slotProps.value),
                    )
                  }}</span>
                </div>
                <span v-else class="text-slate-400"
                  >Pilih order terkait...</span
                >
              </template>
              <template #option="slotProps">
                <div class="flex items-center gap-2 p-2">
                  <Icon
                    name="lucide:shopping-cart"
                    size="0.8em"
                    class="text-slate-500 dark:text-slate-400"
                  />
                  <span>{{ slotProps.option.label }}</span>
                </div>
              </template>
            </Select>
          </div>

          <!-- Relation Type Field -->
          <div class="space-y-2">
            <label
              for="relation_type"
              class="block text-sm font-medium text-slate-700 dark:text-slate-300"
            >
              <Icon name="lucide:tag" class="inline mr-2" size="0.9em" />
              Tipe Relasi
            </label>
            <InputText
              id="relation_type"
              name="relation_type"
              v-model="draftDatas.relation_type"
              list="relationTypeList"
              placeholder="Contoh: Penjual, Pembeli, Debitur, Penjamin..."
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 focus:ring-green-500 bg-white dark:bg-zinc-900 px-4 py-3"
              :disabled="!draftDatas.related_order_id"
            />
            <datalist id="relationTypeList">
              <option value="Penjual" />
              <option value="Pembeli" />
              <option value="Debitur" />
              <option value="Penjamin" />
              <option value="Pemilik" />
              <option value="Penyewa" />
              <option value="Direktur" />
              <option value="Komisaris" />
              <option value="Pemberi Kuasa" />
              <option value="Penerima Kuasa" />
              <option value="Lainnya" />
            </datalist>
          </div>
        </div>

        <!-- Product Field -->
        <div class="space-y-2">
          <label
            for="product_id"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:package" class="inline mr-2" size="0.9em" />
            Produk *
          </label>
          <MultiSelect
            id="product_ids"
            name="product_ids"
            v-model="draftDatas.product_ids"
            display="chip"
            :options="products"
            filter
            optionLabel="name"
            optionValue="id"
            placeholder="Pilih produk..."
            class="w-full multiselect-modern"
            :class="{
              'border-red-500 focus:border-red-500 focus:ring-red-500':
                $form.product_ids?.invalid,
            }"
          />
          <Message
            v-if="$form.product_ids?.invalid"
            severity="error"
            size="small"
            variant="simple"
            class="text-red-600 dark:text-red-400 text-sm"
          >
            {{ $form.product_ids.error.message }}
          </Message>
        </div>
      </div>

      <!-- Dynamic Product Fields Section -->
      <div
        v-if="selectedProducts.length > 0"
        class="space-y-6 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50"
      >
        <div
          v-for="product in selectedProducts"
          :key="product.id"
          class="p-5 bg-white/60 dark:bg-zinc-800/60 rounded-2xl border border-slate-200/60 dark:border-zinc-700/60"
        >
          <div class="flex items-center gap-3 mb-5">
            <div
              class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg"
            >
              <Icon name="lucide:package" class="text-white" size="1.1em" />
            </div>
            <div class="min-w-0">
              <div class="text-sm text-slate-500 dark:text-slate-400">
                Detail Produk
              </div>
              <div
                class="font-semibold text-slate-800 dark:text-white truncate"
              >
                {{ product.name }}
              </div>
            </div>
          </div>

          <div v-if="product.meta_products?.length" class="space-y-4">
            <div
              v-for="field in product.meta_products"
              :key="`${product.id}_${field?.id}`"
              class="flex items-start gap-3"
            >
              <div class="pt-8">
                <Checkbox
                  v-if="field?.id"
                  :binary="true"
                  :modelValue="isMetaSelectedForPrint(field.id)"
                  @update:modelValue="(v: boolean) => setMetaSelectedForPrint(field.id, v)"
                />
              </div>

              <div class="flex-1 space-y-2">
                <label
                  :for="`data_${product.id}_${field.id}`"
                  class="block text-sm font-medium text-slate-700 dark:text-slate-300"
                >
                  <Icon
                    :name="getFieldIcon(field.type)"
                    class="inline mr-2"
                    size="0.9em"
                  />
                  {{ field.name }} {{ field.required ? "*" : "" }}
                </label>

                <InputText
                  v-if="field?.type === 'text'"
                  :id="`data_${product.id}_${field.id}`"
                  v-model="draftDatas.meta.meta_by_product[product.id][field.id]"
                  :placeholder="'Masukkan ' + field.name.toLowerCase() + '...'"
                  class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 focus:ring-purple-500 bg-white dark:bg-zinc-900 px-4 py-3"
                />

                <InputNumber
                  v-if="field?.type === 'number'"
                  :id="`data_${product.id}_${field.id}`"
                  v-model="draftDatas.meta.meta_by_product[product.id][field.id]"
                  :placeholder="'Masukkan ' + field.name.toLowerCase() + '...'"
                  class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 focus:ring-purple-500 bg-white dark:bg-zinc-900"
                />

                <div v-if="field?.type === 'currency'" class="space-y-1">
                  <InputText
                    :id="`data_${product.id}_${field.id}`"
                    v-model="
                      draftDatas.meta.meta_by_product[product.id][field.id]
                    "
                    type="number"
                    inputmode="numeric"
                    :placeholder="'Masukkan ' + field.name.toLowerCase() + '...'"
                    class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 focus:ring-purple-500 bg-white dark:bg-zinc-900 px-4 py-3"
                  />
                  <div
                    v-if="
                      draftDatas.meta.meta_by_product?.[product.id]?.[field.id] &&
                      Number(
                        draftDatas.meta.meta_by_product?.[product.id]?.[field.id],
                      ) > 0
                    "
                    class="text-sm text-purple-600 dark:text-purple-400 font-medium"
                  >
                    {{
                      formatCurrency(
                        draftDatas.meta.meta_by_product[product.id][field.id],
                      )
                    }}
                  </div>
                </div>

                <DatePicker
                  v-if="field?.type === 'date'"
                  :id="`data_${product.id}_${field.id}`"
                  v-model="draftDatas.meta.meta_by_product[product.id][field.id]"
                  dateFormat="dd/mm/yy"
                  :placeholder="'Pilih ' + field.name.toLowerCase() + '...'"
                  class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 focus:ring-purple-500 bg-white dark:bg-zinc-900"
                  showIcon
                />
              </div>
            </div>
          </div>
          <div v-else class="text-sm text-slate-500 dark:text-slate-400">
            Produk ini tidak memiliki field detail.
          </div>
        </div>
      </div>

      <!-- Financial Information Section -->
      <div
        v-if="datas.user.capabilities.includes('access:keuangan')"
        class="space-y-4 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50"
      >
        <div class="flex items-center gap-2 mb-4">
          <div
            class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 flex items-center justify-center"
          >
            <Icon
              name="lucide:banknote"
              class="text-amber-600 dark:text-amber-400"
              size="0.9em"
            />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
            Informasi Keuangan
          </h3>
        </div>

        <!-- Price Field -->
        <div class="space-y-2">
          <label
            for="price"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:dollar-sign" class="inline mr-2" size="0.9em" />
            Harga Jasa Notaris
          </label>
          <InputText
            id="price"
            name="price"
            v-model="draftDatas.price"
            type="number"
            inputmode="numeric"
            placeholder="Masukkan harga jasa..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-amber-500 focus:ring-amber-500 bg-white dark:bg-zinc-900 px-4 py-3"
          />
          <div
            v-if="draftDatas.price && Number(draftDatas.price) > 0"
            class="text-sm text-amber-600 dark:text-amber-400 font-medium"
          >
            {{ formatCurrency(draftDatas.price) }}
          </div>
        </div>

        <!-- Paid Field -->
        <div class="space-y-2">
          <label
            for="paid"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:credit-card" class="inline mr-2" size="0.9em" />
            Dibayar
          </label>
          <InputText
            id="paid"
            name="paid"
            v-model="draftDatas.paid"
            type="number"
            inputmode="numeric"
            placeholder="Masukkan jumlah yang dibayar..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-amber-500 focus:ring-amber-500 bg-white dark:bg-zinc-900 px-4 py-3"
          />
          <div
            v-if="draftDatas.paid && Number(draftDatas.paid) > 0"
            class="text-sm text-amber-600 dark:text-amber-400 font-medium"
          >
            {{ formatCurrency(draftDatas.paid) }}
          </div>
        </div>

        <!-- Billing Notes Field -->
        <div class="space-y-2">
          <label
            for="billing_notes"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:file-text" class="inline mr-2" size="0.9em" />
            Catatan & Keterangan Tagihan
          </label>
          <textarea
            id="billing_notes"
            name="billing_notes"
            v-model="draftDatas.billing_notes"
            rows="4"
            placeholder="Tulis catatan tagihan di sini..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-amber-500 focus:ring-amber-500 bg-white dark:bg-zinc-900 px-4 py-3 text-sm"
          />
        </div>
      </div>

      <!-- Payment Method Section -->
      <div
        class="space-y-4 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50"
      >
        <div class="flex items-center gap-2 mb-4">
          <div
            class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 flex items-center justify-center"
          >
            <Icon
              name="lucide:wallet"
              class="text-indigo-600 dark:text-indigo-400"
              size="0.9em"
            />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
            Metode Pembayaran
          </h3>
        </div>

        <div class="space-y-2">
          <label
            for="payment_method"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
          >
            <Icon name="lucide:credit-card" class="inline mr-2" size="0.9em" />
            Metode Bayar *
          </label>
          <Select
            id="payment_method"
            name="payment_method"
            v-model="draftDatas.payment_method"
            :options="paymentMethod"
            optionLabel="name"
            optionValue="name"
            placeholder="Pilih metode pembayaran..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-indigo-500 focus:ring-indigo-500"
            :class="{
              'border-red-500 focus:border-red-500 focus:ring-red-500':
                $form.payment_method?.invalid,
            }"
          >
            <template #value="slotProps">
              <div v-if="slotProps.value" class="flex items-center gap-2">
                <Icon
                  :name="
                    slotProps.value === 'Tunai'
                      ? 'lucide:banknote'
                      : 'lucide:credit-card'
                  "
                  size="0.8em"
                  class="text-slate-500 dark:text-slate-400"
                />
                <span>{{ slotProps.value }}</span>
              </div>
              <span v-else class="text-slate-400"
                >Pilih metode pembayaran...</span
              >
            </template>
            <template #option="slotProps">
              <div class="flex items-center gap-2 p-2">
                <Icon
                  :name="
                    slotProps.option.name === 'Tunai'
                      ? 'lucide:banknote'
                      : 'lucide:credit-card'
                  "
                  size="0.8em"
                  class="text-slate-500 dark:text-slate-400"
                />
                <span>{{ slotProps.option.name }}</span>
              </div>
            </template>
          </Select>
          <Message
            v-if="$form.payment_method?.invalid"
            severity="error"
            size="small"
            variant="simple"
            class="text-red-600 dark:text-red-400 text-sm"
          >
            {{ $form.payment_method.error.message }}
          </Message>
        </div>
      </div>
    </Form>
  </div>
  <!-- Customer Selection -->
  <div
    v-else
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6"
  >
    <div class="flex items-center gap-2 mb-4">
      <div
        class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center"
      >
        <Icon
          name="lucide:user-search"
          class="text-blue-600 dark:text-blue-400"
          size="0.9em"
        />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
        Pilih Konsumen
      </h3>
    </div>

    <div class="space-y-2">
      <label
        for="user_id"
        class="block text-sm font-medium text-slate-700 dark:text-slate-300"
      >
        <Icon name="lucide:users" class="inline mr-2" size="0.9em" />
        Konsumen *
      </label>
      <Select
        id="user_id"
        name="user_id"
        :options="konsumen"
        filter
        optionLabel="name"
        optionValue="id"
        placeholder="Pilih konsumen untuk order..."
        class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500"
        @change="
          setKonsumen(konsumen.find((item: any) => item.id === $event.value))
        "
      >
        <template #value="slotProps">
          <div v-if="slotProps.value" class="flex items-center gap-2">
            <div
              class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center"
            >
              <span class="text-white font-bold text-xs">{{
                konsumen
                  .find((k: any) => k.id === slotProps.value)
                  ?.name?.charAt(0)
                  .toUpperCase()
              }}</span>
            </div>
            <span>{{
              konsumen.find((k: any) => k.id === slotProps.value)?.name
            }}</span>
          </div>
          <span v-else class="text-slate-400"
            >Pilih konsumen untuk order...</span
          >
        </template>
        <template #option="slotProps">
          <div class="flex items-center gap-3 p-2">
            <div
              class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center"
            >
              <span class="text-white font-bold text-sm">{{
                slotProps.option.name?.charAt(0).toUpperCase()
              }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="font-medium text-slate-800 dark:text-white">
                {{ slotProps.option.name }}
              </div>
              <div class="text-sm text-slate-500 dark:text-slate-400 truncate">
                {{ slotProps.option.phone }}
              </div>
            </div>
          </div>
        </template>
      </Select>
      <small class="text-xs text-slate-500 dark:text-slate-400">
        Pilih konsumen yang akan membuat order
      </small>
    </div>
  </div>

  <!-- Action Buttons -->
  <div
    class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20"
  >
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
          Aksi
        </h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Simpan perubahan atau batalkan
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <!-- Submit Button -->
      <div class="sm:col-span-1 order-1">
        <Button
          v-if="isLoading"
          :disabled="true"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-400 to-slate-500 text-white rounded-xl font-medium cursor-not-allowed"
        >
          <Icon name="lucide:loader-2" class="animate-spin" size="1em" />
          <span>Memproses...</span>
        </Button>
        <Button
          v-else
          type="submit"
          form="order-form"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon
            :name="!draftDatas.id ? 'lucide:shopping-cart-plus' : 'lucide:save'"
            size="1em"
          />
          <span>{{ !draftDatas.id ? "Tambah Order" : "Update Order" }}</span>
        </Button>
      </div>

      <!-- Detail Button (if editing) -->
      <div v-if="draftDatas.id" class="sm:col-span-1 order-2">
        <Button
          @click="() => emits('goToDetail', draftDatas)"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:eye" size="1em" />
          <span class="hidden sm:inline">Detail Order</span>
          <span class="sm:hidden">Detail</span>
        </Button>
      </div>

      <!-- Close Button -->
      <div class="sm:col-span-1 order-3">
        <Button
          @click="emits('closeDialog')"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:x" size="1em" />
          <span>Tutup</span>
        </Button>
      </div>
    </div>

    <!-- Additional Info -->
    <div
      class="mt-4 p-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200/50 dark:border-green-800/50"
    >
      <div class="flex items-start gap-2">
        <Icon
          name="lucide:info"
          class="text-green-600 dark:text-green-400 mt-0.5"
          size="0.9em"
        />
        <div class="text-sm text-green-700 dark:text-green-300">
          <p class="font-medium mb-1">Catatan:</p>
          <ul class="text-xs space-y-1 text-green-600 dark:text-green-400">
            <li>• Semua field yang bertanda (*) wajib diisi</li>
            <li>• Pastikan memilih konsumen sebelum mengisi data order</li>
            <li>• Tanggal order tidak boleh lebih dari hari ini</li>
            <li v-if="datas.user.capabilities.includes('access:keuangan')">
              • Informasi keuangan bersifat opsional
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Types
interface Product {
  id: number;
  name: string;
  price: number;
  meta_products: any[];
}

interface Customer {
  id: number;
  name: string;
  phone: string;
  address: string;
}

const emits = defineEmits([
  "error",
  "closeDialog",
  "goToDetail",
  "addData",
  "updateData",
]);
const { datas } = defineProps(["datas"]);
const client = useSanctumClient();
const route = useRoute();

const draftDatas = ref(JSON.parse(JSON.stringify(datas)));
draftDatas.value.product = draftDatas.value.product || { id: "" };
draftDatas.value.meta = draftDatas.value.meta || {};
draftDatas.value.meta.custom_client_html = (
  draftDatas.value.meta.custom_client_html || ""
).toString();
draftDatas.value.meta.print_meta_ids = Array.isArray(
  draftDatas.value.meta?.print_meta_ids,
)
  ? draftDatas.value.meta.print_meta_ids
      .map((v: any) => Number(v))
      .filter((v: any) => !isNaN(v))
  : [];
draftDatas.value.product_ids = Array.isArray(draftDatas.value.meta?.product_ids)
  ? draftDatas.value.meta.product_ids
      .map((v: any) => Number(v))
      .filter((v: any) => !isNaN(v))
  : draftDatas.value.product_id
    ? [Number(draftDatas.value.product_id)]
    : [];
draftDatas.value.product_id = draftDatas.value.product_ids?.length
  ? Number(draftDatas.value.product_ids[0])
  : draftDatas.value.product_id;
draftDatas.value.product.id = draftDatas.value.product_id ?? "";
draftDatas.value.meta.meta_by_product ??= {} as any;
draftDatas.value.customer = draftDatas.value.customer || {};
draftDatas.value.pic_id = draftDatas.value.pic_id || null;
draftDatas.value.contact_persons = Array.isArray(
  draftDatas.value.contact_persons,
)
  ? draftDatas.value.contact_persons
  : [];
const legacyFirst = draftDatas.value.contact_persons?.[0];
if (legacyFirst && typeof legacyFirst === "object") {
  const legacyName = (legacyFirst?.name || "").toString().trim();
  const legacyPhone = (legacyFirst?.phone || "").toString().trim();
  const currentName = (draftDatas.value.pemberi_order || "").toString().trim();
  const currentPhone = (draftDatas.value.pemberi_phone || "").toString().trim();

  if ((!currentName && legacyName) || (!currentPhone && legacyPhone)) {
    if (!currentName && legacyName) draftDatas.value.pemberi_order = legacyName;
    if (!currentPhone && legacyPhone) draftDatas.value.pemberi_phone = legacyPhone;
    draftDatas.value.contact_persons = draftDatas.value.contact_persons.slice(1);
  } else if (
    currentName &&
    currentPhone &&
    legacyName === currentName &&
    legacyPhone === currentPhone
  ) {
    draftDatas.value.contact_persons = draftDatas.value.contact_persons.slice(1);
  }
}
draftDatas.value.contact_persons = draftDatas.value.contact_persons.map(
  (cp: any) => ({
    name: (cp?.name || "").toString(),
    phone: (cp?.phone || "").toString(),
  }),
);
const isLoading = ref(false);
const products = ref<Product[]>([]);
const konsumenId = ref("") as any;
const konsumen = ref<Customer[]>([]);
const users = ref<any[]>([]);
const pics = ref<any[]>([]);
const picOptions = computed(() =>
  pics.value && pics.value.length ? pics.value : users.value,
);

const paymentMethod = ref([{ name: "Tunai" }, { name: "Transfer" }]);

const selectedProducts = computed(() => {
  const ids = Array.isArray(draftDatas.value.product_ids)
    ? draftDatas.value.product_ids
    : [];
  const normalized = ids
    .map((v: any) => Number(v))
    .filter((v: any) => !isNaN(v));

  return normalized
    .map((id: number) =>
      products.value.find((p: any) => Number(p.id) === Number(id)),
    )
    .filter(Boolean) as any[];
});

const isMetaSelectedForPrint = (metaId: any) => {
  const id = Number(metaId);
  if (isNaN(id)) return false;
  const ids = draftDatas.value.meta?.print_meta_ids;
  return Array.isArray(ids) && ids.includes(id);
};

const setMetaSelectedForPrint = (metaId: any, enabled: boolean) => {
  const id = Number(metaId);
  if (isNaN(id)) return;
  draftDatas.value.meta ??= {} as any;
  if (!Array.isArray(draftDatas.value.meta.print_meta_ids)) {
    draftDatas.value.meta.print_meta_ids = [];
  }
  const ids = draftDatas.value.meta.print_meta_ids as number[];
  const exists = ids.includes(id);
  if (enabled && !exists) ids.push(id);
  if (!enabled && exists) {
    draftDatas.value.meta.print_meta_ids = ids.filter((v) => v !== id);
  }
};

// Order list for dropdown
const orderList = ref<any[]>([]);

// Helper to format order label
const getOrderLabel = (order: any) => {
  if (!order) return "";
  if (order.customer?.name) {
    return `${order.no_order} - ${order.customer.name}`;
  }
  return order.no_order;
};

// Load order list
const loadOrderList = async () => {
  try {
    const params = new URLSearchParams();
    if (draftDatas.value.id) {
      params.append("exclude_id", String(draftDatas.value.id));
    }
    const orders = await client(`/orders/list?${params.toString()}`);
    orderList.value = orders.map((order: any) => ({
      ...order,
      label: getOrderLabel(order),
    }));
  } catch (e) {
    console.error("Error loading order list:", e);
  }
};

// Form resolver for validation
const resolver = () => {
  const errors = {} as any;

  if (!draftDatas.value.order_date) {
    errors.order_date = [{ message: "Tanggal order wajib diisi" }];
  }

  if (
    !Array.isArray(draftDatas.value.product_ids) ||
    draftDatas.value.product_ids.length === 0
  ) {
    errors.product_ids = [{ message: "Produk wajib dipilih" }];
  }

  if (!draftDatas.value.payment_method) {
    errors.payment_method = [{ message: "Metode pembayaran wajib dipilih" }];
  }

  if (!draftDatas.value.customer_id) {
    errors.customer_id = [{ message: "Konsumen wajib dipilih" }];
  }

  return { errors };
};

// Helper function to get field icon
const getFieldIcon = (type: string) => {
  switch (type) {
    case "text":
      return "lucide:type";
    case "number":
      return "lucide:hash";
    case "currency":
      return "lucide:dollar-sign";
    case "date":
      return "lucide:calendar";
    default:
      return "lucide:settings";
  }
};

// Helper function to format currency
const formatCurrency = (value: any) => {
  if (!value || isNaN(Number(value))) return "Rp 0";
  const num = Number(value);
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  }).format(num);
};

const normalizeList = (response: any) => {
  if (Array.isArray(response)) return response;
  if (Array.isArray(response?.data)) return response.data;
  if (Array.isArray(response?.data?.data)) return response.data.data;
  return [];
};

const normalizeId = (v: any) =>
  v === "" || v === null || v === undefined ? v : Number(v);

// Initialize component
onMounted(async () => {
  draftDatas.value.meta ??= {} as any;
  draftDatas.value.product ??= {} as any;
  draftDatas.value.product.id ??= "";
  draftDatas.value.customer ??= {} as any;
  draftDatas.value.order_date ??= new Date().toISOString().split("T")[0];
  if (draftDatas.value.product_id)
    draftDatas.value.product_id = Number(draftDatas.value.product_id);

  // Pastikan produk selalu ter-load untuk menampilkan daftar di dropdown
  await loadProducts();
  if (!draftDatas.value.customer?.id) await loadKonsumen();

  // Load daftar users untuk Maker/PIC
  try {
    const resUsers = await client("/karyawans?paginate=false");
    users.value = normalizeList(resUsers);
    if (!users.value?.length) {
      const resUsersMin = await client("/karyawans/min");
      users.value = normalizeList(resUsersMin);
    }
  } catch (e) {
    try {
      const resUsersMin = await client("/karyawans/min");
      users.value = normalizeList(resUsersMin);
    } catch (err) {
      console.log("Error fetch users", err);
    }
  }

  if (route.query.konsumen_id) {
    konsumenId.value = route.query.konsumen_id;
    await loadKonsumen();
  }
  await loadPICs();
  await loadOrderList();
});

const ensureMetaByProductBuckets = (ids: number[]) => {
  draftDatas.value.meta ??= {} as any;
  draftDatas.value.meta.meta_by_product ??= {} as any;
  for (const id of ids) {
    const key = String(id);
    if (!draftDatas.value.meta.meta_by_product[key]) {
      draftDatas.value.meta.meta_by_product[key] = {};
    }
  }
};

const migrateLegacyMetaToByProduct = () => {
  draftDatas.value.meta ??= {} as any;
  draftDatas.value.meta.meta_by_product ??= {} as any;

  const ids = Array.isArray(draftDatas.value.product_ids)
    ? draftDatas.value.product_ids
    : [];
  const primaryId = ids.length
    ? Number(ids[0])
    : Number(draftDatas.value.product_id);
  if (!primaryId || isNaN(primaryId)) return;

  const byProduct = draftDatas.value.meta.meta_by_product;
  const primaryKey = String(primaryId);
  if (byProduct[primaryKey] && Object.keys(byProduct[primaryKey]).length)
    return;

  const legacyEntries = Object.entries(draftDatas.value.meta).filter(([k]) =>
    /^\d+$/.test(k),
  );
  if (!legacyEntries.length) return;

  byProduct[primaryKey] = byProduct[primaryKey] || {};
  for (const [k, v] of legacyEntries) {
    byProduct[primaryKey][k] = v;
  }
};

watch(
  () => selectedProducts.value,
  (items: any[]) => {
    draftDatas.value.meta ??= {} as any;
    if (!Array.isArray(draftDatas.value.meta.print_meta_ids)) {
      draftDatas.value.meta.print_meta_ids = [];
    }
    if ((draftDatas.value.meta.print_meta_ids as any[]).length) return;

    const defaults: number[] = [];
    for (const p of items || []) {
      const fields = Array.isArray(p?.meta_products) ? p.meta_products : [];
      for (const f of fields) {
        if (f?.show_in_print) {
          const id = Number(f?.id);
          if (!isNaN(id)) defaults.push(id);
        }
      }
    }
    draftDatas.value.meta.print_meta_ids = Array.from(new Set(defaults));
  },
  { immediate: true, deep: true },
);

watch(
  () => draftDatas.value.product_ids,
  (newVal: any) => {
    const ids = Array.isArray(newVal) ? newVal : [];
    const normalized = ids
      .map((v: any) => Number(v))
      .filter((v: any) => !isNaN(v));
    const unique = Array.from(new Set(normalized));

    const current = Array.isArray(draftDatas.value.product_ids)
      ? draftDatas.value.product_ids
      : [];
    if (
      unique.length !== current.length ||
      unique.some((v, i) => Number(current[i]) !== Number(v))
    ) {
      draftDatas.value.product_ids = unique;
    }

    draftDatas.value.product_id = unique.length ? unique[0] : null;
    draftDatas.value.product ??= {} as any;
    draftDatas.value.product.id = draftDatas.value.product_id ?? "";

    migrateLegacyMetaToByProduct();
    ensureMetaByProductBuckets(unique);
  },
  { immediate: true },
);

// Watch for customer changes to set customer_id
watch(
  () => draftDatas.value.customer?.id,
  (newVal) => {
    draftDatas.value.customer_id = newVal ? normalizeId(newVal) : null;
  },
  { immediate: true },
);

// Load PICs data
const loadPICs = async () => {
  try {
    const response = await client("/karyawans?role=PIC&paginate=false");
    pics.value = normalizeList(response);
    if (!pics.value?.length) {
      const resMin = await client("/karyawans/min?role=PIC");
      pics.value = normalizeList(resMin);
    }
  } catch (error) {
    try {
      const resMin = await client("/karyawans/min?role=PIC");
      pics.value = normalizeList(resMin);
    } catch (err) {
      console.log("Error fetching PICs:", err);
    }
  }
};

// Watch for customer changes
watch(
  () => [draftDatas.value.customer?.id, draftDatas.value.customer_id],
  async () => {
    if (draftDatas.value.customer) {
      draftDatas.value.customer_id = draftDatas.value.customer.id;
    } else if (konsumenId) {
      draftDatas.value.customer_id = Number(konsumenId.value);
    }
  },
);

const loadProducts = async () => {
  try {
    const response_products = await client("/produk?paginate=false");
    products.value = normalizeList(response_products);
  } catch (error) {
    console.log("Error fetching products:", error);
  }
};

// Load konsumen data
const loadKonsumen = async () => {
  try {
    const response: any = konsumenId.value
      ? await client(`/customers/${konsumenId.value}`)
      : await client("/customers?paginate=false");

    if (konsumenId.value) {
      draftDatas.value.customer = Array.isArray(response?.data)
        ? (response.data?.[0] ?? response)
        : Array.isArray(response?.data?.data)
          ? (response.data.data?.[0] ?? response)
          : response;
    } else {
      konsumen.value = normalizeList(response);
    }
  } catch (error) {
    console.log("Error fetching konsumen:", error);
  }
};

// Set konsumen
const setKonsumen = async (event: any) => {
  draftDatas.value.customer = event;
};

// Reset konsumen
const resetKonsumen = async () => {
  konsumenId.value = "";
  await loadKonsumen();
  draftDatas.value.customer = null;
};

const addContactPerson = () => {
  if (!Array.isArray(draftDatas.value.contact_persons)) {
    draftDatas.value.contact_persons = [];
  }
  draftDatas.value.contact_persons.push({ name: "", phone: "" });
};

const removeContactPerson = (index: number) => {
  if (!Array.isArray(draftDatas.value.contact_persons)) {
    draftDatas.value.contact_persons = [];
    return;
  }
  draftDatas.value.contact_persons.splice(index, 1);
};

// Format date to YYYY-MM-DD
const formatToYYYYMMDD = (date: any) => {
  const d = new Date(date);
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
};

const getApiErrorMessage = (err: any) => {
  const data =
    err?.data ??
    err?.response?._data ??
    err?.response?.data ??
    err?.cause?.data;

  if (typeof data === "string" && data.trim()) return data.trim();

  if (data && typeof data === "object") {
    if (
      typeof (data as any).message === "string" &&
      (data as any).message.trim()
    ) {
      return (data as any).message.trim();
    }

    const errors = (data as any).errors;
    if (errors && typeof errors === "object") {
      const first = Object.values(errors)
        .flat()
        .find((v: any) => typeof v === "string" && v.trim());
      if (first) return String(first).trim();
    }
  }

  if (typeof err?.statusMessage === "string" && err.statusMessage.trim())
    return err.statusMessage.trim();
  if (typeof err?.message === "string" && err.message.trim())
    return err.message.trim();

  return null;
};

// Handle form submission
const handleSubmit = async ({ valid }: { valid: boolean }) => {
  if (!valid) {
    console.log("Form is not valid, stopping submission");
    return;
  }

  console.log("Starting submission process...");
  isLoading.value = true;

  // Format dates to YYYY-MM-DD
  draftDatas.value.order_date = draftDatas.value.order_date
    ? formatToYYYYMMDD(draftDatas.value.order_date)
    : null;

  if (!draftDatas.value.price) {
    draftDatas.value.price = 0;
  }
  if (!draftDatas.value.paid) {
    draftDatas.value.paid = 0;
  }

  const contactPersons = Array.isArray(draftDatas.value.contact_persons)
    ? draftDatas.value.contact_persons
    : [];
  const cleanedContactPersons = contactPersons
    .map((cp: any) => ({
      name: (cp?.name || "").toString().trim(),
      phone: (cp?.phone || "").toString().trim(),
    }))
    .filter((cp: any) => cp.name || cp.phone)
    .map((cp: any) => ({
      name: cp.name || null,
      phone: cp.phone || null,
    }));
  draftDatas.value.contact_persons = cleanedContactPersons;

  draftDatas.value.meta ??= {} as any;
  draftDatas.value.meta.meta_by_product ??= {} as any;
  draftDatas.value.meta.product_ids = Array.isArray(
    draftDatas.value.product_ids,
  )
    ? draftDatas.value.product_ids
    : [];
  if (
    Array.isArray(draftDatas.value.product_ids) &&
    draftDatas.value.product_ids.length &&
    !draftDatas.value.product_id
  ) {
    draftDatas.value.product_id = Number(draftDatas.value.product_ids[0]);
    draftDatas.value.product ??= {} as any;
    draftDatas.value.product.id = draftDatas.value.product_id ?? "";
  }
  if (draftDatas.value.product_id) {
    const primaryKey = String(draftDatas.value.product_id);
    const bucket =
      draftDatas.value.meta.meta_by_product?.[primaryKey] ??
      draftDatas.value.meta.meta_by_product?.[draftDatas.value.product_id];
    if (bucket && typeof bucket === "object") {
      for (const [k, v] of Object.entries(bucket)) {
        if (/^\d+$/.test(String(k))) {
          draftDatas.value.meta[String(k)] = v as any;
        }
      }
    }
  }

  try {
    if (draftDatas.value.id) {
      // Update existing order
      console.log("Updating order with ID:", draftDatas.value.id);
      const responseUpdate = await client(
        `/orders/${draftDatas.value.id}`,
        {
          method: "PUT",
          body: draftDatas.value,
        },
      );
      console.log("Update response:", responseUpdate);
      emits("updateData", responseUpdate);
    } else {
      // Add new order
      console.log("Adding new order...");
      const responseAdd = await client("/orders", {
        method: "POST",
        body: draftDatas.value,
      });
      console.log("Add response:", responseAdd);
      emits("addData", responseAdd);
    }
  } catch (error) {
    console.error("Submit error:", error);
    const fallback = draftDatas.value.id
      ? "Update order gagal! Periksa kembali data anda."
      : "Tambah order gagal! Periksa kembali data anda.";
    const status = (error as any)?.status ?? (error as any)?.response?.status;
    const detail = getApiErrorMessage(error);
    const message =
      status === 401
        ? "Sesi berakhir. Silakan login ulang."
        : detail || fallback;
    emits("error", message);
  } finally {
    console.log("Setting loading to false");
    isLoading.value = false;
  }
};
</script>
