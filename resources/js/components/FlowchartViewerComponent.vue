<template>
  <div class="w-full bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
    
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
      <div class="flex gap-2 overflow-x-auto no-scrollbar">
        <button 
          v-for="(flow, index) in flowcharts" 
          :key="index"
          @click="activeTab = index"
          type="button"
          :class="[
            'px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap border-2',
            activeTab === index 
              ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-500/30' 
              : 'bg-white dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-blue-400'
          ]"
        >
          <i class="bi bi-diagram-3 me-2"></i> {{ flow.name || 'Flowchart ' + (index + 1) }}
        </button>
      </div>

      <div class="hidden md:block text-[10px] font-black text-slate-400 uppercase tracking-widest">
        Mode Lihat Saja
      </div>
    </div>

    <div class="h-[600px] w-full relative">
      <VueFlow
        v-model="activeFlowData"
        :nodes-draggable="false"
        :nodes-connectable="false"
        :elements-selectable="false"
        :fit-view-on-init="true"
        class="bg-slate-50 dark:bg-[#0b1120]"
      >
        <Background pattern-color="#cbd5e1" :gap="25" />
        <Controls position="bottom-right" />

        <template #node-custom="{ data }">
          <div :class="['flow-node-base viewer-node', getNodeClass(data.shape)]">
            <Handle type="target" position="top" style="opacity: 0" />
            <Handle type="source" position="bottom" style="opacity: 0" />
            
            <div class="node-content text-center font-inter font-bold text-[11px] leading-tight whitespace-pre-wrap p-2">
              {{ data.label }}
            </div>
          </div>
        </template>
      </VueFlow>

      <div class="absolute bottom-4 left-6 pointer-events-none opacity-20 dark:opacity-10">
          <h1 class="text-2xl font-black italic tracking-tighter text-slate-900 dark:text-white">FMIPA UNTAN</h1>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { VueFlow, Handle } from '@vue-flow/core'
import { Background } from '@vue-flow/background'
import { Controls } from '@vue-flow/controls'

// Import style (Pastikan sama dengan create)
import '@vue-flow/core/dist/style.css'
import '@vue-flow/core/dist/theme-default.css'

const props = defineProps({
  // Data dikirim dari Laravel Controller via props (Array of Flowcharts)
  initialData: {
    type: Array,
    required: true
  }
});

const flowcharts = ref(props.initialData);
const activeTab = ref(0);

// Data yang aktif berdasarkan tab yang dipilih
const activeFlowData = computed(() => {
  return flowcharts.value[activeTab.value]?.elements || [];
});

const getNodeClass = (shape) => {
  const classes = {
    start: 'rounded-full bg-emerald-500 border-emerald-400 text-white min-w-[120px]',
    process: 'rounded-none bg-blue-600 border-blue-400 text-white min-w-[140px]',
    decision: 'shape-diamond bg-amber-500 border-amber-400 text-white w-[90px] h-[90px]',
    io: 'shape-io bg-slate-700 border-slate-500 text-white min-w-[140px]'
  }
  return classes[shape] || 'bg-blue-600'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@700;800&display=swap');

.font-inter { font-family: 'Inter', sans-serif !important; }

.flow-node-base {
  @apply flex items-center justify-center border-2 shadow-lg relative;
  color: white;
}

/* Style Shape Sama dengan Create */
.shape-diamond { transform: rotate(45deg); }
.shape-diamond .node-content { transform: rotate(-45deg); }
.shape-io { transform: skewX(-15deg); }
.shape-io .node-content { transform: skewX(15deg); }

/* Viewer node tidak perlu hover effect berlebih */
.viewer-node { @apply transition-none; }

:deep(.vue-flow__edge-path) {
  @apply stroke-[2.5px] stroke-slate-400 dark:stroke-slate-600;
}

:deep(.vue-flow__edge-label) {
  @apply font-black text-[10px] text-slate-800 bg-white border border-slate-200 px-2 py-0.5 rounded-md;
}

.no-scrollbar::-webkit-scrollbar { display: none; }
</style>