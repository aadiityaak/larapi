<template>
  <div class="space-y-4 md:space-y-6">
    <DashboardGreeting :dashboard-data="data" />

    <!-- Date Filter Section -->
    <div
      class="bg-white/30 dark:bg-zinc-800/30 backdrop-blur-xl rounded-2xl p-4 md:p-6 border border-slate-200/50 dark:border-zinc-700/50 shadow-xl"
    >
      <div class="flex flex-col space-y-4">
        <!-- Header -->
        <div class="flex items-center gap-3">
          <div
            class="p-2 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl shadow-lg"
          >
            <Icon
              name="lucide:calendar-range"
              class="text-base md:text-lg text-white"
            />
          </div>
          <div>
            <h3
              class="text-base md:text-lg font-bold text-slate-800 dark:text-zinc-200"
            >
              Filter Tanggal
            </h3>
            <p class="text-xs md:text-sm text-slate-500 dark:text-zinc-400">
              Pilih rentang periode data
            </p>
          </div>
        </div>

        <!-- Content -->
        <div
          class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between"
        >
          <!-- Date Range Inputs -->
          <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <div class="flex items-center gap-2">
              <label
                class="text-xs md:text-sm font-medium text-slate-600 dark:text-zinc-400 w-12 flex-shrink-0"
                >Dari:</label
              >
              <Calendar
                v-model="dateFromCalendar"
                dateFormat="yy-mm-dd"
                showIcon
                :class="'flex-1'"
                :pt="{
                  root: { class: 'w-full' },
                  input: {
                    class:
                      'w-full !rounded-lg !border-slate-200 dark:!border-zinc-600 !bg-white/60 dark:!bg-zinc-700/60 focus:!border-blue-500 focus:!ring-blue-500 !text-xs md:!text-sm !px-2 md:!px-3 !py-1.5 md:!py-2',
                  },
                }"
              />
            </div>
            <div class="flex items-center gap-2">
              <label
                class="text-xs md:text-sm font-medium text-slate-600 dark:text-zinc-400 w-12 flex-shrink-0"
                >Sampai:</label
              >
              <Calendar
                v-model="dateToCalendar"
                dateFormat="yy-mm-dd"
                showIcon
                :class="'flex-1'"
                :pt="{
                  root: { class: 'w-full' },
                  input: {
                    class:
                      'w-full !rounded-lg !border-slate-200 dark:!border-zinc-600 !bg-white/60 dark:!bg-zinc-700/60 focus:!border-blue-500 focus:!ring-blue-500 !text-xs md:!text-sm !px-2 md:!px-3 !py-1.5 md:!py-2',
                  },
                }"
              />
            </div>
          </div>

          <!-- Quick Filter Buttons -->
          <div class="flex flex-wrap gap-1.5 md:gap-2">
            <button
              @click="setQuickFilter('today')"
              :class="[
                'px-2 md:px-3 py-1 md:py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                activeQuickFilter === 'today'
                  ? 'bg-blue-500 text-white shadow-lg'
                  : 'bg-white/60 dark:bg-zinc-700/60 text-slate-600 dark:text-zinc-400 hover:bg-blue-100 dark:hover:bg-zinc-600',
              ]"
            >
              Hari Ini
            </button>
            <button
              @click="setQuickFilter('yesterday')"
              :class="[
                'px-2 md:px-3 py-1 md:py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                activeQuickFilter === 'yesterday'
                  ? 'bg-blue-500 text-white shadow-lg'
                  : 'bg-white/60 dark:bg-zinc-700/60 text-slate-600 dark:text-zinc-400 hover:bg-blue-100 dark:hover:bg-zinc-600',
              ]"
            >
              Kemarin
            </button>
            <button
              @click="setQuickFilter('week')"
              :class="[
                'px-2 md:px-3 py-1 md:py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                activeQuickFilter === 'week'
                  ? 'bg-blue-500 text-white shadow-lg'
                  : 'bg-white/60 dark:bg-zinc-700/60 text-slate-600 dark:text-zinc-400 hover:bg-blue-100 dark:hover:bg-zinc-600',
              ]"
            >
              Minggu Ini
            </button>
            <button
              @click="setQuickFilter('month')"
              :class="[
                'px-2 md:px-3 py-1 md:py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                activeQuickFilter === 'month'
                  ? 'bg-blue-500 text-white shadow-lg'
                  : 'bg-white/60 dark:bg-zinc-700/60 text-slate-600 dark:text-zinc-400 hover:bg-blue-100 dark:hover:bg-zinc-600',
              ]"
            >
              Bulan Ini
            </button>
            <button
              @click="setQuickFilter('6months')"
              :class="[
                'px-2 md:px-3 py-1 md:py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                activeQuickFilter === '6months'
                  ? 'bg-blue-500 text-white shadow-lg'
                  : 'bg-white/60 dark:bg-zinc-700/60 text-slate-600 dark:text-zinc-400 hover:bg-blue-100 dark:hover:bg-zinc-600',
              ]"
            >
              6 Bulan
            </button>
            <button
              @click="setQuickFilter('year')"
              :class="[
                'px-2 md:px-3 py-1 md:py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                activeQuickFilter === 'year'
                  ? 'bg-blue-500 text-white shadow-lg'
                  : 'bg-white/60 dark:bg-zinc-700/60 text-slate-600 dark:text-zinc-400 hover:bg-blue-100 dark:hover:bg-zinc-600',
              ]"
            >
              1 Tahun
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="pending" class="space-y-6">
      <!-- Loading skeleton -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="i in 2"
              :key="i"
              class="bg-zinc-100 dark:bg-zinc-800 rounded-xl p-6 animate-pulse"
            >
              <div class="h-4 bg-zinc-300 dark:bg-zinc-600 rounded mb-4"></div>
              <div class="h-8 bg-zinc-300 dark:bg-zinc-600 rounded mb-2"></div>
              <div class="h-3 bg-zinc-300 dark:bg-zinc-600 rounded w-1/2"></div>
            </div>
          </div>
        </div>
        <div class="lg:col-span-4">
          <div
            class="bg-zinc-100 dark:bg-zinc-800 rounded-xl p-6 animate-pulse"
          >
            <div class="h-4 bg-zinc-300 dark:bg-zinc-600 rounded mb-4"></div>
            <div class="space-y-3">
              <div class="h-12 bg-zinc-300 dark:bg-zinc-600 rounded-lg"></div>
              <div class="h-12 bg-zinc-300 dark:bg-zinc-600 rounded-lg"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="error" class="text-center py-12">
      <Icon
        name="lucide:alert-circle"
        class="text-6xl text-red-500 mx-auto mb-4"
      />
      <h3 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200 mb-2">
        Failed to load dashboard data
      </h3>
      <p class="text-zinc-600 dark:text-zinc-400 mb-4">{{ error.message }}</p>
      <Button @click="refresh()" class="px-6 py-2">
        <Icon name="lucide:refresh-cw" class="mr-2" />
        Try Again
      </Button>
    </div>

    <div
      v-else-if="
        user &&
        user.capabilities &&
        user.capabilities.includes('access:keuangan')
      "
      class="space-y-8"
    >
      <!-- KPI Cards -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div
          class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-2xl hover:shadow-emerald-500/25 transition-all duration-300 relative overflow-hidden"
        >
          <div
            class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-6 translate-x-6"
          ></div>
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-white/20 rounded-2xl">
                <Icon name="lucide:trending-up" class="text-2xl text-white" />
              </div>
              <div class="text-right">
                <div class="text-xs font-medium text-emerald-100">
                  vs periode sebelumnya
                </div>
                <div class="text-lg font-bold text-white">
                  +{{
                    calculateTrend(
                      data.pendapatan_bulan_ini,
                      data.pendapatan_bulan_sebelumnya,
                    )
                  }}%
                </div>
              </div>
            </div>
            <div class="text-3xl font-bold text-white mb-1">
              {{ formatCurrency(data.pendapatan_bulan_ini) }}
            </div>
            <div class="text-sm text-emerald-100">
              Pendapatan {{ filterLabel }}
            </div>
          </div>
        </div>

        <!-- Orders Card -->
        <div
          class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-6 text-white shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 relative overflow-hidden"
        >
          <div
            class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-6 translate-x-6"
          ></div>
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-white/20 rounded-2xl">
                <Icon name="lucide:shopping-cart" class="text-2xl text-white" />
              </div>
              <div class="text-right">
                <div class="text-xs font-medium text-blue-100">total order</div>
                <div class="text-lg font-bold text-white">
                  {{ data.total_orders }}
                </div>
              </div>
            </div>
            <div class="text-3xl font-bold text-white mb-1">
              {{ data.order_bulan_ini }}
            </div>
            <div class="text-sm text-blue-100">Order {{ filterLabel }}</div>
          </div>
        </div>

        <!-- Billing Card -->
        <div
          class="bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl p-6 text-white shadow-2xl hover:shadow-orange-500/25 transition-all duration-300 relative overflow-hidden"
        >
          <div
            class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-6 translate-x-6"
          ></div>
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-white/20 rounded-2xl">
                <Icon name="lucide:receipt" class="text-2xl text-white" />
              </div>
              <div class="text-right">
                <div class="text-xs font-medium text-orange-100">
                  belum bayar
                </div>
                <div class="text-lg font-bold text-white">
                  {{ formatCurrency(data.total_tagihan) }}
                </div>
              </div>
            </div>
            <div class="text-3xl font-bold text-white mb-1">
              {{ formatCurrency(data.total_tagihan_bulan_ini) }}
            </div>
            <div class="text-sm text-orange-100">Tagihan {{ filterLabel }}</div>
          </div>
        </div>

        <!-- Quick Actions Card -->
        <div
          class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl p-6 text-white shadow-2xl hover:shadow-purple-500/25 transition-all duration-300 relative overflow-hidden"
        >
          <div
            class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-6 translate-x-6"
          ></div>
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
              <div class="p-3 bg-white/20 rounded-2xl">
                <Icon name="lucide:zap" class="text-2xl text-white" />
              </div>
              <div class="text-right">
                <div class="text-xs font-medium text-purple-100">aksi</div>
                <div class="text-lg font-bold text-white">Cepat</div>
              </div>
            </div>
            <div class="space-y-3">
              <NuxtLink
                to="/order"
                class="flex items-center p-3 bg-white/15 rounded-xl hover:bg-white/25 transition-all duration-200 group"
              >
                <Icon
                  name="lucide:plus-circle"
                  class="text-lg text-white mr-3"
                />
                <span class="text-sm font-semibold text-white">Order Baru</span>
              </NuxtLink>
              <NuxtLink
                to="/konsumen"
                class="flex items-center p-3 bg-white/15 rounded-xl hover:bg-white/25 transition-all duration-200 group"
              >
                <Icon name="lucide:user-plus" class="text-lg text-white mr-3" />
                <span class="text-sm font-semibold text-white">Konsumen Baru</span>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>

      <!-- Revenue & Billing Trend Chart -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2">
          <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-2xl border border-gray-100 dark:border-zinc-800"
          >
            <div class="flex items-center justify-between mb-8">
              <div>
                <h3
                  class="text-2xl font-bold text-gray-900 dark:text-white mb-2"
                >
                  Tren Pendapatan & Tagihan
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                  Data 12 bulan terakhir dari database
                </p>
              </div>
              <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                  <span class="text-sm text-gray-600 dark:text-gray-300">Pendapatan</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                  <span class="text-sm text-gray-600 dark:text-gray-300">Tagihan</span>
                </div>
              </div>
            </div>
            <div class="h-80">
              <Chart
                v-if="trendChartData"
                type="line"
                :data="trendChartData"
                :options="trendChartOptions"
                class="w-full h-full"
              />
              <div
                v-else
                class="flex items-center justify-center h-full bg-gray-50 dark:bg-zinc-800 rounded-2xl"
              >
                <div class="text-center">
                  <Icon
                    name="lucide:trending-up"
                    class="text-4xl text-gray-400 dark:text-gray-600 mx-auto mb-4"
                  />
                  <p class="text-gray-500 dark:text-gray-400">
                    Memuat data tren 12 bulan...
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Team Stats -->
        <div class="space-y-6">
          <!-- Team Overview -->
          <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-zinc-800"
          >
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl">
                <Icon
                  name="lucide:users"
                  class="text-xl text-blue-600 dark:text-blue-400"
                />
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                  Tim & Pelanggan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Ringkasan tim
                </p>
              </div>
            </div>
            <div class="space-y-4">
              <div
                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800 rounded-2xl"
              >
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                    <Icon
                      name="tdesign:usergroup"
                      class="text-blue-600 dark:text-blue-400"
                    />
                  </div>
                  <span class="font-medium text-gray-700 dark:text-gray-300">Karyawan</span>
                </div>
                <span
                  class="text-2xl font-bold text-gray-900 dark:text-white"
                  >{{ data.total_karyawan }}</span>
              </div>
              <div
                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800 rounded-2xl"
              >
                <div class="flex items-center gap-3">
                  <div
                    class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl"
                  >
                    <Icon
                      name="lucide:users"
                      class="text-emerald-600 dark:text-emerald-400"
                    />
                  </div>
                  <span class="font-medium text-gray-700 dark:text-gray-300">Konsumen</span>
                </div>
                <span
                  class="text-2xl font-bold text-gray-900 dark:text-white"
                  >{{ data.total_customers }}</span>
              </div>
            </div>
          </div>

          <!-- Active Tasks -->
          <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-zinc-800"
          >
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-2xl">
                <Icon
                  name="lucide:activity"
                  class="text-xl text-orange-600 dark:text-orange-400"
                />
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                  Jobdesk Aktif
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ filterLabel }}
                </p>
              </div>
            </div>
            <div class="text-center">
              <div
                class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2"
              >
                {{ data?.total_jobdesks?.Progress || 0 }}
              </div>
              <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                dari {{ totalJobdesk }} total jobdesk
              </div>
              <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-2">
                <div
                  class="bg-gradient-to-r from-orange-400 to-orange-600 h-2 rounded-full transition-all duration-500"
                  :style="`width: ${((data?.total_jobdesks?.Progress || 0) / totalJobdesk) * 100}%`"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Jobdesk Trend 30 Days -->
      <div
        class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-2xl border border-gray-100 dark:border-zinc-800"
      >
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-2xl">
              <Icon
                name="lucide:trending-up"
                class="text-2xl text-purple-600 dark:text-purple-400"
              />
            </div>
            <div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Tren Jobdesk 30 Hari Terakhir
              </h3>
              <p class="text-gray-500 dark:text-gray-400">
                Progress harian jobdesk tim
              </p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ totalJobdesk }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
              Total Jobdesk ({{ filterLabel }})
            </div>
          </div>
        </div>

        <div class="h-80 mb-6">
          <Chart
            v-if="jobdeskTrendChartData"
            type="line"
            :data="jobdeskTrendChartData"
            :options="jobdeskTrendChartOptions"
            class="w-full h-full"
          />
          <div
            v-else
            class="flex items-center justify-center h-full bg-gray-50 dark:bg-zinc-800 rounded-2xl"
          >
            <div class="text-center">
              <Icon
                name="lucide:bar-chart-3"
                class="text-4xl text-gray-400 dark:text-gray-600 mx-auto mb-4"
              />
              <p class="text-gray-500 dark:text-gray-400">
                Memuat data tren jobdesk...
              </p>
            </div>
          </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Masuk Card -->
          <div
            class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 border border-red-100 dark:border-red-800/30"
          >
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                <span
                  class="text-lg font-semibold text-red-700 dark:text-red-300"
                  >Masuk</span>
              </div>
              <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{
                data?.total_jobdesks.Masuk || 0
              }}</span>
            </div>
            <div class="text-sm text-red-600 dark:text-red-400">
              {{
                (
                  ((data?.total_jobdesks.Masuk || 0) / totalJobdesk) *
                  100
                ).toFixed(1)
              }}% dari total
            </div>
          </div>

          <!-- Progress Card -->
          <div
            class="bg-orange-50 dark:bg-orange-900/20 rounded-2xl p-6 border border-orange-100 dark:border-orange-800/30"
          >
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                <span
                  class="text-lg font-semibold text-orange-700 dark:text-orange-300"
                  >Proses</span>
              </div>
              <span
                class="text-2xl font-bold text-orange-600 dark:text-orange-400"
                >{{ data?.total_jobdesks.Progress || 0 }}</span>
            </div>
            <div class="text-sm text-orange-600 dark:text-orange-400">
              {{
                (
                  ((data?.total_jobdesks.Progress || 0) / totalJobdesk) *
                  100
                ).toFixed(1)
              }}% sedang dikerjakan
            </div>
          </div>

          <!-- Selesai Card -->
          <div
            class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-6 border border-green-100 dark:border-green-800/30"
          >
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                <span
                  class="text-lg font-semibold text-green-700 dark:text-green-300"
                  >Selesai</span>
              </div>
              <span
                class="text-2xl font-bold text-green-600 dark:text-green-400"
                >{{ data?.total_jobdesks.Selesai || 0 }}</span>
            </div>
            <div class="text-sm text-green-600 dark:text-green-400">
              {{
                (
                  ((data?.total_jobdesks.Selesai || 0) / totalJobdesk) *
                  100
                ).toFixed(1)
              }}% telah selesai
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="isAdminUser" class="space-y-8">
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="space-y-6">
          <div
            class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-zinc-800"
          >
            <div class="flex items-center gap-3 mb-6">
              <div
                class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl"
              >
                <Icon
                  name="lucide:award"
                  class="text-xl text-emerald-600 dark:text-emerald-400"
                />
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                  Top Rajin (30 Hari)
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Frekuensi login 30 hari terakhir
                </p>
              </div>
            </div>
            <div class="h-64">
              <Chart
                v-if="karyawanLoginTopChartData"
                type="bar"
                :data="karyawanLoginTopChartData"
                :options="karyawanLoginTopChartOptions"
                class="w-full h-full"
              />
              <div
                v-else
                class="flex items-center justify-center h-full bg-gray-50 dark:bg-zinc-800 rounded-2xl"
              >
                <div class="text-center">
                  <Icon
                    name="lucide:bar-chart-3"
                    class="text-4xl text-gray-400 dark:text-gray-600 mx-auto mb-4"
                  />
                  <p class="text-gray-500 dark:text-gray-400">
                    Memuat data login...
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="xl:col-span-2 bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-2xl border border-gray-100 dark:border-zinc-800"
        >
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl">
                <Icon
                  name="lucide:users"
                  class="text-xl text-blue-600 dark:text-blue-400"
                />
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                  Terakhir Login Semua Karyawan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Diurutkan dari yang paling lama tidak login
                </p>
              </div>
            </div>
            <div class="text-right">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
              <div class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ karyawanLoginUsersSorted.length }}
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr
                  class="text-left text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-zinc-800"
                >
                  <th class="py-3 pr-4 font-semibold">Karyawan</th>
                  <th class="py-3 pr-4 font-semibold">Terakhir Login</th>
                  <th class="py-3 pr-4 font-semibold">Hari</th>
                  <th class="py-3 pr-4 font-semibold">Hari Login (30 Hari)</th>
                  <th class="py-3 font-semibold">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="k in karyawanLoginUsersSorted"
                  :key="k.id"
                  class="border-b border-gray-100 dark:border-zinc-800/70"
                >
                  <td class="py-3 pr-4">
                    <div class="font-semibold text-gray-900 dark:text-white">
                      {{ k.name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      {{ k.email }}
                    </div>
                  </td>
                  <td
                    class="py-3 pr-4 text-gray-700 dark:text-gray-300 whitespace-nowrap"
                  >
                    <span v-if="k.last_login_at">{{
                      formatDateTime(k.last_login_at)
                    }}</span>
                    <span v-else class="text-gray-400 dark:text-gray-500">-</span>
                  </td>
                  <td
                    class="py-3 pr-4 text-gray-700 dark:text-gray-300 whitespace-nowrap"
                  >
                    <span v-if="k.days_since_last_login !== null">{{
                      k.days_since_last_login
                    }}</span>
                    <span v-else class="text-gray-400 dark:text-gray-500">-</span>
                  </td>
                  <td
                    class="py-3 pr-4 text-gray-700 dark:text-gray-300 whitespace-nowrap"
                  >
                    {{ k.login_days_30d ?? 0 }}
                  </td>
                  <td class="py-3 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                      :class="getLoginStatusClass(k.login_days_30d, k.last_login_at)"
                    >
                      {{ getLoginStatusLabel(k.login_days_30d, k.last_login_at) }}
                    </span>
                  </td>
                </tr>
                <tr v-if="karyawanLoginUsersSorted.length === 0">
                  <td
                    colspan="5"
                    class="py-6 text-center text-gray-500 dark:text-gray-400"
                  >
                    Data login belum tersedia
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <DashboardJobdesk
      v-if="user && user?.position === 'Staff'"
      :datas="dataJobdesk"
    />
    <PostsDashboard />
  </div>
</template>

<script lang="ts" setup>
definePageMeta({ title: "Dashboard" });
const client = useSanctumClient();

// Date filter states
const dateFrom = ref(
  formatDate(new Date(new Date().setMonth(new Date().getMonth() - 1))),
);
const dateTo = ref(formatDate(new Date()));
const dateFromCalendar = ref(
  new Date(new Date().setMonth(new Date().getMonth() - 1)),
);
const dateToCalendar = ref(new Date());
const activeQuickFilter = ref("month");

// Helper function to format date to YYYY-MM-DD
function formatDate(date: Date): string {
  return date.toISOString().split("T")[0];
}

// Helper function to get start of week (Monday)
function getStartOfWeek(date: Date): Date {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day + (day === 0 ? -6 : 1);
  return new Date(d.setDate(diff));
}

// Quick filter functions
const setQuickFilter = (filter: string) => {
  activeQuickFilter.value = filter;
  const now = new Date();

  switch (filter) {
    case "today":
      dateFromCalendar.value = new Date(now);
      dateToCalendar.value = new Date(now);
      dateFrom.value = formatDate(now);
      dateTo.value = formatDate(now);
      break;
    case "yesterday":
      const yesterday = new Date(now.getTime() - 24 * 60 * 60 * 1000);
      dateFromCalendar.value = new Date(yesterday);
      dateToCalendar.value = new Date(yesterday);
      dateFrom.value = formatDate(yesterday);
      dateTo.value = formatDate(yesterday);
      break;
    case "week":
      const startOfWeek = getStartOfWeek(now);
      dateFromCalendar.value = new Date(startOfWeek);
      dateToCalendar.value = new Date(now);
      dateFrom.value = formatDate(startOfWeek);
      dateTo.value = formatDate(now);
      break;
    case "month":
      const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
      dateFromCalendar.value = new Date(startOfMonth);
      dateToCalendar.value = new Date(now);
      dateFrom.value = formatDate(startOfMonth);
      dateTo.value = formatDate(now);
      break;
    case "6months":
      const sixMonthsAgo = new Date(now.getFullYear(), now.getMonth() - 6, 1);
      dateFromCalendar.value = new Date(sixMonthsAgo);
      dateToCalendar.value = new Date(now);
      dateFrom.value = formatDate(sixMonthsAgo);
      dateTo.value = formatDate(now);
      break;
    case "year":
      const startOfYear = new Date(now.getFullYear(), 0, 1);
      dateFromCalendar.value = new Date(startOfYear);
      dateToCalendar.value = new Date(now);
      dateFrom.value = formatDate(startOfYear);
      dateTo.value = formatDate(now);
      break;
  }
};

// Watch for Calendar component changes and sync with string dates
watch(dateFromCalendar, (newDate) => {
  if (newDate) {
    dateFrom.value = formatDate(newDate);
  }
});

watch(dateToCalendar, (newDate) => {
  if (newDate) {
    dateTo.value = formatDate(newDate);
  }
});

const user = useSanctumUser() as any;
const chartData = ref() as any;
const chartOptions = ref(null) as any;
const barChartData = ref() as any;
const barChartOptions = ref(null) as any;
const trendChartData = ref() as any;
const trendChartOptions = ref(null) as any;
const jobdeskTrendChartData = ref() as any;
const jobdeskTrendChartOptions = ref(null) as any;
const karyawanLoginTopChartData = ref(null) as any;
const karyawanLoginTopChartOptions = ref(null) as any;
const dataJobdesk = ref([] as any);

// Helper function untuk format currency
const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount);
};

const formatDateTime = (value: string | Date) => {
  const dateObject = new Date(value);
  if (isNaN(dateObject.getTime())) return "-";
  return dateObject.toLocaleString("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const isAdminUser = computed(() => {
  const u: any = user?.value || user;
  if (!u) return false;
  if (u.is_admin === "1" || u.is_admin === 1 || u.is_admin === true)
    return true;
  if (Array.isArray(u.role) && u.role.includes("admin")) return true;
  return false;
});

const karyawanLoginUsersSorted = computed(() => {
  const list = (data.value as any)?.karyawan_login_activity?.users;
  if (!Array.isArray(list)) return [];
  return [...list].sort((a: any, b: any) => {
    const aDays =
      a?.days_since_last_login === null ||
      a?.days_since_last_login === undefined
        ? Number.POSITIVE_INFINITY
        : Number(a.days_since_last_login);
    const bDays =
      b?.days_since_last_login === null ||
      b?.days_since_last_login === undefined
        ? Number.POSITIVE_INFINITY
        : Number(b.days_since_last_login);
    if (bDays !== aDays) return bDays - aDays;
    return String(a?.name || "").localeCompare(String(b?.name || ""));
  });
});

const getLoginStatusLabel = (loginDays30d: number | null, lastLoginAt: string | null) => {
  if (!lastLoginAt) return "Belum Pernah";
  const days = Number(loginDays30d || 0);
  if (days >= 25) return "Rajin";
  if (days < 10) return "Tidak Rajin";
  return "Cukup";
};

const getLoginStatusClass = (loginDays30d: number | null, lastLoginAt: string | null) => {
  if (!lastLoginAt) {
    return "bg-gray-100 text-gray-700 dark:bg-zinc-800 dark:text-zinc-300";
  }
  const days = Number(loginDays30d || 0);
  if (days >= 25) {
    return "bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300";
  }
  if (days < 10) {
    return "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300";
  }
  return "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300";
};
const setChartData = (dashboardData: any) => {
  return {
    labels: ["Masuk", "Proses", "Selesai"],
    datasets: [
      {
        data: [
          dashboardData?.total_jobdesks?.Masuk || 0,
          dashboardData?.total_jobdesks?.Progress || 0,
          dashboardData?.total_jobdesks?.Selesai || 0,
        ],
        backgroundColor: [
          "rgba(239, 68, 68, 0.8)", // red-500 with transparency
          "rgba(251, 146, 60, 0.8)", // orange-500 with transparency
          "rgba(34, 197, 94, 0.8)", // green-500 with transparency
        ],
        borderColor: [
          "rgb(239, 68, 68)", // red-500
          "rgb(251, 146, 60)", // orange-500
          "rgb(34, 197, 94)", // green-500
        ],
        borderWidth: 3,
        hoverBackgroundColor: [
          "rgba(248, 113, 113, 0.9)", // red-400 with transparency
          "rgba(251, 146, 60, 0.9)", // orange-400 with transparency
          "rgba(74, 222, 128, 0.9)", // green-400 with transparency
        ],
        hoverBorderWidth: 4,
        offset: [8, 8, 8], // Slightly separate each slice
      },
    ],
  };
};

const setBarChartData = (dashboardData: any) => {
  return {
    labels: ["Masuk", "Proses", "Selesai"],
    datasets: [
      {
        label: "Jumlah Jobdesk",
        data: [
          dashboardData?.total_jobdesks?.Masuk || 0,
          dashboardData?.total_jobdesks?.Progress || 0,
          dashboardData?.total_jobdesks?.Selesai || 0,
        ],
        backgroundColor: [
          "rgba(239, 68, 68, 0.8)", // red-500
          "rgba(251, 146, 60, 0.8)", // orange-500
          "rgba(34, 197, 94, 0.8)", // green-500
        ],
        borderColor: [
          "rgb(239, 68, 68)", // red-500
          "rgb(251, 146, 60)", // orange-500
          "rgb(34, 197, 94)", // green-500
        ],
        borderWidth: 2,
        borderRadius: 6,
        borderSkipped: false,
      },
    ],
  };
};

const setChartOptions = () => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        backgroundColor: "rgba(15, 23, 42, 0.95)", // slate-900 with transparency
        titleColor: "#f8fafc",
        bodyColor: "#f1f5f9",
        titleFont: {
          size: 14,
          weight: "600",
        },
        bodyFont: {
          size: 13,
          weight: "500",
        },
        borderColor: "rgba(148, 163, 184, 0.3)",
        borderWidth: 1,
        cornerRadius: 12,
        padding: 12,
        displayColors: true,
        boxWidth: 12,
        boxHeight: 12,
        usePointStyle: true,
        callbacks: {
          label: function (context: any) {
            const total = context.dataset.data.reduce(
              (a: number, b: number) => a + b,
              0,
            );
            const percentage = ((context.parsed / total) * 100).toFixed(1);
            return `${context.label}: ${context.parsed} (${percentage}%)`;
          },
        },
      },
    },
    cutout: "65%", // Slightly larger cutout for more modern look
    elements: {
      arc: {
        borderWidth: 0, // Remove border for cleaner look
        borderRadius: 8, // Rounded edges
        spacing: 4, // Space between slices
      },
    },
    animation: {
      animateRotate: true,
      animateScale: true,
      duration: 1000,
      easing: "easeOutQuart",
    },
    interaction: {
      intersect: false,
      mode: "nearest",
    },
  };
};

const setBarChartOptions = () => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        backgroundColor: "rgba(15, 23, 42, 0.95)",
        titleColor: "#f8fafc",
        bodyColor: "#f1f5f9",
        titleFont: {
          size: 14,
          weight: "600",
        },
        bodyFont: {
          size: 13,
          weight: "500",
        },
        borderColor: "rgba(148, 163, 184, 0.3)",
        borderWidth: 1,
        cornerRadius: 12,
        padding: 12,
        callbacks: {
          label: function (context: any) {
            const total = context.dataset.data.reduce(
              (a: number, b: number) => a + b,
              0,
            );
            const percentage = ((context.parsed.y / total) * 100).toFixed(1);
            return `${context.label}: ${context.parsed.y} (${percentage}%)`;
          },
        },
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: "rgba(148, 163, 184, 0.1)",
        },
        ticks: {
          color: "rgba(100, 116, 139, 0.8)",
          font: {
            size: 11,
          },
          stepSize: 1,
        },
      },
      x: {
        grid: {
          display: false,
        },
        ticks: {
          color: "rgba(100, 116, 139, 0.8)",
          font: {
            size: 11,
            weight: "500",
          },
        },
      },
    },
    animation: {
      duration: 1000,
      easing: "easeOutQuart",
    },
    interaction: {
      intersect: false,
      mode: "index",
    },
  };
};

const setKaryawanLoginTopChartData = (dashboardData: any) => {
  const users = dashboardData?.karyawan_login_activity?.users;
  if (!Array.isArray(users)) return null;
  const top = [...users]
    .sort(
      (a: any, b: any) =>
        Number(b?.login_days_30d || 0) - Number(a?.login_days_30d || 0),
    )
    .slice(0, 10)
    .reverse();
  return {
    labels: top.map((u: any) => u?.name || ""),
    datasets: [
      {
        label: "Hari Login (30 hari)",
        data: top.map((u: any) => Number(u?.login_days_30d || 0)),
        backgroundColor: "rgba(16, 185, 129, 0.8)",
        borderColor: "rgb(16, 185, 129)",
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false,
      },
    ],
  };
};

const setKaryawanLoginTopChartOptions = () => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: "y",
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: "rgba(17, 24, 39, 0.95)",
        titleColor: "#fff",
        bodyColor: "#fff",
        borderColor: "rgba(75, 85, 99, 0.3)",
        borderWidth: 1,
        cornerRadius: 12,
        padding: 12,
      },
    },
    scales: {
      x: {
        beginAtZero: true,
        grid: { color: "rgba(148, 163, 184, 0.12)" },
        ticks: { stepSize: 1, color: "rgba(100, 116, 139, 0.8)" },
      },
      y: {
        grid: { display: false },
        ticks: { color: "rgba(100, 116, 139, 0.8)" },
      },
    },
  };
};
// Move this after data initialization
const { data, pending, error, refresh } = await useAsyncData("dashboard", () =>
  client("/api/home", {
    params: {
      date_from: dateFrom.value,
      date_to: dateTo.value,
    },
  }),
);

const calculateTrend = (current: number, previous: number) => {
  if (!previous || previous === 0) return 0;
  return Math.round(((current - previous) / previous) * 100);
};

const setTrendChartData = (dashboardData: any) => {
  if (!dashboardData?.monthly_trend) return null;

  const labels = dashboardData.monthly_trend.map(
    (item: any) => item.month_name,
  );

  return {
    labels: labels,
    datasets: [
      {
        label: "Pendapatan",
        data: dashboardData.monthly_trend.map((item: any) => item.revenue),
        borderColor: "rgb(16, 185, 129)",
        backgroundColor: "rgba(16, 185, 129, 0.1)",
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointRadius: 6,
        pointHoverRadius: 8,
        pointBackgroundColor: "rgb(16, 185, 129)",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
      },
      {
        label: "Tagihan",
        data: dashboardData.monthly_trend.map((item: any) => item.billing),
        borderColor: "rgb(249, 115, 22)",
        backgroundColor: "rgba(249, 115, 22, 0.1)",
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointRadius: 6,
        pointHoverRadius: 8,
        pointBackgroundColor: "rgb(249, 115, 22)",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
      },
    ],
  };
};

const setTrendChartOptions = () => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: "index",
      intersect: false,
    },
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        backgroundColor: "rgba(17, 24, 39, 0.95)",
        titleColor: "#fff",
        bodyColor: "#fff",
        borderColor: "rgba(75, 85, 99, 0.3)",
        borderWidth: 1,
        cornerRadius: 12,
        padding: 16,
        titleFont: {
          size: 14,
          weight: "600",
        },
        bodyFont: {
          size: 13,
          weight: "500",
        },
        callbacks: {
          label: function (context: any) {
            return `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`;
          },
        },
      },
    },
    scales: {
      x: {
        grid: {
          display: false,
        },
        ticks: {
          color: "rgba(107, 114, 128, 0.8)",
          font: {
            size: 12,
            weight: "500",
          },
        },
      },
      y: {
        border: {
          display: false,
        },
        grid: {
          color: "rgba(229, 231, 235, 0.5)",
          drawBorder: false,
        },
        ticks: {
          color: "rgba(107, 114, 128, 0.8)",
          font: {
            size: 12,
          },
          callback: function (value: any) {
            return (value / 1000000).toFixed(0) + "M";
          },
        },
      },
    },
    elements: {
      point: {
        hoverBackgroundColor: "#fff",
      },
    },
    animation: {
      duration: 1500,
      easing: "easeInOutQuart",
    },
  };
};

const setJobdeskTrendChartData = (dashboardData: any) => {
  if (!dashboardData?.jobdesk_trend) return null;

  const labels = dashboardData.jobdesk_trend.map((item: any) => {
    const date = new Date(item.date);
    return date.toLocaleDateString("id-ID", { day: "2-digit", month: "short" });
  });

  return {
    labels: labels,
    datasets: [
      {
        label: "Masuk",
        data: dashboardData.jobdesk_trend.map((item: any) => item.masuk),
        borderColor: "rgb(239, 68, 68)",
        backgroundColor: "rgba(239, 68, 68, 0.1)",
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointBackgroundColor: "rgb(239, 68, 68)",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
      },
      {
        label: "Proses",
        data: dashboardData.jobdesk_trend.map((item: any) => item.progress),
        borderColor: "rgb(249, 115, 22)",
        backgroundColor: "rgba(249, 115, 22, 0.1)",
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointBackgroundColor: "rgb(249, 115, 22)",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
      },
      {
        label: "Selesai",
        data: dashboardData.jobdesk_trend.map((item: any) => item.selesai),
        borderColor: "rgb(34, 197, 94)",
        backgroundColor: "rgba(34, 197, 94, 0.1)",
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointBackgroundColor: "rgb(34, 197, 94)",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
      },
    ],
  };
};

const setJobdeskTrendChartOptions = () => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: "index",
      intersect: false,
    },
    plugins: {
      legend: {
        display: true,
        position: "top",
        labels: {
          usePointStyle: true,
          pointStyle: "circle",
          padding: 20,
          font: {
            size: 12,
            weight: "500",
          },
        },
      },
      tooltip: {
        backgroundColor: "rgba(17, 24, 39, 0.95)",
        titleColor: "#fff",
        bodyColor: "#fff",
        borderColor: "rgba(75, 85, 99, 0.3)",
        borderWidth: 1,
        cornerRadius: 12,
        padding: 12,
        titleFont: {
          size: 14,
          weight: "600",
        },
        bodyFont: {
          size: 13,
          weight: "500",
        },
        callbacks: {
          label: function (context: any) {
            return `${context.dataset.label}: ${context.parsed.y} jobdesk`;
          },
        },
      },
    },
    scales: {
      x: {
        grid: {
          display: false,
        },
        ticks: {
          color: "rgba(107, 114, 128, 0.8)",
          font: {
            size: 11,
            weight: "500",
          },
          maxTicksLimit: 10,
        },
      },
      y: {
        border: {
          display: false,
        },
        grid: {
          color: "rgba(229, 231, 235, 0.5)",
          drawBorder: false,
        },
        ticks: {
          color: "rgba(107, 114, 128, 0.8)",
          font: {
            size: 11,
          },
          stepSize: 1,
          beginAtZero: true,
        },
      },
    },
    elements: {
      point: {
        hoverBackgroundColor: "#fff",
      },
    },
    animation: {
      duration: 1200,
      easing: "easeInOutQuart",
    },
  };
};

const totalJobdesk = computed(() => {
  if (!data.value?.total_jobdesks) return 0;
  return Object.values(data.value.total_jobdesks).reduce(
    (acc: number, curr: unknown) => {
      return acc + (typeof curr === "number" ? curr : 0);
    },
    0,
  );
});

// Watch for date changes and refresh data
watch(
  [dateFrom, dateTo],
  () => {
    refresh();
  },
  { immediate: false },
);

// Watch for data changes and update charts
watch(
  data,
  () => {
    if (data.value) {
      chartData.value = setChartData(data.value);
      barChartData.value = setBarChartData(data.value);
      trendChartData.value = setTrendChartData(data.value);
      jobdeskTrendChartData.value = setJobdeskTrendChartData(data.value);
      karyawanLoginTopChartData.value = setKaryawanLoginTopChartData(
        data.value,
      );
    }
  },
  { deep: true },
);

// Computed property untuk label filter dinamis
const filterLabel = computed(() => {
  switch (activeQuickFilter.value) {
    case "today":
      return "Hari Ini";
    case "yesterday":
      return "Kemarin";
    case "week":
      return "Minggu Ini";
    case "month":
      return "Bulan Ini";
    case "6months":
      return "6 Bulan";
    case "year":
      return "1 Tahun";
    default:
      return "Periode";
  }
});

onMounted(async () => {
  if (data.value) {
    chartData.value = setChartData(data.value) as any;
    barChartData.value = setBarChartData(data.value) as any;
    trendChartData.value = setTrendChartData(data.value) as any;
    jobdeskTrendChartData.value = setJobdeskTrendChartData(data.value) as any;
    karyawanLoginTopChartData.value = setKaryawanLoginTopChartData(
      data.value,
    ) as any;
  }
  chartOptions.value = setChartOptions() as any;
  barChartOptions.value = setBarChartOptions() as any;
  trendChartOptions.value = setTrendChartOptions() as any;
  jobdeskTrendChartOptions.value = setJobdeskTrendChartOptions() as any;
  karyawanLoginTopChartOptions.value = setKaryawanLoginTopChartOptions() as any;

  try {
    const response = await client(`/api/karyawans/${user.value.id}`);
    dataJobdesk.value = response;
  } catch (error) {
    console.log(error);
  }
});
</script>
