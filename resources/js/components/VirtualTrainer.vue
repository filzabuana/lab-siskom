<template>
  <div class="isolated-board-wrapper">
    
    <div class="board-header">
      <div class="header-title">
        <h2 class="title-text">
          <span class="pulse-indicator"></span>
          VIRTUAL HARDWARE BOARD
        </h2>
        <p class="subtitle-text">MODEL: HBE-EXTENDED-MODULAR-TRAINER</p>
      </div>

      <div class="power-controller">
        <span class="power-label font-mono">BOARD POWER</span>
        <button 
          @click="togglePower" 
          :class="['power-switch', isPowerOn ? 'power-on' : 'power-off']">
          <div :class="['switch-knob', isPowerOn ? 'knob-on' : 'knob-off']"></div>
        </button>
        <div :class="['power-led', isPowerOn ? 'led-active' : 'led-inactive']"></div>
      </div>
    </div>

    <div ref="boardRef" class="hardware-grid">
      
      <svg class="cable-svg-layer">
        <g v-for="(conn, idx) in connections" :key="idx">
          <path 
            :d="getBezierCurve(conn.sourceCoords, conn.targetCoords)" 
            fill="none" 
            stroke="rgba(0, 0, 0, 0.7)" 
            stroke-width="6" 
            stroke-linecap="round"
          />
          <path 
            :d="getBezierCurve(conn.sourceCoords, conn.targetCoords)" 
            fill="none" 
            :stroke="conn.signalValue && isPowerOn ? '#00ff66' : '#ff3333'" 
            stroke-width="3" 
            stroke-linecap="round"
            :class="['interactive-cable', conn.signalValue && isPowerOn ? 'cable-glow-active' : 'cable-glow-inactive']"
            @dblclick="removeConnection(idx)"
            title="Double click untuk menghapus kabel"
          />
        </g>

        <path 
          v-if="activeSourcePin && tempTargetCoords" 
          :d="getBezierCurve(activeSourceCoords, tempTargetCoords)" 
          fill="none" 
          stroke="#00ccff" 
          stroke-dasharray="5,5" 
          stroke-width="2.5"
          class="temp-cable-glow"
        />
      </svg>

      <div class="panel-column left-panel-sw">
        <span class="panel-title font-mono">INPUT SWITCH</span>
        <div class="switches-list">
          <div v-for="(val, idx) in switches" :key="idx" class="hardware-item mb-1">
            <div class="socket-group">
              <div 
                :id="`pin-sw-${idx}`"
                @click="handlePinClick('sw', idx)" 
                :class="['socket-pin', 'socket-output-style', val && isPowerOn ? 'socket-glow-green-neon' : 'socket-red-dark-style', isPinSelected('sw', idx) ? 'selected-pin' : '']">
                <div class="socket-inner-metallic"></div>
              </div>
              <span class="item-label">SW {{ idx + 1 }}</span>
            </div>
            <button 
              @click="toggleSwitch(idx)" 
              :disabled="!isPowerOn" 
              :class="['toggle-switch-btn', !isPowerOn ? 'disabled-switch' : '']">
              <div :class="['toggle-knob', val && isPowerOn ? 'toggle-knob-active' : '']"></div>
            </button>
          </div>
        </div>

        <div class="sig-gen-panel mt-3">
          <span class="panel-title font-mono border-t border-slate-800 pt-2">SIGNAL GENERATOR</span>
          
          <div class="hardware-item justify-around py-2">
            <div class="socket-group">
              <div 
                id="pin-sig-out"
                @click="handlePinClick('sig_out', null)" 
                :class="['socket-pin', 'socket-output-style', clockSignal && isPowerOn ? 'socket-glow-blue-neon' : 'socket-input-style', isPinSelected('sig_out', null) ? 'selected-pin' : '']">
                <div class="socket-inner-metallic"></div>
              </div>
              <span class="item-label font-bold text-cyan-400">CLK OUT</span>
            </div>
            <div :class="['pulse-led', clockSignal && isPowerOn ? 'pulse-led-active' : 'pulse-led-inactive']"></div>
          </div>

          <div class="speed-control-box mt-2">
            <label class="speed-label font-mono">SPEED: {{ clockHz }} Hz</label>
            <input 
              type="range" 
              min="0.5" 
              max="10" 
              step="0.5" 
              v-model.number="clockHz" 
              @input="updateClockInterval"
              :disabled="!isPowerOn || !isClockRunning"
              class="speed-slider"
            />
          </div>

          <button 
            @click="toggleClockActive" 
            :disabled="!isPowerOn"
            :class="['btn-sig-switch font-mono mt-2', isClockRunning && isPowerOn ? 'btn-sig-on' : 'btn-sig-off']">
            {{ isClockRunning && isPowerOn ? '⏹ STOP GENERATOR' : '▶ START GENERATOR' }}
          </button>
        </div>
      </div>

      <div class="panel-column center-panel-chips">
        
        <div class="inner-row row-top-gates">
          <div class="panel-title-row">
            <span class="panel-title font-mono text-cyan-400">MODULAR CHIP SLOTS</span>
            <span v-if="selectedSlotId !== null" class="selection-notice animate-pulse">
              PILIH TIPE CHIP DI BAWAH UNTUK SLOT #{{ selectedSlotId + 1 }}
            </span>
          </div>

          <div class="gates-grid-container">
            <div 
              v-for="(gate, idx) in gates" 
              :key="gate.id" 
              :class="['gate-chip-box', selectedSlotId === idx ? 'slot-ready-to-replace' : '']">
              
              <div class="chip-top-bar">
                <span class="chip-serial">SLOT {{ idx + 1 }}::{{ gate.type }}</span>
                <button 
                  @click="selectSlotForReplacement(idx)" 
                  :class="['btn-replace-chip', selectedSlotId === idx ? 'btn-replace-active' : '']"
                  title="Ganti tipe modul ini">
                  ⚡ GANTI
                </button>
              </div>
              
              <div class="gate-symbol-container" :class="{'ff-chip-bg': gate.type.includes('FF')}">
                <svg viewBox="0 0 80 40" class="gate-logic-svg">
                  <path v-if="gate.type === 'AND'" d="M20,10 L40,10 A10,10 0 0,1 50,20 A10,10 0 0,1 40,30 L20,30 Z" class="svg-gate-path" />
                  
                  <path v-else-if="gate.type === 'OR'" d="M20,10 C25,15 25,25 20,30 C32,30 42,26 50,20 C42,14 32,10 20,10 Z" class="svg-gate-path" />
                  
                  <g v-else-if="gate.type === 'NOT'">
                    <polygon points="25,10 45,20 25,30" class="svg-gate-path" />
                    <circle cx="49" cy="20" r="3" class="svg-gate-path" />
                  </g>
                  
                  <g v-else-if="gate.type === 'NAND'">
                    <path d="M20,10 L38,10 A10,10 0 0,1 48,20 A10,10 0 0,1 38,30 L20,30 Z" class="svg-gate-path" />
                    <circle cx="52" cy="20" r="3" class="svg-gate-path" />
                  </g>
                  
                  <g v-else-if="gate.type === 'NOR'">
                    <path d="M18,10 C23,15 23,25 18,30 C30,30 40,26 48,20 C40,14 30,10 18,10 Z" class="svg-gate-path" />
                    <circle cx="52" cy="20" r="3" class="svg-gate-path" />
                  </g>
                  
                  <g v-else-if="gate.type === 'XOR'">
                    <path d="M15,10 C20,15 20,25 15,30" fill="none" stroke="#475569" stroke-width="2" />
                    <path d="M20,10 C25,15 25,25 20,30 C32,30 42,26 50,20 C42,14 32,10 20,10 Z" class="svg-gate-path" />
                  </g>

                  <g v-else-if="gate.type === 'JK-FF'">
                    <rect x="20" y="5" width="40" height="30" rx="3" class="svg-ff-rect" />
                    <text x="25" y="14" class="svg-ff-text">J</text>
                    <circle cx="16" cy="20" r="2.5" fill="#020617" stroke="#38bdf8" stroke-width="1" />
                    <polygon points="20,17 24,20 20,23" fill="none" stroke="#38bdf8" stroke-width="1.2" />
                    <text x="28" y="22" class="svg-ff-text text-cp">CP</text>
                    <text x="25" y="31" class="svg-ff-text">K</text>
                    <text x="50" y="15" class="svg-ff-text">Q</text>
                    <text x="47" y="29" class="svg-ff-text">/Q</text>
                  </g>

                  <g v-else-if="gate.type === 'D-FF'">
                    <rect x="20" y="5" width="40" height="30" rx="3" class="svg-ff-rect" />
                    <text x="25" y="15" class="svg-ff-text">D</text>
                    <circle cx="16" cy="25" r="2.5" fill="#020617" stroke="#38bdf8" stroke-width="1" />
                    <polygon points="20,22 24,25 20,28" fill="none" stroke="#38bdf8" stroke-width="1.2" />
                    <text x="28" y="27" class="svg-ff-text text-cp">CP</text>
                    <text x="50" y="15" class="svg-ff-text">Q</text>
                    <text x="47" y="29" class="svg-ff-text">/Q</text>
                  </g>
                </svg>
                <div class="gate-type-badge">{{ gate.type }}</div>
              </div>

              <div class="gate-pins-row">
                <template v-if="gate.type === 'JK-FF'">
                  <div class="gate-inputs-flex">
                    <div class="socket-group-mini">
                      <span class="pin-label text-blue-400">J</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-0`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: 0 })" 
                        :class="['socket-pin', gate.inputs[0] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: 0 }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini">
                      <span class="pin-label text-cyan-400">CP</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-1`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: 1 })" 
                        :class="['socket-pin', gate.inputs[1] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: 1 }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini">
                      <span class="pin-label text-blue-400">K</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-2`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: 2 })" 
                        :class="['socket-pin', gate.inputs[2] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: 2 }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini">
                      <span class="pin-label text-amber-500">CLR</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-3`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: 3 })" 
                        :class="['socket-pin', gate.inputs[3] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: 3 }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                  <div class="ff-output-flex">
                    <div class="socket-group-mini">
                      <span class="pin-label">Q</span>
                      <div 
                        :id="`pin-gate_out-${gate.id}`"
                        @click="handlePinClick('gate_out', gate.id)" 
                        :class="['socket-pin', gate.output && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('gate_out', gate.id) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini">
                      <span class="pin-label">/Q</span>
                      <div 
                        :id="`pin-gate_out_secondary-${gate.id}`"
                        @click="handlePinClick('gate_out_secondary', gate.id)" 
                        :class="['socket-pin', !gate.output && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('gate_out_secondary', gate.id) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-else-if="gate.type === 'D-FF'">
                  <div class="gate-inputs-flex">
                    <div class="socket-group-mini">
                      <span class="pin-label text-blue-400">D</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-0`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: 0 })" 
                        :class="['socket-pin', gate.inputs[0] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: 0 }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini">
                      <span class="pin-label text-cyan-400">CP</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-1`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: 1 })" 
                        :class="['socket-pin', gate.inputs[1] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: 1 }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                  <div class="ff-output-flex">
                    <div class="socket-group-mini">
                      <span class="pin-label">Q</span>
                      <div 
                        :id="`pin-gate_out-${gate.id}`"
                        @click="handlePinClick('gate_out', gate.id)" 
                        :class="['socket-pin', gate.output && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('gate_out', gate.id) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini">
                      <span class="pin-label">/Q</span>
                      <div 
                        :id="`pin-gate_out_secondary-${gate.id}`"
                        @click="handlePinClick('gate_out_secondary', gate.id)" 
                        :class="['socket-pin', !gate.output && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('gate_out_secondary', gate.id) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-else>
                  <div class="gate-inputs-flex">
                    <div v-for="(inputVal, pIdx) in gate.inputs" :key="pIdx" class="socket-group-mini">
                      <span class="pin-label">I{{ pIdx+1 }}</span>
                      <div 
                        :id="`pin-gate_in-${gate.id}-${pIdx}`"
                        @click="handlePinClick('gate_in', { id: gate.id, pin: pIdx })" 
                        :class="['socket-pin', inputVal && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('gate_in', { id: gate.id, pin: pIdx }) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                  <div class="socket-group-mini">
                    <span class="pin-label">OUT</span>
                    <div 
                      :id="`pin-gate_out-${gate.id}`"
                      @click="handlePinClick('gate_out', gate.id)" 
                      :class="['socket-pin', gate.output && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('gate_out', gate.id) ? 'selected-pin' : '']">
                      <div class="socket-inner-metallic"></div>
                    </div>
                  </div>
                </template>
              </div>

            </div>
          </div>
        </div>

        <div class="inner-row storage-row">
          <span class="panel-title font-mono text-center">CHIP STORAGE (KLIK "GANTI" PADA CHIP DI ATAS, LALU PILIH DI SINI)</span>
          <div class="storage-chips-flex">
            <button 
              v-for="type in availableGateTypes" 
              :key="type" 
              @click="applyGateReplacement(type)"
              :disabled="selectedSlotId === null"
              :class="['storage-chip-item', selectedSlotId !== null ? 'storage-active-btn' : 'storage-disabled-btn', type.includes('FF') ? 'ff-storage-highlight' : '']">
              <span class="storage-gate-name">{{ type }}</span>
              <span class="storage-gate-desc">
                {{ type === 'NOT' ? '1-Input' : type.includes('FF') ? 'Flip-Flop' : '2-Input' }}
              </span>
            </button>
          </div>
        </div>

        <div class="inner-row row-bottom-modules">
          <div class="bottom-half-panel">
            <span class="panel-title font-mono">FIXED FLIP-FLOP REGISTER</span>
            <div class="flipflop-horizontal-container">
              
              <div class="sub-chip-box-horizontal">
                <span class="chip-serial-tab">JK-TYPE FF (Falling Edge)</span>
                <div class="ff-pins-wrapper">
                  <div class="ff-pins-column border-right-dark">
                    <div class="socket-group-mini-row">
                      <span class="pin-label-left">J</span>
                      <div 
                        :id="`pin-ff_jk-j`"
                        @click="handlePinClick('ff_jk_in', 'j')"
                        :class="['socket-pin', ffJK.j && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('ff_jk_in', 'j') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini-row">
                      <span class="pin-label-left">CP</span>
                      <div 
                        :id="`pin-ff_jk-cp`"
                        @click="handlePinClick('ff_jk_in', 'cp')"
                        :class="['socket-pin', ffJK.cp && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('ff_jk_in', 'cp') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                      <button @click="triggerManualClock('jk')" :disabled="!isPowerOn" class="btn-trigger-clock" title="Pulse Clock Manual">⤓</button>
                    </div>
                    <div class="socket-group-mini-row">
                      <span class="pin-label-left">K</span>
                      <div 
                        :id="`pin-ff_jk-k`"
                        @click="handlePinClick('ff_jk_in', 'k')"
                        :class="['socket-pin', ffJK.k && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('ff_jk_in', 'k') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini-row">
                      <span class="pin-label-left text-amber-500">CLR</span>
                      <div 
                        :id="`pin-ff_jk-clr`"
                        @click="handlePinClick('ff_jk_in', 'clr')"
                        :class="['socket-pin', ffJK.clr && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('ff_jk_in', 'clr') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>

                  <div class="ff-pins-column">
                    <div class="socket-group-mini-row-rev">
                      <div 
                        :id="`pin-ff_jk-q`"
                        @click="handlePinClick('ff_jk_out', 'q')"
                        :class="['socket-pin', ffJK.q && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('ff_jk_out', 'q') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                      <span class="pin-label-right">Q</span>
                    </div>
                    <div class="socket-group-mini-row-rev">
                      <div 
                        :id="`pin-ff_jk-qn`"
                        @click="handlePinClick('ff_jk_out', 'qn')"
                        :class="['socket-pin', !ffJK.q && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('ff_jk_out', 'qn') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                      <span class="pin-label-right">/Q</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="sub-chip-box-horizontal">
                <span class="chip-serial-tab">D-TYPE FF (Falling Edge)</span>
                <div class="ff-pins-wrapper">
                  <div class="ff-pins-column border-right-dark">
                    <div class="socket-group-mini-row">
                      <span class="pin-label-left">D</span>
                      <div 
                        :id="`pin-ff_d-d`"
                        @click="handlePinClick('ff_d_in', 'd')"
                        :class="['socket-pin', ffD.d && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('ff_d_in', 'd') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                    <div class="socket-group-mini-row">
                      <span class="pin-label-left">CP</span>
                      <div 
                        :id="`pin-ff_d-cp`"
                        @click="handlePinClick('ff_d_in', 'cp')"
                        :class="['socket-pin', ffD.cp && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('ff_d_in', 'cp') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                      <button @click="triggerManualClock('d')" :disabled="!isPowerOn" class="btn-trigger-clock" title="Pulse Clock Manual">⤓</button>
                    </div>
                  </div>

                  <div class="ff-pins-column">
                    <div class="socket-group-mini-row-rev">
                      <div 
                        :id="`pin-ff_d-q`"
                        @click="handlePinClick('ff_d_out', 'q')"
                        :class="['socket-pin', ffD.q && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('ff_d_out', 'q') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                      <span class="pin-label-right">Q</span>
                    </div>
                    <div class="socket-group-mini-row-rev">
                      <div 
                        :id="`pin-ff_d-qn`"
                        @click="handlePinClick('ff_d_out', 'qn')"
                        :class="['socket-pin', !ffD.q && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('ff_d_out', 'qn') ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                      <span class="pin-label-right">/Q</span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="bottom-half-panel border-left-divider">
            <span class="panel-title font-mono">BCD TO 7-SEG DECODER</span>
            <div class="sub-chip-box-horizontal decoder-chip-wide">
              <span class="chip-serial-tab">DECODER BCD-7SEG</span>
              <div class="decoder-pins-wrapper">
                <div class="decoder-pins-section">
                  <span class="section-label">INPUTS</span>
                  <div class="decoder-pins-line">
                    <div v-for="bin in ['A', 'B', 'C', 'D']" :key="bin" class="socket-group-mini">
                      <span class="pin-label-micro">{{ bin }}</span>
                      <div 
                        :id="`pin-dec-in-${bin}`"
                        @click="handlePinClick('decoder_in', bin)"
                        :class="['socket-pin', decoderInputs[bin] && isPowerOn ? 'socket-glow-green-neon' : 'socket-input-style', isPinSelected('decoder_in', bin) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="decoder-pins-section">
                  <span class="section-label">OUTPUTS</span>
                  <div class="decoder-pins-line">
                    <div v-for="seg in ['a', 'b', 'c', 'd', 'e', 'f', 'g']" :key="seg" class="socket-group-mini">
                      <span class="pin-label-micro">{{ seg }}</span>
                      <div 
                        :id="`pin-dec-out-${seg}`"
                        @click="handlePinClick('decoder_out', seg)"
                        :class="['socket-pin', decoderOutputs[seg] && isPowerOn ? 'socket-glow-blue-neon' : 'socket-output-style', isPinSelected('decoder_out', seg) ? 'selected-pin' : '']">
                        <div class="socket-inner-metallic"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="panel-column right-panel-leds">
        <span class="panel-title font-mono">LED MONITOR</span>
        <div class="column-content-leds">
          <div class="led-grid-vertical">
            <div v-for="(val, idx) in leds" :key="idx" class="hardware-item bg-dark-item">
              <div class="socket-group-led">
                <div 
                  :id="`pin-led-${idx}`"
                  @click="handlePinClick('led', idx)" 
                  :class="['socket-pin', 'socket-input-style', isPinSelected('led', idx) ? 'selected-pin' : '']">
                  <div class="socket-inner-metallic"></div>
                </div>
                <span class="item-label">OUT {{ idx + 1 }}</span>
              </div>
              <div :class="['led-indicator', val && isPowerOn ? 'led-red-glow' : 'led-red-dark']"></div>
            </div>
          </div>

          <div class="seven-segment-container">
            <div class="seg-svg-wrapper">
              <svg viewBox="0 0 50 80" class="seven-seg-svg">
                <path d="M 12 6 L 38 6 L 34 10 L 16 10 Z" :class="['seg-path', segmentState.a && isPowerOn ? 'seg-active' : 'seg-inactive']" />
                <path d="M 10 8 L 14 12 L 14 36 L 10 40 Z" :class="['seg-path', segmentState.f && isPowerOn ? 'seg-active' : 'seg-inactive']" />
                <path d="M 40 8 L 40 40 L 36 36 L 36 12 Z" :class="['seg-path', segmentState.b && isPowerOn ? 'seg-active' : 'seg-inactive']" />
                <path d="M 12 41 L 16 38 L 34 38 L 38 41 L 34 44 L 16 44 Z" :class="['seg-path', segmentState.g && isPowerOn ? 'seg-active' : 'seg-inactive']" />
                <path d="M 10 42 L 14 46 L 14 70 L 10 74 Z" :class="['seg-path', segmentState.e && isPowerOn ? 'seg-active' : 'seg-inactive']" />
                <path d="M 40 42 L 40 74 L 36 70 L 36 46 Z" :class="['seg-path', segmentState.c && isPowerOn ? 'seg-active' : 'seg-inactive']" />
                <path d="M 12 76 L 16 72 L 34 72 L 38 76 Z" :class="['seg-path', segmentState.d && isPowerOn ? 'seg-active' : 'seg-inactive']" />
              </svg>
            </div>
            
            <div class="seg-inputs-grid">
              <div v-for="seg in ['a', 'b', 'c', 'd', 'e', 'f', 'g']" :key="seg" class="socket-group-mini">
                <span class="pin-label-micro">{{ seg }}</span>
                <div 
                  :id="`pin-seg-phys-${seg}`"
                  @click="handlePinClick('seg_phys', seg)"
                  :class="['socket-pin', 'socket-input-style', isPinSelected('seg_phys', seg) ? 'selected-pin' : '']">
                  <div class="socket-inner-metallic"></div>
                </div>
              </div>
            </div>
            <span class="display-label font-mono">7-SEG INTERACTIVE</span>
          </div>
        </div>
      </div>

    </div>

    <div class="status-footer">
      <span class="status-text font-mono">
        SYSTEM STATUS: <span class="active-text">ONLINE</span> | 
        CONNECTED WIRES: <span class="text-blue-400 font-bold">{{ connections.length }}</span> | 
        <span class="text-emerald-400 font-bold">INFO: Simulator diperbarui! JK-FF sekarang memiliki Pin CLR (Reset Active-LOW). Hubungkan ke output gerbang untuk auto-reset.</span>
      </span>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, watch, nextTick } from 'vue';

const isPowerOn = ref(true);
const boardRef = ref(null);

const switches = reactive(Array(8).fill(false));
const leds = reactive(Array(8).fill(false));

// === STATE: CLOCK ===
const clockSignal = ref(false);       
const isClockRunning = ref(false);    
const clockHz = ref(1);              
let clockIntervalId = null;          

const gates = reactive([
  // Array Input JK-FF Modular sekarang berukuran 4: [J, CP, K, CLR]
  // CLR diset default true (HIGH) agar tidak mereset secara default jika tidak ada kabel dicolok
  { id: 'gate_slot_0', type: 'JK-FF', inputs: [true, false, true, true], output: false }, 
  { id: 'gate_slot_1', type: 'JK-FF', inputs: [true, false, true, true], output: false }, 
  { id: 'gate_slot_2', type: 'JK-FF', inputs: [true, false, true, true], output: false }, 
  { id: 'gate_slot_3', type: 'JK-FF', inputs: [true, false, true, true], output: false }, 
  { id: 'gate_slot_4', type: 'NOR', inputs: [false, false], output: true },
  { id: 'gate_slot_5', type: 'XOR', inputs: [false, false], output: false },
]);

const availableGateTypes = ['AND', 'OR', 'NOT', 'NAND', 'NOR', 'XOR', 'JK-FF', 'D-FF'];
const selectedSlotId = ref(null);

// Fixed Register State (ffJK sekarang memiliki properti clr)
const ffJK = reactive({ j: false, k: false, cp: false, clr: true, q: false });
const ffD = reactive({ d: false, cp: false, q: false });

const decoderInputs = reactive({ A: false, B: false, C: false, D: false });
const decoderOutputs = reactive({ a: false, b: false, c: false, d: false, e: false, f: false, g: false });
const segmentState = reactive({ a: false, b: false, c: false, d: false, e: false, f: false, g: false });

// Wire Logic
const connections = reactive([]); 
const activeSourcePin = ref(null); 
const activeSourceCoords = ref({ x: 0, y: 0 });
const tempTargetCoords = ref(null);

const selectSlotForReplacement = (index) => {
  selectedSlotId.value = selectedSlotId.value === index ? null : index;
};

const applyGateReplacement = (newType) => {
  if (selectedSlotId.value === null) return;
  const slotIdx = selectedSlotId.value;
  const targetGate = gates[slotIdx];

  // Bersihkan kabel terkait slot ini
  for (let i = connections.length - 1; i >= 0; i--) {
    const conn = connections[i];
    const isSourceMatch = (conn.source.type === 'gate_out' || conn.source.type === 'gate_out_secondary') && conn.source.payload === targetGate.id;
    const isTargetMatch = conn.target.type === 'gate_in' && conn.target.payload.id === targetGate.id;
    
    if (isSourceMatch || isTargetMatch) {
      connections.splice(i, 1);
    }
  }

  targetGate.type = newType;
  
  if (newType === 'JK-FF') {
    // Diatur ke J=1, K=1, CLR=1 secara default
    targetGate.inputs = [true, false, true, true]; 
    targetGate.output = false;                 
  } else if (newType === 'D-FF') {
    targetGate.inputs = [false, false];        
    targetGate.output = false;
  } else if (newType === 'NOT') {
    targetGate.inputs = [false];
    targetGate.output = true;
  } else {
    targetGate.inputs = [false, false];
    targetGate.output = newType === 'NAND' || newType === 'NOR';
  }

  selectedSlotId.value = null;
  nextTick(() => { evaluateCircuit(); });
};

const toggleClockActive = () => {
  if (!isPowerOn.value) return;
  isClockRunning.value = !isClockRunning.value;
  if (isClockRunning.value) {
    startClockInterval();
  } else {
    stopClockInterval();
    clockSignal.value = false;
  }
  nextTick(() => { evaluateCircuit(); });
};

const startClockInterval = () => {
  stopClockInterval(); 
  const halfPeriodMs = (1000 / clockHz.value) / 2;
  
  clockIntervalId = setInterval(() => {
    if (isPowerOn.value && isClockRunning.value) {
      clockSignal.value = !clockSignal.value;
      nextTick(() => { evaluateCircuit(); });
    }
  }, halfPeriodMs);
};

const stopClockInterval = () => {
  if (clockIntervalId) {
    clearInterval(clockIntervalId);
    clockIntervalId = null;
  }
};

const updateClockInterval = () => {
  if (isClockRunning.value && isPowerOn.value) {
    startClockInterval();
  }
};

const triggerManualClock = (type) => {
  if (!isPowerOn.value) return;
  
  if (type === 'jk') {
    ffJK.cp = true;
    evaluateCircuit();
    setTimeout(() => {
      ffJK.cp = false;
      evaluateCircuit();
    }, 50);
  } else if (type === 'd') {
    ffD.cp = true;
    evaluateCircuit();
    setTimeout(() => {
      ffD.cp = false;
      evaluateCircuit();
    }, 50);
  }
};

// === CORE ENGINE SIMULATION (DENGAN RECURSION SOLVER UNTUK RIPPLE COUNTER & RESET PIN) ===
const evaluateCircuit = () => {
  if (!isPowerOn.value) {
    gates.forEach(g => {
      if (g.type === 'JK-FF') g.inputs = [true, false, true, true];
      else if (g.type === 'D-FF') g.inputs = [false, false];
      else g.inputs.fill(false);
      g.output = false;
    });
    leds.fill(false);
    ffJK.j = ffJK.k = ffJK.cp = ffJK.q = false;
    ffJK.clr = true;
    ffD.d = ffD.cp = ffD.q = false;
    Object.keys(decoderInputs).forEach(k => decoderInputs[k] = false);
    Object.keys(decoderOutputs).forEach(k => decoderOutputs[k] = false);
    Object.keys(segmentState).forEach(k => segmentState[k] = false);
    connections.forEach(conn => { conn.signalValue = false; });
    stopClockInterval();
    isClockRunning.value = false;
    clockSignal.value = false;
    return;
  }

  const previousStates = {
    modularCp: {},
    fixedJkCp: ffJK.cp,
    fixedDCp: ffD.cp
  };
  
  gates.forEach(g => {
    if (g.type === 'JK-FF' || g.type === 'D-FF') {
      previousStates.modularCp[g.id] = g.inputs[1];
    }
  });

  let isStable = false;
  let iterations = 0;
  const maxIterations = 8; 

  while (!isStable && iterations < maxIterations) {
    let changed = false;

    // A. Alirkan sinyal
    connections.forEach(conn => {
      let sourceVal = false;

      if (conn.source.type === 'sw') {
        sourceVal = switches[conn.source.payload] || false;
      } else if (conn.source.type === 'sig_out') {
        sourceVal = clockSignal.value && isClockRunning.value;
      } else if (conn.source.type === 'gate_out') {
        const sourceGate = gates.find(g => g.id === conn.source.payload);
        sourceVal = sourceGate ? sourceGate.output : false;
      } else if (conn.source.type === 'gate_out_secondary') {
        const sourceGate = gates.find(g => g.id === conn.source.payload);
        sourceVal = sourceGate ? !sourceGate.output : true; 
      } else if (conn.source.type === 'ff_jk_out') {
        sourceVal = conn.source.payload === 'q' ? ffJK.q : !ffJK.q;
      } else if (conn.source.type === 'ff_d_out') {
        sourceVal = conn.source.payload === 'q' ? ffD.q : !ffD.q;
      } else if (conn.source.type === 'decoder_out') {
        sourceVal = decoderOutputs[conn.source.payload] || false;
      }

      if (conn.signalValue !== sourceVal) {
        conn.signalValue = sourceVal;
        changed = true;
      }
    });

    // B. Reset temporer state input pin penerima
    const tempInputs = {
      gates: {},
      leds: Array(8).fill(false),
      ffJK: { j: false, k: false, cp: false, clr: true }, // Default reset tidak terpemicu (CLR = HIGH)
      ffD: { d: false, cp: false },
      decoderInputs: { A: false, B: false, C: false, D: false },
      segmentState: { a: false, b: false, c: false, d: false, e: false, f: false, g: false }
    };

    gates.forEach(g => {
      if (g.type === 'JK-FF') {
        // [J, CP, K, CLR] -> CLR default bernilai true (HIGH)
        tempInputs.gates[g.id] = [true, false, true, true]; 
      } else if (g.type === 'D-FF') {
        tempInputs.gates[g.id] = [false, false];
      } else {
        tempInputs.gates[g.id] = Array(g.inputs.length).fill(false);
      }
    });

    // C. Ambil sinyal dari kabel terpasang
    connections.forEach(conn => {
      const val = conn.signalValue;

      if (conn.target.type === 'gate_in') {
        const targetId = conn.target.payload.id;
        const pinIdx = conn.target.payload.pin;
        if (tempInputs.gates[targetId]) {
          tempInputs.gates[targetId][pinIdx] = val;
        }
      } else if (conn.target.type === 'led') {
        tempInputs.leds[conn.target.payload] = val;
      } else if (conn.target.type === 'ff_jk_in') {
        if (conn.target.payload === 'j') tempInputs.ffJK.j = val;
        if (conn.target.payload === 'k') tempInputs.ffJK.k = val;
        if (conn.target.payload === 'cp') tempInputs.ffJK.cp = val;
        if (conn.target.payload === 'clr') tempInputs.ffJK.clr = val;
      } else if (conn.target.type === 'ff_d_in') {
        if (conn.target.payload === 'd') tempInputs.ffD.d = val;
        if (conn.target.payload === 'cp') tempInputs.ffD.cp = val;
      } else if (conn.target.type === 'decoder_in') {
        tempInputs.decoderInputs[conn.target.payload] = val;
      } else if (conn.target.type === 'seg_phys') {
        tempInputs.segmentState[conn.target.payload] = val;
      }
    });

    // D. Logika Gerbang Kombinasional
    gates.forEach(g => {
      if (g.type === 'JK-FF' || g.type === 'D-FF') return;
      
      const iArr = tempInputs.gates[g.id];
      let newOut = false;
      switch (g.type) {
        case 'AND':  newOut = iArr[0] && iArr[1]; break;
        case 'OR':   newOut = iArr[0] || iArr[1]; break;
        case 'NOT':  newOut = !iArr[0]; break;
        case 'NAND': newOut = !(iArr[0] && iArr[1]); break;
        case 'NOR':  newOut = !(iArr[0] || iArr[1]); break;
        case 'XOR':  newOut = iArr[0] !== iArr[1]; break;
      }
      
      if (g.output !== newOut) {
        g.output = newOut;
        changed = true;
      }
      g.inputs = iArr;
    });

    // E. Logika Modular Flip-Flops dengan Reset (Active-LOW CLR)
    gates.forEach(g => {
      if (g.type !== 'JK-FF' && g.type !== 'D-FF') return;

      const nextInputs = tempInputs.gates[g.id];
      
      if (g.type === 'JK-FF') {
        const clrVal = nextInputs[3]; // Pin CLR

        // LOGIKA RESET KILAT: Jika CLR bernilai LOW (false), paksa output Q langsung ke 0!
        if (!clrVal) {
          if (g.output !== false) {
            g.output = false;
            changed = true;
          }
        } else {
          // Logika Clock Normal hanya dievaluasi jika CLR dalam kondisi HIGH
          const prevCp = previousStates.modularCp[g.id] || false;
          const currCp = nextInputs[1];

          if (prevCp && !currCp) { // Falling edge
            const j = nextInputs[0];
            const k = nextInputs[2];
            const oldQ = g.output;

            let nextQ = oldQ;
            if (j && k) nextQ = !oldQ;      
            else if (j && !k) nextQ = true;  
            else if (!j && k) nextQ = false; 

            if (g.output !== nextQ) {
              g.output = nextQ;
              changed = true;
            }
          }
        }
      } else if (g.type === 'D-FF') {
        const prevCp = previousStates.modularCp[g.id] || false;
        const currCp = nextInputs[1];
        if (prevCp && !currCp) {
          const dVal = nextInputs[0];
          if (g.output !== dVal) {
            g.output = dVal;
            changed = true;
          }
        }
      }

      g.inputs = nextInputs;
      previousStates.modularCp[g.id] = nextInputs[1];
    });

    // F. Evaluasi Fixed JK-FF dengan Reset (Active-LOW CLR)
    const nextJkClr = tempInputs.ffJK.clr;
    if (!nextJkClr) {
      if (ffJK.q !== false) {
        ffJK.q = false;
        changed = true;
      }
    } else {
      const nextJkCp = tempInputs.ffJK.cp;
      if (previousStates.fixedJkCp && !nextJkCp) {
        const { j, k } = tempInputs.ffJK;
        let nextQ = ffJK.q;
        if (j && k) nextQ = !ffJK.q;
        else if (j && !k) nextQ = true;
        else if (!j && k) nextQ = false;
        
        if (ffJK.q !== nextQ) {
          ffJK.q = nextQ;
          changed = true;
        }
      }
    }
    ffJK.j = tempInputs.ffJK.j;
    ffJK.k = tempInputs.ffJK.k;
    ffJK.cp = tempInputs.ffJK.cp;
    ffJK.clr = nextJkClr;
    previousStates.fixedJkCp = tempInputs.ffJK.cp;

    // Fixed D-FF (tanpa reset pin)
    const nextDCp = tempInputs.ffD.cp;
    if (previousStates.fixedDCp && !nextDCp) {
      if (ffD.q !== tempInputs.ffD.d) {
        ffD.q = tempInputs.ffD.d;
        changed = true;
      }
    }
    ffD.d = tempInputs.ffD.d;
    ffD.cp = nextDCp;
    previousStates.fixedDCp = nextDCp;

    for (let i = 0; i < 8; i++) {
      if (leds[i] !== tempInputs.leds[i]) {
        leds[i] = tempInputs.leds[i];
        changed = true;
      }
    }

    decoderInputs.A = tempInputs.decoderInputs.A;
    decoderInputs.B = tempInputs.decoderInputs.B;
    decoderInputs.C = tempInputs.decoderInputs.C;
    decoderInputs.D = tempInputs.decoderInputs.D;

    Object.keys(segmentState).forEach(k => {
      segmentState[k] = tempInputs.segmentState[k];
    });

    if (!changed) {
      isStable = true;
    }
    iterations++;
  }

  // G. Evaluasi Akhir BCD ke Decoder
  const valA = decoderInputs.A ? 1 : 0;
  const valB = decoderInputs.B ? 2 : 0;
  const valC = decoderInputs.C ? 4 : 0;
  const valD = decoderInputs.D ? 8 : 0;
  const decimalVal = valA + valB + valC + valD;

  Object.keys(decoderOutputs).forEach(k => decoderOutputs[k] = false);

  if (isPowerOn.value && decimalVal <= 9) {
    const map = [
      { a:1, b:1, c:1, d:1, e:1, f:1, g:0 }, 
      { a:0, b:1, c:1, d:0, e:0, f:0, g:0 }, 
      { a:1, b:1, c:0, d:1, e:1, f:0, g:1 }, 
      { a:1, b:1, c:1, d:1, e:0, f:0, g:1 }, 
      { a:0, b:1, c:1, d:0, e:0, f:1, g:1 }, 
      { a:1, b:0, c:1, d:1, e:0, f:1, g:1 }, 
      { a:1, b:0, c:1, d:1, e:1, f:1, g:1 }, 
      { a:1, b:1, c:1, d:0, e:0, f:0, g:0 }, 
      { a:1, b:1, c:1, d:1, e:1, f:1, g:1 }, 
      { a:1, b:1, c:1, d:1, e:0, f:1, g:1 }  
    ];
    const currentMap = map[decimalVal];
    Object.keys(currentMap).forEach(k => {
      decoderOutputs[k] = !!currentMap[k];
      const physicalSegCable = connections.find(c => c.source.type === 'decoder_out' && c.source.payload === k && c.target.type === 'seg_phys');
      if (physicalSegCable) {
        segmentState[k] = !!currentMap[k];
      }
    });
  }
};

watch([isPowerOn, () => [...switches], () => connections.length], () => {
  nextTick(() => {
    try { evaluateCircuit(); } catch (err) { console.error(err); }
  });
});

const getPinCenterCoordinates = (elementId) => {
  const pinEl = document.getElementById(elementId);
  const boardEl = boardRef.value;
  if (!pinEl || !boardEl) return { x: 0, y: 0 };

  const pinRect = pinEl.getBoundingClientRect();
  const boardRect = boardEl.getBoundingClientRect();

  return {
    x: (pinRect.left + pinRect.width / 2) - boardRect.left,
    y: (pinRect.top + pinRect.height / 2) - boardRect.top
  };
};

const handleMouseMove = (e) => {
  if (!activeSourcePin.value || !boardRef.value) return;
  const boardRect = boardRef.value.getBoundingClientRect();
  tempTargetCoords.value = {
    x: e.clientX - boardRect.left,
    y: e.clientY - boardRect.top
  };
};

const handleKeyDown = (e) => {
  if (e.key === 'Escape') cancelWiring();
};

const cancelWiring = () => {
  activeSourcePin.value = null;
  tempTargetCoords.value = null;
};

const isPinSelected = (type, payload) => {
  if (!activeSourcePin.value) return false;
  if (activeSourcePin.value.type !== type) return false;
  
  if (typeof payload === 'object' && payload !== null) {
    return activeSourcePin.value.payload.id === payload.id && activeSourcePin.value.payload.pin === payload.pin;
  }
  return activeSourcePin.value.payload === payload;
};

const handlePinClick = (type, payload) => {
  if (!isPowerOn.value) return;

  let elementId = '';
  if (type === 'sw') elementId = `pin-sw-${payload}`;
  else if (type === 'sig_out') elementId = `pin-sig-out`;
  else if (type === 'led') elementId = `pin-led-${payload}`;
  else if (type === 'gate_in') elementId = `pin-gate_in-${payload.id}-${payload.pin}`;
  else if (type === 'gate_out') elementId = `pin-gate_out-${payload}`;
  else if (type === 'gate_out_secondary') elementId = `pin-gate_out_secondary-${payload}`;
  else if (type === 'ff_jk_in') elementId = `pin-ff_jk-${payload}`;
  else if (type === 'ff_jk_out') elementId = `pin-ff_jk-${payload}`;
  else if (type === 'ff_d_in') elementId = `pin-ff_d-${payload}`;
  else if (type === 'ff_d_out') elementId = `pin-ff_d-${payload}`;
  else if (type === 'decoder_in') elementId = `pin-dec-in-${payload}`;
  else if (type === 'decoder_out') elementId = `pin-dec-out-${payload}`;
  else if (type === 'seg_phys') elementId = `pin-seg-phys-${payload}`;

  const pinEl = document.getElementById(elementId);
  if (!pinEl) return;

  if (!activeSourcePin.value) {
    const isValidSource = ['sw', 'sig_out', 'gate_out', 'gate_out_secondary', 'ff_jk_out', 'ff_d_out', 'decoder_out'].includes(type);
    if (!isValidSource) {
      alert('Sambungan kabel harus dimulai dari PIN Output!');
      return;
    }
    
    activeSourcePin.value = { type, payload, elementId };
    activeSourceCoords.value = getPinCenterCoordinates(elementId);
    tempTargetCoords.value = { ...activeSourceCoords.value };
  } else {
    const source = activeSourcePin.value;
    
    if (source.elementId === elementId) {
      cancelWiring();
      return;
    }

    const isValidTarget = ['gate_in', 'led', 'ff_jk_in', 'ff_d_in', 'decoder_in', 'seg_phys'].includes(type);
    if (!isValidTarget) {
      alert('Sambungan kabel harus diakhiri di PIN Input!');
      cancelWiring();
      return;
    }

    const isDuplicate = connections.some(conn => conn.source.elementId === source.elementId && conn.target.elementId === elementId);
    if (isDuplicate) {
      alert('Kabel ini sudah terhubung!');
      cancelWiring();
      return;
    }

    const targetCoords = getPinCenterCoordinates(elementId);

    connections.push({
      source: { type: source.type, payload: source.payload, elementId: source.elementId },
      target: { type, payload, elementId },
      sourceCoords: { ...activeSourceCoords.value },
      targetCoords: targetCoords,
      signalValue: false 
    });

    cancelWiring();
    nextTick(() => { evaluateCircuit(); });
  }
};

const removeConnection = (index) => {
  connections.splice(index, 1);
  nextTick(() => { evaluateCircuit(); });
};

const getBezierCurve = (p1, p2) => {
  if (!p1 || !p2) return 'M 0 0';
  const dx = Math.abs(p2.x - p1.x) * 0.5;
  const dy = Math.abs(p2.y - p1.y) * 0.5;
  const cp1x = p1.x + (p2.x > p1.x ? dx : -dx);
  const cp1y = p1.y + dy + 15;
  const cp2x = p2.x + (p2.x > p1.x ? -dx : dx);
  const cp2y = p2.y + dy + 15;

  return `M ${p1.x} ${p1.y} C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${p2.x} ${p2.y}`;
};

const togglePower = () => {
  isPowerOn.value = !isPowerOn.value;
  if (!isPowerOn.value) {
    switches.fill(false);
    stopClockInterval();
    isClockRunning.value = false;
    clockSignal.value = false;
  }
  nextTick(() => { evaluateCircuit(); });
};

const toggleSwitch = (index) => {
  if (!isPowerOn.value) return;
  switches[index] = !switches[index];
};

onMounted(() => {
  window.addEventListener('mousemove', handleMouseMove);
  window.addEventListener('keydown', handleKeyDown);
  nextTick(() => { evaluateCircuit(); });
});

onUnmounted(() => {
  window.removeEventListener('mousemove', handleMouseMove);
  window.removeEventListener('keydown', handleKeyDown);
  stopClockInterval();
});
</script>

<style scoped>
/* (Seluruh CSS lama dipertahankan, ditambahkan warna visual khusus untuk pin CLR) */
.isolated-board-wrapper {
  all: unset;
  display: block;
  box-sizing: border-box;
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
  color: #e2e8f0;
  width: 100%;
}

.isolated-board-wrapper *, 
.isolated-board-wrapper *::before, 
.isolated-board-wrapper *::after {
  box-sizing: border-box !important;
}

.board-header {
  display: flex !important;
  justify-content: space-between !important;
  align-items: center !important;
  background-color: rgba(15, 23, 42, 0.9) !important;
  padding: 14px 18px !important;
  border-radius: 12px !important;
  border: 1px solid #1e293b !important;
  margin-bottom: 20px !important;
}

.title-text {
  font-size: 13px !important;
  font-weight: 700 !important;
  color: #f1f5f9 !important;
  margin: 0 !important;
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  letter-spacing: 0.05em !important;
}

.pulse-indicator {
  width: 8px;
  height: 8px;
  background-color: #00ff66;
  border-radius: 50%;
  box-shadow: 0 0 8px #00ff66;
}

.subtitle-text {
  font-size: 9px !important;
  color: #64748b !important;
  margin: 3px 0 0 0 !important;
}

.power-controller {
  display: flex !important;
  align-items: center !important;
  gap: 12px !important;
  background-color: #020617 !important;
  padding: 6px 14px !important;
  border-radius: 8px !important;
  border: 1px solid #1e293b !important;
}

.power-label {
  font-size: 9px !important;
  font-weight: 700 !important;
  color: #94a3b8 !important;
}

.power-switch {
  width: 34px !important;
  height: 18px !important;
  border-radius: 9999px !important;
  padding: 2px !important;
  border: none !important;
  cursor: pointer !important;
  transition: background-color 0.2s !important;
  display: flex !important;
  align-items: center;
}

.power-on { background-color: #059669 !important; }
.power-off { background-color: #475569 !important; }

.switch-knob {
  width: 14px !important;
  height: 14px !important;
  background-color: #ffffff !important;
  border-radius: 50% !important;
  transition: transform 0.2s !important;
}

.knob-on { transform: translateX(16px) !important; }
.knob-off { transform: translateX(0) !important; }

.power-led {
  width: 8px !important;
  height: 8px !important;
  border-radius: 50% !important;
  transition: all 0.3s !important;
}

.led-active {
  background-color: #00ff66 !important;
  box-shadow: 0 0 8px #00ff66 !important;
}

.led-inactive { background-color: #1e293b !important; }

.hardware-grid {
  display: flex !important;
  flex-direction: row !important;
  gap: 14px !important;
  background-color: #020617 !important;
  padding: 16px !important;
  border-radius: 12px !important;
  border: 2px solid #1e293b !important;
  min-height: 680px !important;
  position: relative !important;
  width: 100% !important;
}

.cable-svg-layer {
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  width: 100% !important;
  height: 100% !important;
  pointer-events: none !important;
  z-index: 100 !important;
}

.interactive-cable {
  pointer-events: stroke !important;
  cursor: pointer !important;
  transition: stroke-width 0.15s !important;
}

.cable-glow-active {
  filter: drop-shadow(0 0 3px #00ff66);
}
.cable-glow-inactive {
  filter: drop-shadow(0 0 2px #ff3333);
}

.interactive-cable:hover {
  stroke-width: 6px !important;
  stroke: #ffaa00 !important;
  filter: drop-shadow(0 0 4px #ffaa00) !important;
}

.temp-cable-glow {
  filter: drop-shadow(0 0 3px #00ccff);
}

.panel-column {
  background-color: rgba(15, 23, 42, 0.7) !important;
  border: 1px solid #1e293b !important;
  border-radius: 8px !important;
  padding: 10px !important;
  display: flex !important;
  flex-direction: column !important;
  z-index: 20 !important;
}

.left-panel-sw { width: 20% !important; }
.right-panel-leds { width: 18% !important; }
.center-panel-chips {
  width: 62% !important;
  display: flex !important;
  flex-direction: column !important;
  gap: 12px !important;
}

.switches-list {
  display: flex !important;
  flex-direction: column !important;
  gap: 4px !important;
}

.column-content-leds {
  display: flex !important;
  flex-direction: column !important;
  justify-content: space-between !important;
  flex-grow: 1 !important;
}

.panel-title {
  font-size: 9px !important;
  font-weight: 700 !important;
  text-align: center !important;
  color: #64748b !important;
  border-bottom: 1px solid #1e293b !important;
  padding-bottom: 6px !important;
  margin-bottom: 10px !important;
  letter-spacing: 0.1em !important;
  display: block !important;
}

.hardware-item {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  background-color: rgba(2, 6, 23, 0.9) !important;
  padding: 5px 8px !important;
  border-radius: 6px !important;
  border: 1px solid #1e293b !important;
}

.bg-dark-item {
  background-color: rgba(2, 6, 23, 0.9) !important;
}

.socket-group {
  display: flex !important;
  flex-direction: column-reverse !important;
  align-items: center !important;
  gap: 4px !important;
}

.item-label {
  font-size: 9px !important;
  font-weight: 700 !important;
  color: #64748b !important;
}

.socket-pin {
  width: 16px !important;
  height: 16px !important;
  border-radius: 50% !important;
  border: 2px solid #000000 !important;
  cursor: pointer !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: transform 0.1s, border-color 0.2s !important;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.8) !important;
  z-index: 30 !important;
}

.socket-pin:hover {
  transform: scale(1.2) !important;
  border-color: #00ccff !important;
}

.selected-pin {
  border-color: #00ccff !important;
  box-shadow: 0 0 10px #00ccff !important;
  animation: pulse-pin 0.8s infinite alternate;
}

@keyframes pulse-pin {
  from { transform: scale(1); }
  to { transform: scale(1.2); }
}

.socket-input-style { background-color: #334155 !important; }
.socket-output-style { background-color: #1e3a8a !important; }
.socket-red-dark-style { background-color: #7f1d1d !important; }

.socket-inner-metallic {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background-color: #94a3b8;
  border: 1px solid #475569;
}

.socket-glow-green-neon {
  background-color: #00ff66 !important;
  box-shadow: 0 0 10px #00ff66, inset 0 2px 4px rgba(0,0,0,0.4) !important;
}

.socket-glow-blue-neon {
  background-color: #00ccff !important;
  box-shadow: 0 0 10px #00ccff, inset 0 2px 4px rgba(0,0,0,0.4) !important;
}

.sig-gen-panel {
  display: flex !important;
  flex-direction: column !important;
}

.pulse-led {
  width: 14px !important;
  height: 14px !important;
  border-radius: 50% !important;
  border: 1px solid #000000 !important;
  transition: all 0.08s ease-in-out !important;
}

.pulse-led-inactive {
  background-color: #0c4a6e !important;
}

.pulse-led-active {
  background-color: #0ea5e9 !important;
  box-shadow: 0 0 12px #0ea5e9, inset 0 1px 3px rgba(255, 255, 255, 0.6) !important;
}

.speed-control-box {
  background-color: rgba(2, 6, 23, 0.9) !important;
  border: 1px solid #1e293b !important;
  border-radius: 6px !important;
  padding: 6px 8px !important;
  display: flex !important;
  flex-direction: column !important;
  gap: 4px !important;
}

.speed-label {
  font-size: 8px !important;
  color: #64748b !important;
  text-align: center !important;
}

.speed-slider {
  width: 100% !important;
  cursor: pointer !important;
  accent-color: #0ea5e9 !important;
}

.btn-sig-switch {
  width: 100% !important;
  font-size: 8px !important;
  font-weight: bold !important;
  padding: 6px 0 !important;
  border-radius: 4px !important;
  cursor: pointer !important;
  border: none !important;
  transition: all 0.15s !important;
}

.btn-sig-off {
  background-color: #1e293b !important;
  color: #94a3b8 !important;
  border: 1px solid #334155 !important;
}

.btn-sig-off:hover:not(:disabled) {
  background-color: #334155 !important;
  color: #ffffff !important;
}

.btn-sig-on {
  background-color: #0284c7 !important;
  color: #ffffff !important;
  box-shadow: 0 0 8px rgba(14, 165, 233, 0.4) !important;
}

.btn-sig-on:hover:not(:disabled) {
  background-color: #0369a1 !important;
}

.btn-trigger-clock {
  background-color: #2563eb !important;
  border: 1px solid #1d4ed8 !important;
  color: #ffffff !important;
  font-size: 8px !important;
  padding: 1px 4px !important;
  border-radius: 3px !important;
  cursor: pointer !important;
  font-weight: bold;
}

.btn-trigger-clock:hover {
  background-color: #3b82f6 !important;
}

.toggle-switch-btn {
  width: 24px !important;
  height: 38px !important;
  background-color: #0f172a !important;
  border-radius: 4px !important;
  border: 1px solid #1e293b !important;
  padding: 2px !important;
  cursor: pointer !important;
  display: flex !important;
  flex-direction: column !important;
  justify-content: space-between !important;
}

.disabled-switch {
  opacity: 0.3 !important;
  cursor: not-allowed !important;
}

.toggle-knob {
  width: 18px !important;
  height: 14px !important;
  background-color: #475569 !important;
  border-radius: 2px !important;
  transition: all 0.12s !important;
  box-shadow: 0 2px 3px rgba(0,0,0,0.5) !important;
}

.toggle-knob-active {
  transform: translateY(18px) !important;
  background-color: #00ff66 !important;
  box-shadow: 0 0 6px rgba(0,255,102,0.5) !important;
}

.inner-row {
  background-color: rgba(15, 23, 42, 0.5) !important;
  border: 1px solid #1e293b !important;
  border-radius: 8px !important;
  padding: 10px !important;
  display: flex !important;
  flex-direction: column !important;
}

.row-top-gates { height: 46% !important; }

.panel-title-row {
  display: flex !important;
  justify-content: space-between !important;
  align-items: center !important;
  margin-bottom: 10px !important;
  border-bottom: 1px solid #1e293b !important;
  padding-bottom: 6px !important;
}

.panel-title-row .panel-title {
  border-bottom: none !important;
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
}

.selection-notice {
  font-size: 8px !important;
  font-weight: bold !important;
  color: #00ccff !important;
  letter-spacing: 0.05em !important;
}

.gates-grid-container {
  display: grid !important;
  grid-template-columns: repeat(3, 1fr) !important;
  gap: 10px !important;
  flex-grow: 1 !important;
}

.gate-chip-box {
  background-color: rgba(2, 6, 23, 0.9) !important;
  border: 1px solid #1e293b !important;
  border-radius: 8px !important;
  padding: 6px 8px !important;
  display: flex !important;
  flex-direction: column !important;
  justify-content: space-between !important;
  align-items: center !important;
  transition: all 0.2s ease !important;
}

.slot-ready-to-replace {
  border: 2px dashed #00ccff !important;
  background-color: rgba(0, 204, 255, 0.05) !important;
  box-shadow: 0 0 12px rgba(0, 204, 255, 0.2) !important;
}

.chip-top-bar {
  display: flex !important;
  justify-content: space-between !important;
  align-items: center !important;
  width: 100% !important;
  margin-bottom: 4px !important;
}

.chip-serial {
  font-size: 8px !important;
  color: #00ccff !important;
  font-weight: bold !important;
}

.btn-replace-chip {
  background-color: #1e293b !important;
  border: 1px solid #334155 !important;
  color: #94a3b8 !important;
  font-size: 7.5px !important;
  font-weight: 700 !important;
  padding: 1px 5px !important;
  border-radius: 3px !important;
  cursor: pointer !important;
  transition: all 0.15s !important;
}

.btn-replace-chip:hover {
  background-color: #00ccff !important;
  border-color: #00ccff !important;
  color: #020617 !important;
}

.btn-replace-active {
  background-color: #e11d48 !important;
  border-color: #e11d48 !important;
  color: #ffffff !important;
}

.gate-symbol-container {
  width: 100% !important;
  height: 50px !important;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  position: relative !important;
}

.gate-logic-svg {
  width: 80px !important;
  height: 40px !important;
}

.svg-gate-path {
  fill: #0f172a !important;
  stroke: #475569 !important;
  stroke-width: 2 !important;
}

.svg-ff-rect {
  fill: #030712 !important;
  stroke: #0369a1 !important;
  stroke-width: 1.5 !important;
}

.svg-ff-text {
  fill: #64748b !important;
  font-size: 9px !important;
  font-weight: bold !important;
}

.text-cp {
  fill: #38bdf8 !important;
  font-size: 7px !important;
}

.gate-type-badge {
  position: absolute !important;
  bottom: -2px !important;
  font-size: 8px !important;
  font-weight: bold !important;
  color: #00ccff !important;
  background-color: rgba(2, 6, 23, 0.8) !important;
  padding: 0px 6px !important;
  border-radius: 4px !important;
  border: 1px solid #1e293b !important;
  letter-spacing: 0.05em !important;
}

.ff-chip-bg .gate-type-badge {
  color: #38bdf8 !important;
  border-color: #0369a1 !important;
}

.gate-pins-row {
  display: flex !important;
  justify-content: space-between !important;
  width: 100% !important;
  padding: 0 4px !important;
  margin-top: 6px !important;
}

.gate-inputs-flex {
  display: flex !important;
  gap: 10px !important;
}

.ff-output-flex {
  display: flex !important;
  gap: 10px !important;
}

.socket-group-mini {
  display: flex !important;
  flex-direction: column !important;
  align-items: center;
}

.pin-label {
  font-size: 8px !important;
  color: #475569 !important;
  font-weight: bold !important;
  margin-bottom: 2px !important;
}

.storage-row {
  padding: 8px 12px !important;
  background-color: rgba(2, 6, 23, 0.6) !important;
}

.storage-chips-flex {
  display: flex !important;
  flex-direction: row !important;
  justify-content: space-around !important;
  gap: 8px !important;
  margin-top: 4px !important;
}

.storage-chip-item {
  flex: 1 !important;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  padding: 6px 10px !important;
  border-radius: 6px !important;
  border: 1px solid #1e293b !important;
  cursor: pointer !important;
  transition: all 0.2s ease !important;
  outline: none !important;
}

.storage-active-btn {
  background-color: #0f172a !important;
  border-color: #334155 !important;
  color: #00ccff !important;
}

.storage-active-btn:hover {
  background-color: rgba(0, 204, 255, 0.1) !important;
  border-color: #00ccff !important;
  box-shadow: 0 0 8px rgba(0, 204, 255, 0.3) !important;
  transform: translateY(-2px) !important;
}

.ff-storage-highlight {
  border-color: #0369a1 !important;
}
.ff-storage-highlight.storage-active-btn {
  color: #38bdf8 !important;
}
.ff-storage-highlight.storage-active-btn:hover {
  background-color: rgba(14, 165, 233, 0.1) !important;
  border-color: #38bdf8 !important;
  box-shadow: 0 0 8px rgba(56, 189, 248, 0.3) !important;
}

.storage-disabled-btn {
  background-color: #020617 !important;
  border-color: #0f172a !important;
  color: #475569 !important;
  cursor: not-allowed !important;
  opacity: 0.5 !important;
}

.storage-gate-name {
  font-size: 10px !important;
  font-weight: 800 !important;
}

.storage-gate-desc {
  font-size: 6.5px !important;
  color: #64748b !important;
  margin-top: 1px !important;
}

.row-bottom-modules {
  height: 38% !important;
  display: flex !important;
  flex-direction: row !important;
  gap: 12px !important;
}

.bottom-half-panel {
  width: 50% !important;
  display: flex !important;
  flex-direction: column !important;
}

.border-left-divider {
  border-left: 1px dashed #1e293b !important;
  padding-left: 12px !important;
}

.flipflop-horizontal-container {
  display: flex !important;
  flex-direction: row !important;
  gap: 8px !important;
  flex-grow: 1 !important;
}

.sub-chip-box-horizontal {
  flex: 1 !important;
  background-color: rgba(2, 6, 23, 0.9) !important;
  border: 1px solid #1e293b !important;
  border-radius: 8px !important;
  padding: 6px 10px !important;
  display: flex !important;
  flex-direction: column !important;
  justify-content: space-between !important;
  position: relative !important;
}

.chip-serial-tab {
  font-size: 8px !important;
  color: #00ccff !important;
  font-weight: bold !important;
  text-align: center !important;
  border-bottom: 1px solid #1e293b !important;
  padding-bottom: 3px !important;
  margin-bottom: 4px !important;
  display: block !important;
}

.ff-pins-wrapper {
  display: flex !important;
  flex-direction: row !important;
  justify-content: space-between !important;
  align-items: center !important;
  flex-grow: 1 !important;
}

.ff-pins-column {
  display: flex !important;
  flex-direction: column !important;
  gap: 4px !important;
  width: 48% !important;
}

.border-right-dark {
  border-right: 1px dashed #0f172a !important;
}

.socket-group-mini-row {
  display: flex !important;
  flex-direction: row !important;
  align-items: center !important;
  gap: 6px !important;
}

.socket-group-mini-row-rev {
  display: flex !important;
  flex-direction: row-reverse !important;
  align-items: center !important;
  gap: 6px !important;
  justify-content: flex-start !important;
}

.pin-label-left {
  font-size: 8px !important;
  color: #475569 !important;
  width: 14px !important;
  text-align: left !important;
}

.pin-label-right {
  font-size: 8px !important;
  color: #475569 !important;
  width: 14px !important;
  text-align: right !important;
}

.decoder-chip-wide {
  width: 100% !important;
}

.decoder-pins-wrapper {
  display: flex !important;
  flex-direction: row !important;
  justify-content: space-between !important;
  align-items: center !important;
  flex-grow: 1 !important;
  gap: 8px !important;
}

.decoder-pins-section {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  background-color: rgba(15, 23, 42, 0.4) !important;
  padding: 4px 8px !important;
  border-radius: 6px !important;
  flex: 1 !important;
}

.section-label {
  font-size: 7px !important;
  color: #64748b !important;
  margin-bottom: 4px !important;
}

.decoder-pins-line {
  display: flex !important;
  gap: 5px !important;
}

.pin-label-micro {
  font-size: 7px !important;
  color: #475569 !important;
  margin-bottom: 2px !important;
}

.right-panel-leds .hardware-item {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  background-color: rgba(2, 6, 23, 0.9) !important;
  padding: 6px 10px !important;
  border-radius: 6px !important;
  border: 1px solid #1e293b !important;
}

.led-grid-vertical {
  display: flex !important;
  flex-direction: column !important;
  gap: 4px !important;
}

.socket-group-led {
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
}

.led-indicator {
  width: 12px !important;
  height: 12px !important;
  border-radius: 50% !important;
  border: 1px solid #000000 !important;
  transition: all 0.2s !important;
}

.led-red-dark { background-color: #3b0712 !important; }
.led-red-glow {
  background-color: #ff3333 !important;
  box-shadow: 0 0 10px #ff3333, inset 0 1px 3px rgba(255,255,255,0.4) !important;
}

.seven-segment-container {
  background-color: rgba(2, 6, 23, 0.9) !important;
  border: 1px solid #1e293b !important;
  border-radius: 8px !important;
  padding: 8px 4px !important;
  text-align: center !important;
  margin-top: 8px !important;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  gap: 6px !important;
}

.seg-svg-wrapper {
  background-color: #05050a !important;
  border: 2px solid #1e293b !important;
  padding: 6px 14px !important;
  border-radius: 6px !important;
  width: 60px !important;
  height: 90px !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.seven-seg-svg {
  width: 100% !important;
  height: 100% !important;
}

.seg-path {
  stroke: #020617 !important;
  stroke-width: 0.8 !important;
  stroke-linejoin: round !important;
  transition: all 0.15s ease-in-out !important;
}

.seg-inactive { fill: #1a0508 !important; }
.seg-active {
  fill: #ff2222 !important; 
  filter: drop-shadow(0 0 2px #ff2222);
}

.seg-inputs-grid {
  display: grid !important;
  grid-template-columns: repeat(4, 1fr) !important;
  gap: 4px !important;
  width: 100% !important;
  padding: 4px !important;
  background-color: rgba(15, 23, 42, 0.3) !important;
  border-radius: 4px !important;
}

.display-label {
  font-size: 8px !important;
  color: #475569 !important;
  margin-top: 1px !important;
  display: block !important;
}

.status-footer {
  background-color: rgba(15, 23, 42, 0.9) !important;
  border: 1px solid #1e293b !important;
  border-radius: 8px !important;
  padding: 10px !important;
  text-align: center !important;
  margin-top: 18px !important;
}

.status-text {
  font-size: 10px !important;
  color: #64748b !important;
}

.active-text {
  color: #00ff66 !important;
  font-weight: bold !important;
}
</style>