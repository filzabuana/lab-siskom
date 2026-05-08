<template>
  <div class="hbe-container">
    <div class="hbe-header">
      <div class="brand">
        <h1>HBE-COMBO</h1>
        <span>DIGITAL LOGIC TRAINER</span>
      </div>
      <div class="led-array">
        <div v-for="(val, key) in inputs" :key="key" class="led-unit">
          <div :class="['led', { 'led-on': val }]"></div>
          <span class="led-label">IN {{ key.toUpperCase() }}</span>
        </div>
        <div class="led-unit">
          <div :class="['led led-out', { 'led-out-on': finalResult }]"></div>
          <span class="led-label">OUT Q</span>
        </div>
      </div>
    </div>

    <div class="hbe-board">
    <svg viewBox="0 0 800 340" class="circuit-svg">
  <g font-family="monospace" font-weight="bold" font-size="14" fill="#64748b">
    <text x="20" y="65">A</text>
    <text x="20" y="145">B</text>
    <text x="20" y="285">C</text>
  </g>

  <g font-family="monospace" font-size="12" font-weight="bold">
    <text x="60" y="50" :fill="inputs.a ? '#22c55e' : '#475569'">{{ inputs.a ? '1' : '0' }}</text>
    <text x="60" y="130" :fill="inputs.b ? '#22c55e' : '#475569'">{{ inputs.b ? '1' : '0' }}</text>
    <text x="60" y="270" :fill="inputs.c ? '#22c55e' : '#475569'">{{ inputs.c ? '1' : '0' }}</text>
  </g>

  <g stroke-width="4" fill="none">
    <path d="M 50 60 L 215 60" :stroke="inputs.a ? '#22c55e' : '#334155'" />
    <path d="M 50 140 L 215 140" :stroke="inputs.b ? '#22c55e' : '#334155'" />
    <path d="M 50 280 L 490 280 L 490 225 L 525 225" :stroke="inputs.c ? '#22c55e' : '#334155'" />
  </g>

  <path d="M 305 100 L 400 100 L 400 165 L 525 165" 
        fill="none" 
        stroke-width="4" 
        :stroke="res1 ? '#3b82f6' : '#334155'" />
  <text x="325" y="90" font-family="monospace" font-size="12" :fill="res1 ? '#3b82f6' : '#475569'">{{ res1 ? '1' : '0' }}</text>
  
  <path d="M 615 185 L 720 185" 
        fill="none" 
        stroke-width="5" 
        :stroke="finalResult ? '#facc15' : '#334155'" />
  <text x="735" y="170" font-family="sans-serif" font-size="10" font-weight="bold" fill="#64748b">OUT</text>

  <circle cx="750" cy="185" r="12" 
          :fill="finalResult ? '#facc15' : '#1e1b4b'" 
          :stroke="finalResult ? '#fef08a' : '#312e81'" 
          stroke-width="3" 
          style="transition: 0.3s;" />
  <circle v-if="finalResult" cx="750" cy="185" r="20" fill="rgba(250, 204, 21, 0.2)" />

  <g transform="translate(210, 60)">
    <text x="40" y="-15" text-anchor="middle" font-size="11" font-weight="bold" fill="#60a5fa" letter-spacing="1">GATE 1</text>
    <path :d="getGateShape(gate1)" 
          fill="#18181b" 
          :stroke="res1 ? '#3b82f6' : '#475569'" 
          stroke-width="3" />
    <circle v-if="['NAND', 'NOR', 'XNOR', 'NOT'].includes(gate1)" cx="86" cy="40" r="5" fill="#18181b" :stroke="res1 ? '#3b82f6' : '#475569'" stroke-width="3" />
    <path v-if="gate1 === 'XOR'" d="M-10 0C-2 40 -2 40 -10 80" fill="none" :stroke="res1 ? '#3b82f6' : '#475569'" stroke-width="3" />

    <foreignObject x="-10" y="95" width="100" height="40">
      <select v-model="gate1" @change="playClick" class="gate-select">
        <option v-for="g in allGates" :key="g" :value="g">{{ g }}</option>
      </select>
    </foreignObject>
  </g>

  <g transform="translate(520, 145)">
    <text x="40" y="-15" text-anchor="middle" font-size="11" font-weight="bold" fill="#facc15" letter-spacing="1">GATE 2</text>
    <path :d="getGateShape(gate2)" 
          fill="#18181b" 
          :stroke="finalResult ? '#facc15' : '#475569'" 
          stroke-width="3" />
    <circle v-if="['NAND', 'NOR', 'XNOR', 'NOT'].includes(gate2)" cx="86" cy="40" r="5" fill="#18181b" :stroke="finalResult ? '#facc15' : '#475569'" stroke-width="3" />
    <path v-if="gate2 === 'XNOR'" d="M-10 0C-2 40 -2 40 -10 80" fill="none" :stroke="finalResult ? '#facc15' : '#475569'" stroke-width="3" />

    <foreignObject x="-10" y="95" width="100" height="40">
      <select v-model="gate2" @change="playClick" class="gate-select">
        <option v-for="g in allGates" :key="g" :value="g">{{ g }}</option>
      </select>
    </foreignObject>
  </g>
</svg>
    </div>

    <div class="hbe-footer">
      <div class="switch-panel">
        <div v-for="(val, key) in inputs" :key="key" class="switch-control">
          <button @click="toggleInput(key)" :class="['toggle-btn', { 'btn-active': val }]">
            <div class="knob"></div>
          </button>
          <span class="switch-label">SW {{ key.toUpperCase() }}</span>
        </div>
      </div>
      <div class="logic-display">
        <div class="formula">Q = ({{ inputs.a?1:0 }} {{ gate1 }} {{ inputs.b?1:0 }}) {{ gate2 }} {{ inputs.c?1:0 }}</div>
        <div class="result-text" :style="{ color: finalResult ? '#f87171' : '#475569' }">
          RESULT: {{ finalResult ? 'HIGH (1)' : 'LOW (0)' }}
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { reactive, ref, computed, onMounted } from 'vue';

const inputs = reactive({ a: false, b: false, c: false });
const gate1 = ref('AND');
const gate2 = ref('OR');
const allGates = ['AND', 'OR', 'NAND', 'NOR', 'XOR', 'XNOR', 'NOT', 'BUFFER'];

const getGateShape = (type) => {
  // Geser titik awal ke kanan (x=10) agar kabel input 'masuk' sedikit ke body
  if (type === 'NOT' || type === 'BUFFER') {
    return "M10 0 L85 40 L10 80 Z";
  }
  if (type.includes('AND')) {
    return "M5 0 H45 C70 0 85 15 85 40 C85 65 70 80 45 80 H5 V0 Z";
  }
  if (type.includes('OR') || type.includes('XOR')) {
    return "M0 0 C0 0 20 0 40 0 C65 0 85 30 85 40 C85 50 65 80 40 80 C20 80 0 80 0 80 C10 40 10 40 0 0 Z";
  }
  return "";
};
// --------------------------------

let audioCtx = null;
onMounted(() => {
  audioCtx = new (window.AudioContext || window.webkitAudioContext)();
});

const playClick = () => {
  if (!audioCtx) return;
  if (audioCtx.state === 'suspended') audioCtx.resume();
  const osc = audioCtx.createOscillator();
  const gain = audioCtx.createGain();
  osc.frequency.setValueAtTime(inputs.a || inputs.b ? 800 : 600, audioCtx.currentTime);
  gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
  gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
  osc.connect(gain); gain.connect(audioCtx.destination);
  osc.start(); osc.stop(audioCtx.currentTime + 0.1);
};

const toggleInput = (key) => {
  inputs[key] = !inputs[key];
  playClick();
};

const solve = (type, in1, in2) => {
  switch (type) {
    case 'AND':  return in1 && in2;
    case 'OR':   return in1 || in2;
    case 'NAND': return !(in1 && in2);
    case 'NOR':  return !(in1 || in2);
    case 'XOR':  return in1 !== in2;
    case 'XNOR': return in1 === in2;
    case 'NOT':  return !in1; // Hanya memproses input pertama
    case 'BUFFER': return in1; // Hanya meneruskan input pertama
    default: return false;
  }
};

const res1 = computed(() => solve(gate1.value, inputs.a, inputs.b));
const finalResult = computed(() => solve(gate2.value, res1.value, inputs.c));
</script>


<style scoped>
.hbe-container {
  background: #27272a;
  padding: 30px;
  border-radius: 12px;
  border: 8px solid #3f3f46;
  max-width: 850px;
  margin: 20px auto;
  color: #e4e4e7;
  box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
}

.hbe-header {
  display: flex;
  justify-content: space-between;
  background: #18181b;
  padding: 15px 25px;
  border-radius: 8px;
  margin-bottom: 20px;
  border-bottom: 4px solid #09090b;
}

.brand h1 { margin: 0; font-size: 20px; color: #3b82f6; letter-spacing: 2px; }
.brand span { font-size: 9px; color: #71717a; }

.led-array { display: flex; gap: 20px; }
.led-unit { text-align: center; }
.led { width: 15px; height: 15px; border-radius: 50%; background: #450a0a; margin: 0 auto 5px; transition: 0.3s; }
.led-on { background: #ef4444; box-shadow: 0 0 12px #ef4444; }
.led-out { background: #1e1b4b; }
.led-out-on { background: #facc15; box-shadow: 0 0 15px #facc15; }
.led-label { font-size: 8px; font-weight: bold; color: #a1a1aa; }

.hbe-board { background: #1e1e1e; border-radius: 8px; padding: 10px; border: 2px solid #000; }
.circuit-svg { width: 100%; height: auto; }

.bus-lines path { stroke: #3f3f46; stroke-width: 4; fill: none; transition: 0.3s; }
.bus-lines path.active { stroke: #22c55e; }
.active-out { stroke: #3b82f6; stroke-width: 4; fill: none; }
.active-final { stroke: #facc15; stroke-width: 5; fill: none; }

.gate-box { fill: #2d2d2d; stroke: #3b82f6; stroke-width: 2; }
.gate-box.highlight { stroke: #facc15; }
.gate-title { fill: #71717a; font-size: 9px; font-weight: bold; text-anchor: middle; }
.gate-select { width: 100%; background: #000; color: #fff; border: 1px solid #3f3f46; font-size: 11px; text-align: center; border-radius: 4px; }

.hbe-footer { margin-top: 20px; display: flex; gap: 30px; align-items: center; }
.switch-panel { display: flex; gap: 25px; background: #18181b; padding: 15px; border-radius: 10px; border: 2px solid #3f3f46; }
.switch-control { text-align: center; }

.toggle-btn { width: 35px; height: 50px; background: #3f3f46; border-radius: 6px; position: relative; cursor: pointer; border: none; padding: 0; }
.knob { width: 25px; height: 20px; background: #71717a; margin: 5px auto; border-radius: 3px; transition: 0.2s; box-shadow: 0 4px 0 #27272a; }
.btn-active .knob { transform: translateY(20px); background: #22c55e; }
.switch-label { display: block; font-size: 9px; margin-top: 8px; color: #a1a1aa; font-weight: bold; }

.logic-display { flex: 1; text-align: right; font-family: monospace; }
.formula { font-size: 16px; color: #3b82f6; margin-bottom: 5px; }

.gate-label {
  fill: #60a5fa;
  font-size: 10px;
  font-weight: bold;
  text-anchor: middle;
  letter-spacing: 1px;
}

/* Supaya dropdown tidak menutupi garis kabel */
.gate-select {
  width: 100%;
  background: #09090b;
  color: #fff;
  border: 1px solid #3f3f46;
  font-size: 10px;
  padding: 2px;
  border-radius: 4px;
  cursor: pointer;
  outline: none;
}

path {
  transition: d 0.3s ease, stroke 0.3s ease;
}

path {
  fill: none; /* WAJIB: agar kabel tidak jadi blok hitam */
  transition: d 0.3s ease, stroke 0.3s ease;
}

/* Pastikan gerbangnya sendiri tetap punya warna isi */
.hbe-board path[fill="#18181b"] {
  fill: #18181b !important;
}
</style>