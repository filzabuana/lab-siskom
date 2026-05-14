<template>
  <div :class="nodeClasses">
    <!-- Port untuk koneksi (Atas) -->
    <Handle type="target" position="top" class="!bg-slate-400" />
    
    <div class="px-4 py-2 text-center text-xs font-bold leading-tight uppercase tracking-tighter">
      {{ data.label }}
    </div>

    <!-- Port untuk koneksi (Bawah) -->
    <Handle type="source" position="bottom" class="!bg-slate-400" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Handle } from '@vue-flow/core'

const props = defineProps(['data', 'type'])

const nodeClasses = computed(() => {
  const base = 'min-w-[120px] shadow-lg transition-all duration-300 border-2 '
  
  const styles = {
    // Mulai / Selesai (Rounded)
    start: 'rounded-full bg-emerald-500 border-emerald-600 text-white',
    // Proses (Persegi)
    process: 'rounded-none bg-blue-600 border-blue-700 text-white',
    // Keputusan (Belah Ketupat - Kita gunakan CSS clip-path atau rotasi)
    decision: 'bg-amber-500 border-amber-600 text-white transform rotate-45 !w-[100px] !h-[100px] flex items-center justify-center',
    // Input/Output (Jajaran Genjang)
    io: 'bg-slate-700 border-slate-800 text-white [transform:skew(-15deg)]',
  }

  return base + (styles[props.data.shape] || styles.process)
})
</script>

<style scoped>
/* Untuk teks di dalam node decision agar tidak ikut miring */
.rotate-45 > div {
  transform: rotate(-45deg);
}
</style>