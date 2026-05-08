<template>
  <div style="background-color: #1e293b; padding: 30px; border-radius: 15px; color: #f1f5f9; font-family: sans-serif; border: 1px solid #334155; max-width: 750px; margin: 20px auto;">
    
    <div style="margin-bottom: 25px; text-align: center;">
      <label style="display: block; font-size: 10px; color: #64748b; font-weight: bold; margin-bottom: 10px; letter-spacing: 1px;">PILIH JENIS GERBANG</label>
      <div style="position: relative; display: inline-block;">
        <select v-model="selectedGate" @change="playClick" style="background-color: #0f172a; color: #60a5fa; border: 1px solid #334155; padding: 10px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; outline: none; appearance: none; text-align: center; min-width: 180px;">
          <option value="AND">AND GATE</option>
          <option value="OR">OR GATE</option>
          <option value="NOT">NOT GATE (Inverter)</option>
          <option value="NAND">NAND GATE</option>
          <option value="NOR">NOR GATE</option>
          <option value="XOR">XOR GATE</option>
          <option value="XNOR">XNOR GATE</option>
          <option value="BUFFER">BUFFER (Direct)</option>
        </select>
        <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b; font-size: 10px;">▼</div>
      </div>
    </div>

    <div style="position: relative; width: 100%; overflow: visible; margin-bottom: 20px;">
      <svg viewBox="0 0 500 200" style="width: 100%; height: auto; display: block; overflow: visible;">
        
        <path d="M 95 70 L 210 70" fill="none" stroke-width="4" :stroke="state.a ? '#4ade80' : '#334155'" style="transition: 0.3s;" />
        <path v-if="!['NOT', 'BUFFER'].includes(selectedGate)" d="M 95 150 L 210 150" fill="none" stroke-width="4" :stroke="state.b ? '#4ade80' : '#334155'" style="transition: 0.3s;" />
        
        <path d="M 285 110 L 440 110" fill="none" stroke-width="4" :stroke="result ? '#eab308' : '#334155'" style="transition: 0.3s;" />

        <g transform="translate(210, 70)">
          <path v-if="['AND', 'NAND'].includes(selectedGate)" d="M0 0H40C56.5 0 70 13.5 70 40C70 66.5 56.5 80 40 80H0V0Z" fill="#1e293b" stroke-width="5" :stroke="result ? '#3b82f6' : '#475569'" />
          
          <path v-if="['OR', 'NOR', 'XOR', 'XNOR'].includes(selectedGate)" d="M-5 0C-5 0 10 0 25 0C45 0 65 30 65 40C65 50 45 80 25 80C10 80 -5 80 -5 80C2 40 2 40 -5 0Z" fill="#1e293b" stroke-width="5" :stroke="result ? '#3b82f6' : '#475569'" />
          
          <path v-if="['XOR', 'XNOR'].includes(selectedGate)" d="M-12 0C-5 40 -5 40 -12 80" fill="none" stroke-width="3" :stroke="(state.a || state.b) ? '#475569' : '#334155'" />
          
          <path v-if="['NOT', 'BUFFER'].includes(selectedGate)" d="M0 0 L70 40 L0 80 Z" fill="#1e293b" stroke-width="5" :stroke="result ? '#3b82f6' : '#475569'" />

          <circle v-if="['NOT', 'NAND', 'NOR', 'XNOR'].includes(selectedGate)" cx="76" cy="40" r="6" fill="#1e293b" stroke-width="3" :stroke="result ? '#3b82f6' : '#475569'" />
          
          <text x="14" y="45" fill="#64748b" style="font-size: 10px; font-weight: bold; pointer-events: none; font-family: sans-serif;">{{ selectedGate }}</text>
        </g>

        <foreignObject x="0" y="45" width="110" height="50">
          <div style="display: flex; align-items: center; gap: 10px; height: 100%;">
            <div style="text-align: right; flex: 1;">
              <div style="font-size: 8px; color: #64748b; font-weight: 800;">IN A</div>
              <div :style="{ color: state.a ? '#4ade80' : '#475569' }" style="font-family: monospace; font-weight: bold; font-size: 14px;">{{ state.a ? '1' : '0' }}</div>
            </div>
            <button @click="toggleInput('a')" :style="{ backgroundColor: state.a ? '#22c55e' : '#334155', borderColor: state.a ? '#4ade80' : '#475569' }" style="width: 40px; height: 40px; border-radius: 8px; border: 2px solid; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; outline: none;">
              <div style="width: 3px; height: 12px; background-color: white; border-radius: 10px; opacity: 0.8;"></div>
            </button>
          </div>
        </foreignObject>

        <foreignObject v-if="!['NOT', 'BUFFER'].includes(selectedGate)" x="0" y="125" width="110" height="50">
          <div style="display: flex; align-items: center; gap: 10px; height: 100%;">
            <div style="text-align: right; flex: 1;">
              <div style="font-size: 8px; color: #64748b; font-weight: 800;">IN B</div>
              <div :style="{ color: state.b ? '#4ade80' : '#475569' }" style="font-family: monospace; font-weight: bold; font-size: 14px;">{{ state.b ? '1' : '0' }}</div>
            </div>
            <button @click="toggleInput('b')" :style="{ backgroundColor: state.b ? '#22c55e' : '#334155', borderColor: state.b ? '#4ade80' : '#475569' }" style="width: 40px; height: 40px; border-radius: 8px; border: 2px solid; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; outline: none;">
              <div style="width: 3px; height: 12px; background-color: white; border-radius: 10px; opacity: 0.8;"></div>
            </button>
          </div>
        </foreignObject>

        <foreignObject x="420" y="40" width="80" height="140">
          <div style="width: 100%; height: 100%; position: relative;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; text-align: center; font-size: 8px; color: #64748b; font-weight: 800; letter-spacing: 0.5px;">OUT Q</div>
            <div :style="{ 
                   backgroundColor: result ? '#eab308' : '#0f172a', 
                   borderColor: result ? '#fde047' : '#334155', 
                   boxShadow: result ? '0 0 25px rgba(234, 179, 8, 0.6)' : 'none' 
                 }"
                 style="position: absolute; top: 40px; left: 50%; transform: translateX(-50%); width: 55px; height: 55px; border-radius: 50%; border: 4px solid; display: flex; align-items: center; justify-content: center; transition: 0.3s;">
              <span :style="{ color: result ? '#422006' : '#334155' }" style="font-size: 22px; font-weight: 900; line-height: 1;">{{ result ? '1' : '0' }}</span>
            </div>
          </div>
        </foreignObject>
      </svg>
    </div>

    <div style="background-color: #0f172a; border-radius: 12px; padding: 20px; border: 1px solid #334155;">
      <p style="text-align: center; font-size: 10px; color: #475569; letter-spacing: 2px; margin-bottom: 15px; text-transform: uppercase; font-weight: bold;">Truth Table: {{ selectedGate }}</p>
      <div style="display: flex; border-bottom: 1px solid #334155; padding-bottom: 10px; margin-bottom: 10px; color: #60a5fa; font-weight: bold; font-size: 12px; text-align: center;">
        <div style="flex: 1;">A</div>
        <div v-if="!['NOT', 'BUFFER'].includes(selectedGate)" style="flex: 1;">B</div>
        <div style="flex: 1;">Q</div>
      </div>
      <div v-for="(row, i) in currentTruthTable" :key="i"
           :style="{ backgroundColor: isRowActive(row) ? 'rgba(59, 130, 246, 0.15)' : 'transparent', color: isRowActive(row) ? '#93c5fd' : '#475569' }"
           style="display: flex; padding: 8px 0; border-radius: 6px; font-family: monospace; font-size: 14px; text-align: center; transition: 0.2s;">
        <div style="flex: 1;">{{ row.a ? 1 : 0 }}</div>
        <div v-if="!['NOT', 'BUFFER'].includes(selectedGate)" style="flex: 1;">{{ row.b ? 1 : 0 }}</div>
        <div style="flex: 1;" :style="{ color: row.q ? '#eab308' : 'inherit', fontWeight: row.q ? 'bold' : 'normal' }">{{ row.q ? 1 : 0 }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed, ref, watch, onMounted } from 'vue';

const selectedGate = ref('AND');
const state = reactive({ a: false, b: false });

// Audio Engine
let audioCtx = null;
onMounted(() => { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); });

const playSound = (frequency, type, duration) => {
  if (!audioCtx) return;
  if (audioCtx.state === 'suspended') audioCtx.resume();
  const oscillator = audioCtx.createOscillator();
  const gainNode = audioCtx.createGain();
  oscillator.type = type;
  oscillator.frequency.setValueAtTime(frequency, audioCtx.currentTime);
  gainNode.gain.setValueAtTime(0.05, audioCtx.currentTime);
  gainNode.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);
  oscillator.connect(gainNode);
  gainNode.connect(audioCtx.destination);
  oscillator.start();
  oscillator.stop(audioCtx.currentTime + duration);
};

const playClick = () => playSound(800, 'sine', 0.05);
const playLightOn = () => playSound(400, 'triangle', 0.2);

const toggleInput = (key) => {
  state[key] = !state[key];
  playClick();
};

// Logika Kalkulasi
const result = computed(() => {
  const { a, b } = state;
  switch (selectedGate.value) {
    case 'AND':    return a && b;
    case 'OR':     return a || b;
    case 'NOT':    return !a;
    case 'NAND':   return !(a && b);
    case 'NOR':    return !(a || b);
    case 'XOR':    return a !== b;
    case 'XNOR':   return a === b;
    case 'BUFFER': return a;
    default:       return false;
  }
});

watch(result, (newVal) => { if (newVal) playLightOn(); });

const isRowActive = (row) => {
  if (['NOT', 'BUFFER'].includes(selectedGate.value)) return state.a === row.a;
  return state.a === row.a && state.b === row.b;
};

const currentTruthTable = computed(() => {
  const isSingleInput = ['NOT', 'BUFFER'].includes(selectedGate.value);
  const combinations = isSingleInput 
    ? [{ a: false }, { a: true }]
    : [{ a: false, b: false }, { a: false, b: true }, { a: true, b: false }, { a: true, b: true }];
  
  return combinations.map(row => {
    let q = false;
    const a = row.a;
    const b = row.b;
    if (selectedGate.value === 'AND') q = a && b;
    else if (selectedGate.value === 'OR') q = a || b;
    else if (selectedGate.value === 'NOT') q = !a;
    else if (selectedGate.value === 'NAND') q = !(a && b);
    else if (selectedGate.value === 'NOR') q = !(a || b);
    else if (selectedGate.value === 'XOR') q = a !== b;
    else if (selectedGate.value === 'XNOR') q = a === b;
    else if (selectedGate.value === 'BUFFER') q = a;
    return { ...row, q };
  });
});
</script>