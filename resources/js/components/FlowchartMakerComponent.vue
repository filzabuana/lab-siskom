<template>
  <div 
    ref="flowContainer"
    class="flex flex-col h-full w-full outline-none relative main-container font-inter text-slate-800" 
    @keydown="handleKeydown" 
    tabindex="0"
  >
    <div class="absolute top-4 left-4 z-50 flex flex-wrap gap-2 p-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800">
      <button @click="addNode('start')" type="button" class="btn-tool" :class="themes[currentTheme].start">
        <i class="bi bi-play-circle me-1"></i> Start
      </button>
      <button @click="addNode('process')" type="button" class="btn-tool" :class="themes[currentTheme].process">
        <i class="bi bi-square me-1"></i> Proses
      </button>
      <button @click="addNode('decision')" type="button" class="btn-tool" :class="themes[currentTheme].decision">
        <i class="bi bi-diamond me-1"></i> Opsi
      </button>
      <button @click="addNode('io')" type="button" class="btn-tool" :class="themes[currentTheme].io">
        <i class="bi bi-input-cursor-text me-1"></i> Data
      </button>
      
      <div class="w-[1px] h-8 bg-slate-300 dark:bg-slate-800 mx-1"></div>
      
      <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl gap-1">
        <button type="button" @click="edgeType = 'default'" :class="['px-2 py-1 rounded-lg text-[10px] font-bold transition-all', edgeType === 'default' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500']" title="Lengkung">
          <i class="bi bi-bezier2"></i>
        </button>
        <button type="button" @click="edgeType = 'step'" :class="['px-2 py-1 rounded-lg text-[10px] font-bold transition-all', edgeType === 'step' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500']" title="Siku">
          <i class="bi bi-arrow-90deg-down"></i>
        </button>
        <button type="button" @click="edgeType = 'straight'" :class="['px-2 py-1 rounded-lg text-[10px] font-bold transition-all', edgeType === 'straight' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500']" title="Lurus">
          <i class="bi bi-arrow-up-right"></i>
        </button>
      </div>

      <div class="w-[1px] h-8 bg-slate-300 dark:bg-slate-800 mx-1"></div>

      <select v-model="currentTheme" class="text-[10px] bg-slate-100 dark:bg-slate-800 dark:text-white border-none rounded-lg focus:ring-0 cursor-pointer font-bold px-2">
        <option value="modern">Modern</option>
        <option value="ocean">Ocean</option>
        <option value="forest">Forest</option>
      </select>

      <button @click="removeSelected" type="button" class="btn-tool bg-red-500 hover:bg-red-600 border-none">
        <i class="bi bi-trash"></i>
      </button>
    </div>

    <Transition name="fade">
      <div 
        v-if="edgeEdit.active" 
        :style="edgeEdit.style" 
        class="absolute z-[100] bg-white dark:bg-slate-800 p-2 rounded-xl shadow-2xl border-2 border-blue-500 flex gap-2 items-center"
      >
        <input 
          ref="edgeInputRef"
          v-model="edgeEdit.value" 
          class="bg-transparent border-none outline-none text-sm font-bold dark:text-white w-32 px-1" 
          @keyup.enter="saveEdgeLabel"
          @keyup.esc="closeEdgeEdit"
        />
        <button type="button" @click="saveEdgeLabel" class="text-emerald-500 hover:scale-110">
          <i class="bi bi-check-circle-fill text-lg"></i>
        </button>
      </div>
    </Transition>

    <VueFlow
      v-model="elements"
      :default-viewport="{ x: 0, y: 0, zoom: 1.2 }"
      :min-zoom="0.1"
      :max-zoom="4"
      :delete-key-code="null" 
      class="bg-slate-50 dark:bg-[#0b1120]"
      @connect="onConnect"
      @edge-click="onEdgeClick"
      @edge-double-click="onEdgeDoubleClick"
      @pane-click="closeEdgeEdit"
    >
      <Controls position="bottom-right" />
      <Background pattern-color="#cbd5e1" :gap="25" />

      <template #node-custom="{ data, selected }">
        <div :class="['flow-node-base', getNodeClass(data.shape), selected ? 'node-selected' : '']">
          <div v-if="selected" @click.stop="removeNode(data.id)" class="btn-delete-node">
             <i class="bi bi-x-lg text-[10px]"></i>
          </div>

          <template v-if="data.shape === 'start'">
            <Handle id="s-b" type="source" position="bottom" class="custom-handle" />
          </template>

          <template v-else-if="data.shape === 'decision'">
            <Handle id="d-t" type="target" position="top" class="handle-diamond h-d-top" />
            <Handle id="d-r" type="source" position="right" class="handle-diamond h-d-right" />
            <Handle id="d-b" type="source" position="bottom" class="handle-diamond h-d-bottom" />
            <Handle id="d-l" type="source" position="left" class="handle-diamond h-d-left" />
          </template>

          <template v-else-if="data.shape === 'io'">
            <Handle id="i-t" type="target" position="top" class="handle-io h-i-top" />
            <Handle id="i-b" type="source" position="bottom" class="handle-io h-i-bottom" />
            <Handle id="i-r" type="source" position="right" class="handle-io h-i-right" />
            <Handle id="i-l" type="source" position="left" class="handle-io h-i-left" />
          </template>

          <template v-else>
            <Handle id="g-t" type="target" position="top" class="custom-handle" />
            <Handle id="g-b" type="source" position="bottom" class="custom-handle" />
            <Handle id="g-r" type="source" position="right" class="custom-handle" />
            <Handle id="g-l" type="source" position="left" class="custom-handle" />
          </template>
          
          <div class="node-content-wrapper">
            <textarea 
                v-model="data.label" 
                rows="1"
                @input="autoResize"
                @change="updateLaravelInput"
                class="node-input nodrag nopan font-inter"
                placeholder="..."
            ></textarea>
          </div>
        </div>
      </template>
    </VueFlow>
    
    <div class="absolute bottom-6 left-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 dark:text-slate-200 pointer-events-none bg-white/80 dark:bg-slate-900/80 px-5 py-2 rounded-full border-2 border-slate-200 dark:border-slate-700 shadow-2xl">
        Klik Garis 1x: Label • 2x: Hapus • Del: Hapus Node
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { VueFlow, useVueFlow, Handle, MarkerType } from '@vue-flow/core'
import { Background } from '@vue-flow/background'
import { Controls } from '@vue-flow/controls'

import '@vue-flow/core/dist/style.css'
import '@vue-flow/core/dist/theme-default.css'
import '@vue-flow/controls/dist/style.css'

const { addEdges, toObject, removeNodes, removeEdges, getSelectedNodes, getSelectedEdges } = useVueFlow()

const flowContainer = ref(null)
const edgeInputRef = ref(null)
const currentTheme = ref('modern')
const edgeType = ref('default') 

const elements = ref([
  { id: 'node_1', type: 'custom', data: { id: 'node_1', label: 'Mulai', shape: 'start' }, position: { x: 250, y: 50 } },
])

const edgeEdit = ref({ active: false, value: '', edge: null, style: {} })

const themes = {
  modern: { start: 'bg-emerald-500', process: 'bg-blue-600', decision: 'bg-amber-500', io: 'bg-slate-700' },
  ocean: { start: 'bg-cyan-600', process: 'bg-sky-500', decision: 'bg-indigo-500', io: 'bg-slate-600' },
  forest: { start: 'bg-green-700', process: 'bg-lime-600', decision: 'bg-orange-500', io: 'bg-stone-600' }
}

const handleKeydown = (e) => {
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
  if (e.key === 'Delete') removeSelected();
}

const onEdgeClick = ({ event, edge }) => {
  edgeEdit.value = {
    active: true, value: edge.label || '', edge: edge,
    style: { left: `${event.clientX - 80}px`, top: `${event.clientY - 50}px` }
  }
  nextTick(() => edgeInputRef.value?.focus())
}

const onEdgeDoubleClick = ({ edge }) => {
  closeEdgeEdit();
  removeEdges([edge.id]);
  updateLaravelInput();
}

const saveEdgeLabel = () => {
  if (edgeEdit.value.edge) {
    edgeEdit.value.edge.label = edgeEdit.value.value;
    updateLaravelInput();
  }
  closeEdgeEdit();
}

const closeEdgeEdit = () => {
  edgeEdit.value.active = false;
  nextTick(() => flowContainer.value?.focus());
}

const removeSelected = () => {
  removeNodes(getSelectedNodes.value);
  removeEdges(getSelectedEdges.value);
  updateLaravelInput();
}

const removeNode = (id) => {
  removeNodes([id]);
  updateLaravelInput();
}

const onConnect = (params) => {
  addEdges([{ 
    ...params, 
    type: edgeType.value, 
    label: '',
    labelStyle: { fill: '#1e293b', fontWeight: 800, fontSize: '11px', fontFamily: 'Inter' },
    labelBgStyle: { fill: '#ffffff', fillOpacity: 0.95 },
    markerEnd: MarkerType.ArrowClosed,
    style: { strokeWidth: 2.5, stroke: '#64748b' } 
  }])
  updateLaravelInput()
}

const addNode = (shapeType) => {
  const id = `node_${Date.now()}`
  elements.value.push({
    id, type: 'custom', data: { id, label: '...', shape: shapeType },
    position: { x: 300, y: 200 },
  })
  updateLaravelInput()
}

const updateLaravelInput = () => {
  const hiddenInput = document.getElementById('flowchart_data_input')
  if (hiddenInput) hiddenInput.value = JSON.stringify(toObject())
}

const autoResize = (e) => {
  e.target.style.height = 'auto';
  e.target.style.height = e.target.scrollHeight + 'px';
}

const getNodeClass = (shape) => {
  const theme = themes[currentTheme.value];
  const classes = {
    start: `rounded-full ${theme.start} min-w-[140px]`,
    process: `rounded-none ${theme.process} min-w-[160px]`,
    decision: `shape-diamond ${theme.decision} !w-[100px] !h-[100px]`,
    io: `shape-io ${theme.io} min-w-[160px]`
  }
  return classes[shape] || classes.process
}

onMounted(() => {
  updateLaravelInput();
  flowContainer.value?.focus();
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');

.font-inter { font-family: 'Inter', sans-serif !important; }

.flow-node-base {
  @apply flex items-center justify-center p-4 border-2 shadow-xl relative transition-all duration-300;
  color: white;
}

.node-selected { @apply ring-4 ring-blue-500/40 border-blue-500 scale-[1.02] z-40; }

.node-content-wrapper { @apply w-full h-full flex items-center justify-center z-10; }

/* SHAPES */
.shape-diamond { transform: rotate(45deg); }
.shape-diamond .node-input { transform: rotate(-45deg); }
.shape-io { transform: skewX(-15deg); }
.shape-io .node-input { transform: skewX(15deg); }

/* HANDLES */
:deep(.vue-flow__handle) {
  @apply !bg-white border-2 border-slate-500 !w-3.5 !h-3.5 hover:!scale-150 transition-all z-[100];
}

/* DIAMOND HANDLES */
.handle-diamond { @apply !absolute !bg-white !border-slate-700; }
.h-d-top { top: 0 !important; left: 0 !important; transform: translate(-50%, -50%) rotate(-45deg) !important; }
.h-d-right { top: 0 !important; left: 100% !important; transform: translate(-50%, -50%) rotate(-45deg) !important; }
.h-d-bottom { top: 100% !important; left: 100% !important; transform: translate(-50%, -50%) rotate(-45deg) !important; }
.h-d-left { top: 100% !important; left: 0 !important; transform: translate(-50%, -50%) rotate(-45deg) !important; }

/* IO HANDLES */
.handle-io { @apply !absolute !bg-white !border-slate-700; }
.h-i-top { top: 0 !important; left: calc(50% + 8px) !important; transform: translate(-50%, -50%) !important; }
.h-i-bottom { bottom: 0 !important; left: calc(50% - 8px) !important; transform: translate(-50%, 50%) !important; }
.h-i-right { right: 0 !important; top: 50% !important; transform: translate(50%, -50%) !important; }
.h-i-left { left: 0 !important; top: 50% !important; transform: translate(-50%, -50%) !important; }

.node-input {
  @apply bg-transparent border-none text-center outline-none w-full text-[11px] font-bold p-0 text-white resize-none overflow-hidden tracking-tight;
}

.btn-tool {
  @apply px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all active:scale-95 shadow-lg border border-white/10;
}

.btn-delete-node {
  @apply absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center cursor-pointer shadow-xl z-[110] border-2 border-white;
  transform: rotate(0deg) !important;
}

:deep(.vue-flow__edge-label) {
  @apply font-black text-slate-900 bg-white shadow-xl border-2 border-slate-300 px-3 py-1 rounded-lg cursor-pointer;
}
</style>