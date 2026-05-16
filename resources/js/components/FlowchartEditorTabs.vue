<template>
  <div 
    ref="flowContainer"
    class="flex flex-col h-full w-full outline-none relative main-container font-inter text-slate-800 bg-white dark:bg-[#0b1120]" 
    @keydown="handleKeydown" 
    tabindex="0"
  >
    <div class="flex items-center gap-2 p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 z-[60]">
      <div v-for="(tab, index) in tabs" :key="tab.id" class="flex items-center group">
        <button 
          type="button"
          @click="switchTab(index)"
          :class="[
            'px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 border-2',
            activeTabIndex === index 
              ? 'bg-blue-600 text-white border-blue-400 shadow-lg' 
              : 'bg-white dark:bg-slate-800 text-slate-400 border-transparent hover:border-slate-300'
          ]"
        >
          <i class="bi bi-diagram-3"></i>
          <span>{{ tab.name }}</span>
        </button>
        <button 
          v-if="tabs.length > 1"
          type="button"
          @click.stop="removeTab(index)"
          class="ml-[-12px] z-10 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity border-2 border-white shadow-md"
        >
          <i class="bi bi-x text-[10px]"></i>
        </button>
      </div>
      
      <button type="button" @click="showTabModal = true" class="p-2 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600 transition-all shadow-lg active:scale-90">
        <i class="bi bi-plus-lg"></i>
      </button>

      <div class="flex-grow"></div>

      <button 
        type="button"
        @click="showScriptEditor = !showScriptEditor"
        :class="['px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 shadow-lg transition-all', showScriptEditor ? 'bg-red-500 text-white' : 'bg-slate-800 text-white']"
      >
        <i :class="showScriptEditor ? 'bi bi-x-circle' : 'bi bi-code-slash'"></i>
        {{ showScriptEditor ? 'Tutup Script' : 'Input Script' }}
      </button>
    </div>

    <Transition name="slide-down">
      <div v-if="showScriptEditor" class="absolute top-[73px] left-0 right-0 z-[55] p-6 bg-white dark:bg-slate-800 border-b-4 border-blue-500 shadow-2xl">
        <div class="flex justify-between items-end mb-3">
          <div class="flex flex-col gap-1">
            <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Script Parser v3.1</span>
            <p class="text-[9px] text-slate-400 uppercase font-bold italic">Parser otomatis menggunakan mode Siku (Step).</p>
          </div>
          <button type="button" @click="generateFromScript" class="px-6 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg hover:bg-blue-700 active:scale-95 transition-all">Render Flowchart</button>
        </div>
        <textarea 
          v-model="rawScript" 
          class="w-full h-40 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 font-mono text-xs outline-none focus:border-blue-500 text-slate-800 dark:text-slate-100" 
          placeholder="[Mulai] -> {Cek} --Label--> [Proses]"
        ></textarea>
      </div>
    </Transition>

    <div 
      class="absolute left-6 z-40 flex flex-wrap gap-2 p-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 transition-all duration-500"
      :style="{ top: showScriptEditor ? '340px' : '90px' }"
    >
      <button @click="processAddNode('start')" type="button" class="btn-tool" :class="themes[currentTheme].start"><i class="bi bi-play-circle me-1"></i> Start</button>
      <button @click="processAddNode('process')" type="button" class="btn-tool" :class="themes[currentTheme].process"><i class="bi bi-square me-1"></i> Proses</button>
      <button @click="processAddNode('decision')" type="button" class="btn-tool" :class="themes[currentTheme].decision"><i class="bi bi-diamond me-1"></i> Opsi</button>
      <button @click="processAddNode('io')" type="button" class="btn-tool" :class="themes[currentTheme].io"><i class="bi bi-input-cursor-text me-1"></i> Data</button>
      
      <div class="w-[1px] h-8 bg-slate-300 dark:bg-slate-800 mx-1"></div>
      
      <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl gap-1">
        <button v-for="t in ['default', 'step', 'straight']" :key="t" type="button" @click="edgeType = t" :class="['btn-edge', edgeType === t ? 'active' : '']">
          <i :class="t === 'default' ? 'bi bi-bezier2' : t === 'step' ? 'bi bi-arrow-90deg-down' : 'bi bi-arrow-up-right'"></i>
        </button>
      </div>

      <select v-model="currentTheme" class="theme-select">
        <option value="modern">Modern</option>
        <option value="ocean">Ocean</option>
        <option value="forest">Forest</option>
      </select>

      <button @click="removeSelected" type="button" class="btn-tool bg-red-500 hover:bg-red-600 border-none"><i class="bi bi-trash"></i></button>
    </div>

    <Transition name="fade">
      <div v-if="edgeEdit.active" :style="edgeEdit.style" class="absolute z-[70] bg-white dark:bg-slate-800 p-2 rounded-xl shadow-2xl border-2 border-blue-500 flex gap-2 items-center">
        <input ref="edgeInputRef" v-model="edgeEdit.value" class="bg-transparent border-none outline-none text-sm font-bold dark:text-white w-32 px-1" @keyup.enter="saveEdgeLabel" @keyup.esc="closeEdgeEdit" />
        <button type="button" @click="saveEdgeLabel" class="text-emerald-500 hover:scale-110"><i class="bi bi-check-circle-fill text-lg"></i></button>
      </div>
    </Transition>

    <Transition name="fade">
      <div v-if="nodeEdit.active" :style="nodeEdit.style" class="absolute z-[70] bg-white dark:bg-slate-800 p-2 rounded-xl shadow-2xl border-2 border-blue-500 flex gap-2 items-center">
        <input ref="nodeInputRef" v-model="nodeEdit.value" class="bg-transparent border-none outline-none text-sm font-bold dark:text-white w-40 px-1" @keyup.enter="saveNodeLabel" @keyup.esc="nodeEdit.active = false" />
        <button type="button" @click="saveNodeLabel" class="text-emerald-500 hover:scale-110"><i class="bi bi-check-circle-fill text-lg"></i></button>
      </div>
    </Transition>

    <VueFlow
      v-model="tabs[activeTabIndex].elements"
      :default-viewport="{ x: 0, y: 0, zoom: 1.2 }"
      :delete-key-code="null" 
      class="bg-slate-50 dark:bg-[#0b1120] min-h-[700px]"
      @connect="onConnect"
      @edge-click="onEdgeClick"
      @edge-double-click="onEdgeDoubleClick"
      @node-click="openNodeEditor"
      @pane-click="closeAllEditors"
      @nodes-change="updateLaravelInput"
      @edges-change="updateLaravelInput"
    >
      <Controls position="bottom-right" />
      <Background pattern-color="#cbd5e1" :gap="25" />

      <template #node-custom="{ data, selected }">
        <div :class="['flow-node-base', getNodeClass(data.shape), selected ? 'node-selected' : '']" :style="getNodeStyle(data.shape)">
          <div v-if="selected" @click.stop="removeNode(data.id)" class="btn-delete-node"><i class="bi bi-x-lg text-[10px]"></i></div>

          <template v-if="data.shape === 'decision'">
            <Handle id="d-t" type="target" position="top" class="h-fixed h-d-top" />
            <Handle id="d-r" type="source" position="right" class="h-fixed h-d-right" />
            <Handle id="d-b" type="source" position="bottom" class="h-fixed h-d-bottom" />
            <Handle id="d-l" type="source" position="left" class="h-fixed h-d-left" />
          </template>
          <template v-else> 
            <Handle id="h-t" type="target" position="top" class="h-fixed h-std-v-lock" />
            <Handle id="h-b" type="source" position="bottom" class="h-fixed h-std-v-lock" />
            <Handle id="h-r" type="source" position="right" class="h-fixed h-std-h-lock" />
            <Handle id="h-l" type="source" position="left" class="h-fixed h-std-h-lock" />
          </template>
          
          <div class="node-content-wrapper p-4">
            <p class="text-[11px] font-bold text-center leading-tight whitespace-pre-wrap">{{ data.label || '...' }}</p>
          </div>
        </div>
      </template>
    </VueFlow>

    <Transition name="fade">
      <div v-if="showTabModal" class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 w-full max-w-md shadow-2xl border border-white/20">
          <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-6 italic">Tambah Alur Baru</h3>
          <input v-model="newTabName" @keyup.enter="confirmAddNewTab" class="w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 border-none outline-none focus:ring-4 ring-blue-500/20 text-slate-900 dark:text-white font-bold mb-6" placeholder="Misal: Alur Pengembalian" autofocus />
          <div class="flex gap-3">
            <button type="button" @click="showTabModal = false" class="flex-1 py-3 rounded-xl font-bold text-slate-400 uppercase tracking-widest text-[10px]">Batal</button>
            <button type="button" @click="confirmAddNewTab" class="flex-1 py-3 bg-blue-600 rounded-xl font-bold text-white uppercase tracking-widest text-[10px] shadow-lg shadow-blue-500/30">Buat</button>
          </div>
        </div>
      </div>
    </Transition>

    <div class="absolute bottom-6 left-6 right-6 flex justify-center items-center pointer-events-none z-[50]">
      <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 dark:text-slate-200 bg-white/80 dark:bg-slate-900/80 px-6 py-3 rounded-full border-2 border-slate-200 dark:border-slate-700 shadow-2xl backdrop-blur-md pointer-events-auto">
        <span class="text-blue-500"><i class="bi bi-mouse me-1"></i> Klik Node:</span> Edit Teks • 
        <span class="text-emerald-500"><i class="bi bi-bezier me-1"></i> Tarik Handle:</span> Hubungkan • 
        <span class="text-red-500"><i class="bi bi-trash me-1"></i> Delete:</span> Hapus
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { VueFlow, useVueFlow, Handle, MarkerType } from '@vue-flow/core'
import { Background } from '@vue-flow/background'
import { Controls } from '@vue-flow/controls'

import '@vue-flow/core/dist/style.css'
import '@vue-flow/core/dist/theme-default.css'
import '@vue-flow/controls/dist/style.css'

const { addEdges, removeNodes, removeEdges, getSelectedNodes, getSelectedEdges } = useVueFlow()

const activeTabIndex = ref(0)
const showTabModal = ref(false)
const showScriptEditor = ref(false)
const rawScript = ref("")
const newTabName = ref('')
const flowContainer = ref(null)
const edgeInputRef = ref(null)
const nodeInputRef = ref(null)
const currentTheme = ref('modern')
const edgeType = ref('step') 

const nodeEdit = ref({ active: false, value: '', node: null, style: {} })
const edgeEdit = ref({ active: false, value: '', edge: null, style: {} })

const tabs = ref([
  {
    id: Date.now(),
    name: 'Alur Utama',
    elements: [{ id: 'node_1', type: 'custom', data: { id: 'node_1', label: 'Mulai', shape: 'start' }, position: { x: 300, y: 50 } }]
  }
])

const themes = {
  modern: { start: 'bg-emerald-500', process: 'bg-blue-600', decision: 'bg-amber-500', io: 'bg-slate-700' },
  ocean: { start: 'bg-cyan-600', process: 'bg-sky-500', decision: 'bg-indigo-500', io: 'bg-slate-600' },
  forest: { start: 'bg-green-700', process: 'bg-lime-600', decision: 'bg-orange-500', io: 'bg-stone-600' }
}

// SHARED LOGIC UNTUK PENEMPATAN EDITOR
const calculateStyle = (event) => {
  const rect = flowContainer.value.getBoundingClientRect();
  return { 
    left: `${event.clientX - rect.left - 80}px`, 
    top: `${event.clientY - rect.top - 50}px` 
  };
}

const openNodeEditor = ({ event, node }) => {
  closeAllEditors();
  nodeEdit.value = { 
    active: true, 
    value: node.data.label || '', 
    node: node,
    style: calculateStyle(event)
  };
  nextTick(() => nodeInputRef.value?.focus());
}

const saveNodeLabel = () => {
  if (nodeEdit.value.node) {
    nodeEdit.value.node.data.label = nodeEdit.value.value;
    updateLaravelInput();
  }
  nodeEdit.value.active = false;
}

const onEdgeClick = ({ event, edge }) => {
  closeAllEditors();
  edgeEdit.value = { 
    active: true, 
    value: edge.label || '', 
    edge: edge, 
    style: calculateStyle(event)
  };
  nextTick(() => edgeInputRef.value?.focus());
}

const closeAllEditors = () => {
  edgeEdit.value.active = false;
  nodeEdit.value.active = false;
}

// LOGIKA HANDLE CERDAS
const getOptimalHandles = (sourceNode, targetNode) => {
    const sPos = sourceNode.position;
    const tPos = targetNode.position;
    const sShape = sourceNode.data.shape;
    const tShape = targetNode.data.shape;
    let sH = (sShape === 'decision') ? 'd-b' : 'h-b';
    let tH = (tShape === 'decision') ? 'd-t' : 'h-t';
    if (Math.abs(tPos.y - sPos.y) < 120) {
        if (tPos.x > sPos.x + 100) {
            sH = (sShape === 'decision') ? 'd-r' : 'h-r';
            tH = (tShape === 'decision') ? 'd-l' : 'h-l';
        } else if (tPos.x < sPos.x - 100) {
            sH = (sShape === 'decision') ? 'd-l' : 'h-l';
            tH = (tShape === 'decision') ? 'd-r' : 'h-r';
        }
    }
    return { sourceHandle: sH, targetHandle: tH };
}

const generateFromScript = () => {
  if (!rawScript.value.trim()) return
  const lines = rawScript.value.replace(/→/g, '->').split('\n')
  const newElements = []
  const nodeMap = new Map()
  let currentY = 50
  const centerX = 300

  lines.forEach((line) => {
    const matches = line.matchAll(/([\[\{\|])(.*?)([\]\}\|])(?:\s*\((.*?)\))?/g)
    for (const m of matches) {
        const label = m[2].trim();
        const lowLabel = label.toLowerCase();
        if (!nodeMap.has(lowLabel)) {
            const shape = m[4]?.trim() || (m[1] === '{' ? 'decision' : m[1] === '|' ? 'io' : 'process');
            const id = `node_${Date.now()}_${Math.random().toString(36).substr(2, 4)}`;
            newElements.push({ id, type: 'custom', position: { x: centerX, y: currentY }, data: { id, label, shape } });
            nodeMap.set(lowLabel, id);
            currentY += 160;
        }
    }
  })

  lines.forEach((line) => {
    const edge = line.match(/([\[\{\|])(.*?)([\]\}\|])\s*(?:--(.*?)--)?>\s*([\[\{\|])(.*?)([\]\}\|])/)
    if (edge) {
        const sId = nodeMap.get(edge[2].trim().toLowerCase());
        const tId = nodeMap.get(edge[6].trim().toLowerCase());
        if (sId && tId) {
            const sNode = newElements.find(n => n.id === sId);
            const tNode = newElements.find(n => n.id === tId);
            const { sourceHandle, targetHandle } = getOptimalHandles(sNode, tNode);
            newElements.push({
                id: `e-${sId}-${tId}-${Math.random()}`, source: sId, target: tId, label: edge[4] || '',
                sourceHandle, targetHandle, type: 'step', markerEnd: MarkerType.ArrowClosed,
                style: { strokeWidth: 2.5, stroke: '#64748b' },
                labelStyle: { fill: '#1e293b', fontWeight: 800, fontSize: '11px' },
                labelBgStyle: { fill: '#ffffff', fillOpacity: 0.95 }
            });
        }
    }
  })

  if (newElements.length > 0) {
    tabs.value[activeTabIndex.value].elements = [...newElements];
    showScriptEditor.value = false;
    updateLaravelInput();
  }
}

const onEdgeDoubleClick = ({ edge }) => { 
  removeEdges([edge.id]); 
  edgeEdit.value.active = false;
  updateLaravelInput(); 
}

const onConnect = (params) => { 
  addEdges([{ ...params, type: edgeType.value, markerEnd: MarkerType.ArrowClosed, style: { strokeWidth: 2.5, stroke: '#64748b' } }]); 
  updateLaravelInput(); 
}

const confirmAddNewTab = () => {
  if (!newTabName.value) return
  tabs.value.push({ id: Date.now(), name: newTabName.value, elements: [{ id: `node_${Date.now()}`, type: 'custom', data: { id: `node_${Date.now()}`, label: 'Mulai', shape: 'start' }, position: { x: 300, y: 50 } }] })
  newTabName.value = ''; showTabModal.value = false; activeTabIndex.value = tabs.value.length - 1; updateLaravelInput();
}

const switchTab = (index) => { activeTabIndex.value = index }
const removeTab = (index) => { if (tabs.value.length > 1) { tabs.value.splice(index, 1); activeTabIndex.value = 0; updateLaravelInput(); } }
const processAddNode = (shapeType) => {
  const id = `node_${Date.now()}`;
  tabs.value[activeTabIndex.value].elements.push({ id, type: 'custom', data: { id, label: '...', shape: shapeType }, position: { x: 300, y: 200 } });
  updateLaravelInput();
}

const updateLaravelInput = () => {
  const hiddenInput = document.getElementById('flowchart_master_input');
  if (hiddenInput) hiddenInput.value = JSON.stringify(tabs.value);
}

const saveEdgeLabel = () => { if (edgeEdit.value.edge) { edgeEdit.value.edge.label = edgeEdit.value.value; updateLaravelInput(); } closeEdgeEdit(); }
const closeEdgeEdit = () => { edgeEdit.value.active = false; }
const removeSelected = () => { removeNodes(getSelectedNodes.value); removeEdges(getSelectedEdges.value); updateLaravelInput(); }
const removeNode = (id) => { removeNodes([id]); updateLaravelInput(); }
const handleKeydown = (e) => { if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return; if (e.key === 'Delete') removeSelected(); }

const getNodeClass = (shape) => {
  const theme = themes[currentTheme.value];
  const classes = { start: `rounded-full ${theme.start}`, process: `rounded-none ${theme.process}`, decision: `shape-diamond ${theme.decision}`, io: `shape-io ${theme.io}` }
  return classes[shape] || classes.process
}

const getNodeStyle = (shape) => {
  if (shape === 'decision') return { width: '126px', height: '126px' }
  return { width: '180px', minHeight: '60px' }
}

onMounted(() => { updateLaravelInput(); flowContainer.value?.focus(); })
watch(tabs, () => updateLaravelInput(), { deep: true })
</script>

<style scoped>
.font-inter { font-family: 'Inter', sans-serif !important; }
.flow-node-base { @apply flex items-center justify-center border-2 shadow-xl relative transition-all duration-300 text-white cursor-pointer; box-sizing: border-box; }
.node-selected { @apply ring-4 ring-blue-500/40 border-blue-500 scale-[1.02] z-40; }
.node-content-wrapper { @apply w-full h-full flex items-center justify-center z-10 overflow-hidden; }

.h-fixed { position: absolute !important; }
.h-std-v-lock { left: 90px !important; transform: translateX(-50%) !important; }
.h-std-h-lock { top: 50% !important; transform: translateY(-50%) !important; }

.shape-diamond { transform: rotate(45deg); }
.shape-diamond p { transform: rotate(-45deg); }
.shape-io { transform: skewX(-15deg); }
.shape-io p { transform: skewX(15deg); }

.h-d-top { top: 0% !important; left: 0% !important; transform: translate(-50%, -50%) !important; }
.h-d-right { top: 0% !important; left: 100% !important; transform: translate(-50%, -50%) !important; }
.h-d-bottom { top: 100% !important; left: 100% !important; transform: translate(-50%, -50%) !important; }
.h-d-left { top: 100% !important; left: 0% !important; transform: translate(-50%, -50%) !important; }

:deep(.vue-flow__handle) { @apply !bg-white border-2 border-slate-500 !w-3 !h-3 hover:!scale-150 transition-all z-[100]; }
:deep(.vue-flow__edge) { @apply cursor-pointer; }

.btn-tool { @apply px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all active:scale-95 shadow-lg border border-white/10 flex items-center; }
.btn-edge { @apply px-2 py-1 rounded-lg text-[10px] text-slate-500 transition-all hover:bg-white; }
.btn-edge.active { @apply bg-white shadow-sm text-blue-600; }

.theme-select { @apply text-[10px] bg-slate-100 dark:bg-slate-800 dark:text-white border-none rounded-lg font-bold px-2 cursor-pointer; }
.btn-delete-node { @apply absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center cursor-pointer shadow-xl z-[110] border-2 border-white; }

.slide-down-enter-active, .slide-down-leave-active { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); }
.slide-down-enter-from, .slide-down-leave-to { transform: translateY(-100%); opacity: 0; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>