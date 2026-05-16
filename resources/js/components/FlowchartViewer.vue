<template>
  <div class="flex flex-col w-full bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-xl min-h-[600px]">
    
    <div v-if="initialData && initialData.length > 1" class="flex items-center gap-2 p-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
      <button 
        v-for="(tab, index) in initialData" 
        :key="tab.id"
        @click="activeTabIndex = index"
        :class="[
          'px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 border-2',
          activeTabIndex === index 
            ? 'bg-blue-600 text-white border-blue-400 shadow-lg' 
            : 'bg-white dark:bg-slate-800 text-slate-400 border-transparent hover:border-slate-300'
        ]"
      >
        <i class="bi bi-diagram-3"></i>
        {{ tab.name }}
      </button>
    </div>

    <div class="flex-grow relative h-[600px]">
      <VueFlow
        v-model="initialData[activeTabIndex].elements"
        :nodes-draggable="true"
        :nodes-connectable="false"
        :elements-selectable="true"
        :fit-view-on-init="true"
        :min-zoom="0.2"
        :max-zoom="2"
        class="bg-slate-50 dark:bg-[#0b1120]"
      >
        <Background pattern-color="#cbd5e1" :gap="25" />
        <Controls position="bottom-right" :show-interactive="false" />

        <template #node-custom="{ data }">
          <div :class="['flow-node-viewer cursor-grab active:cursor-grabbing flex items-center justify-center', getNodeClass(data.shape)]">
            
            <template v-if="data.shape === 'decision'">
              <Handle id="d-t" type="target" position="top" class="h-fixed h-d-top invisible" />
              <Handle id="d-r" type="source" position="right" class="h-fixed h-d-right invisible" />
              <Handle id="d-b" type="source" position="bottom" class="h-fixed h-d-bottom invisible" />
              <Handle id="d-l" type="source" position="left" class="h-fixed h-d-left invisible" />
            </template>

            <template v-else>
              <Handle id="h-t" type="target" position="top" class="h-fixed h-std-v-lock invisible" />
              <Handle id="h-b" type="source" position="bottom" class="h-fixed h-std-v-lock invisible" />
              <Handle id="h-r" type="source" position="right" class="h-fixed h-std-h-lock invisible" />
              <Handle id="h-l" type="source" position="left" class="h-fixed h-std-h-lock invisible" />
            </template>

            <div class="node-content-wrapper">
              <span class="node-text font-inter whitespace-pre-wrap">{{ data.label }}</span>
            </div>
          </div>
        </template>
      </VueFlow>

      <div class="absolute top-6 right-6 px-4 py-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-full border border-slate-200 dark:border-slate-700 shadow-sm pointer-events-none">
        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">View Mode (Draggable)</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { VueFlow, Handle } from '@vue-flow/core'
import { Background } from '@vue-flow/background'
import { Controls } from '@vue-flow/controls'

import '@vue-flow/core/dist/style.css'
import '@vue-flow/core/dist/theme-default.css'
import '@vue-flow/controls/dist/style.css'

const props = defineProps({
  initialData: { type: Array, default: () => [] }
})

const activeTabIndex = ref(0)

const getNodeClass = (shape) => {
  const classes = {
    start: 'rounded-full bg-emerald-500 w-[180px] h-[60px]',
    process: 'rounded-none bg-blue-600 w-[180px] h-[70px]',
    // Kita pakai 126px agar pembagian tengahnya genap (63px)
    decision: 'shape-diamond bg-amber-500 !w-[126px] !h-[126px]', 
    io: 'shape-io bg-slate-700 w-[180px] h-[70px]'
  }
  return classes[shape] || 'bg-blue-600 w-[180px] h-[70px]'
}
</script>

<style scoped>
.font-inter { font-family: 'Inter', sans-serif !important; }

.flow-node-viewer {
  @apply border-2 border-white/20 shadow-xl relative transition-shadow;
  color: white;
  box-sizing: border-box;
}

.node-content-wrapper { @apply w-full h-full flex items-center justify-center text-center p-2; }
.node-text { @apply text-[11px] font-bold leading-tight; }

/* SHAPES */
.shape-diamond { transform: rotate(45deg); }
.shape-diamond .node-text { transform: rotate(-45deg); }
.shape-io { transform: skewX(-15deg); }
.shape-io .node-text { transform: skewX(15deg); }

.invisible { opacity: 0; pointer-events: none; position: absolute; }

:deep(.vue-flow__handle) { @apply !bg-white border-2 border-slate-500 !w-3 !h-3; }

/* COORDINATE LOCKING SYSTEM */
.h-fixed { position: absolute !important; }

/* 1. Node Standar (180px) -> Tengah di 90px */
.h-std-v-lock { 
  left: 90px !important; 
  transform: translateX(-50%) !important; 
}
.h-std-h-lock { 
  top: 50% !important; 
  transform: translateY(-50%) !important; 
}

/* 2. Node Decision (126px di-rotate 45deg) */
/* Titik sudut atas visual = 0,0 box asli */
.h-d-top { top: 0% !important; left: 0% !important; transform: translate(-50%, -50%) !important; }
.h-d-right { top: 0% !important; left: 100% !important; transform: translate(-50%, -50%) !important; }
.h-d-bottom { top: 100% !important; left: 100% !important; transform: translate(-50%, -50%) !important; }
.h-d-left { top: 100% !important; left: 0% !important; transform: translate(-50%, -50%) !important; }

:deep(.vue-flow__edge-path) { stroke: #64748b; stroke-width: 2.5; }
:deep(.vue-flow__edge-label) { @apply font-black text-slate-900 bg-white shadow-md border border-slate-200 px-2 py-1 rounded-md text-[10px]; }
</style>