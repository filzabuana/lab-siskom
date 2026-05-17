<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { watch, onBeforeUnmount, ref } from 'vue'

// --- 1. IMPORT LOWLIGHT DEPENDENCIES ---
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight'
import { createLowlight, common } from 'lowlight'

// Daftarkan set bahasa pemrograman standar bawaan lowlight
const lowlight = createLowlight(common)

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Tulis konten artikel di sini...'
    }
})

const emit = defineEmits(['update:modelValue'])

// State untuk mengatur mode tampilan (Editor biasa vs HTML/Source Code)
const isHtmlMode = ref(false)
const rawHtml = ref('')

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        // Matikan codeBlock bawaan StarterKit agar tidak bentrok
        StarterKit.configure({
            codeBlock: false,
        }),
        // --- 2. GANTI DENGAN CODE BLOCK LOWLIGHT ---
        CodeBlockLowlight.configure({
            lowlight,
            defaultLanguage: 'plaintext',
            HTMLAttributes: {
                class: 'theme-code-block', // Mempertahankan kelas kustom Anda
            },
        }),
    ],
    onUpdate: ({ editor }) => {
        const html = editor.getHTML()
        rawHtml.value = html
        emit('update:modelValue', html)
    },
    editorProps: {
        attributes: {
            class: 'prose dark:prose-invert max-w-none focus:outline-none min-h-[300px] p-4 bg-white dark:bg-railway-card border border-t-0 border-gray-300 dark:border-railway-border rounded-b-lg shadow-sm overflow-y-auto text-gray-900 dark:text-gray-300 font-sans',
        },
    },
})

// Sinkronisasi perubahan dari mode HTML/Source Code kembali ke Rich Editor secara aman
const updateFromHtmlView = () => {
    if (editor.value) {
        editor.value.commands.setContent(rawHtml.value, false, {
            parseOptions: {
                preserveWhitespace: 'full'
            }
        })
        emit('update:modelValue', rawHtml.value)
    }
}

// Menangani perpindahan ke mode HTML secara mulus
const toggleHtmlMode = () => {
    if (!isHtmlMode.value) {
        rawHtml.value = editor.value ? editor.value.getHTML() : ''
    } else {
        updateFromHtmlView()
    }
    isHtmlMode.value = !isHtmlMode.value
}

// Watcher sinkronisasi data dari komponen induk (Inertia form)
watch(() => props.modelValue, (value) => {
    if (!editor.value) return
    
    const currentHTML = editor.value.getHTML()
    if (currentHTML === value) return

    editor.value.commands.setContent(value, false)
    rawHtml.value = value
})

onBeforeUnmount(() => {
    editor.value?.destroy()
})
</script>

<template>
    <div v-if="editor" class="w-full font-sans">
        <div class="flex flex-wrap items-center gap-1 p-2 bg-gray-50 dark:bg-railway-dark border border-gray-300 dark:border-railway-border rounded-t-lg control-group">
            
            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleBold().run()" 
                :class="{ 
                    'bg-railway-accent text-white font-bold': editor.isActive('bold'), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('bold') 
                }"
                class="px-3 py-1 text-sm font-medium transition rounded disabled:opacity-30">
                B
            </button>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleItalic().run()" 
                :class="{ 
                    'bg-railway-accent text-white italic': editor.isActive('italic'), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('italic') 
                }"
                class="px-3 py-1 text-sm font-medium transition rounded disabled:opacity-30">
                I
            </button>

            <div class="w-px h-6 mx-1 bg-gray-300 dark:bg-railway-border"></div>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" 
                :class="{ 
                    'bg-railway-accent text-white font-bold': editor.isActive('heading', { level: 2 }), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('heading', { level: 2 }) 
                }"
                class="px-3 py-1 text-sm font-medium transition rounded disabled:opacity-30">
                H2
            </button>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" 
                :class="{ 
                    'bg-railway-accent text-white font-bold': editor.isActive('heading', { level: 3 }), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('heading', { level: 3 }) 
                }"
                class="px-3 py-1 text-sm font-medium transition rounded disabled:opacity-30">
                H3
            </button>

            <div class="w-px h-6 mx-1 bg-gray-300 dark:bg-railway-border"></div>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleBulletList().run()" 
                :class="{ 
                    'bg-railway-accent text-white': editor.isActive('bulletList'), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('bulletList') 
                }"
                class="px-3 py-1 text-sm font-medium transition rounded disabled:opacity-30">
                • List
            </button>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleOrderedList().run()" 
                :class="{ 
                    'bg-railway-accent text-white': editor.isActive('orderedList'), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('orderedList') 
                }"
                class="px-3 py-1 text-sm font-medium transition rounded disabled:opacity-30">
                1. List
            </button>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleCodeBlock().run()" 
                :class="{ 
                    'bg-railway-accent text-white': editor.isActive('codeBlock'), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('codeBlock') 
                }"
                class="px-3 py-1 text-xs font-mono transition rounded disabled:opacity-30">
                &lt;/&gt; Code
            </button>

            <select
                v-if="editor.isActive('codeBlock') && !isHtmlMode"
                :value="editor.getAttributes('codeBlock').language"
                @change="editor.commands.updateAttributes('codeBlock', { language: $event.target.value })"
                class="px-2 py-1 text-xs rounded border border-amber-300 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-200 focus:outline-none font-semibold animate-fade-in animate-duration-150">
                <option value="plaintext">Select Language (Plain Text)</option>
                <option value="html">HTML / XML</option>
                <option value="css">CSS</option>
                <option value="javascript">JavaScript (JS)</option>
                <option value="cpp">C++ / Arduino (CPP)</option>
                <option value="bash">Bash / Shell Script</option>
                <option value="python">Python</option>
                <option value="sql">SQL Database</option>
            </select>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().toggleBlockquote().run()" 
                :class="{ 
                    'bg-railway-accent text-white': editor.isActive('blockquote'), 
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white': !editor.isActive('blockquote') 
                }"
                class="px-3 py-1 text-sm font-serif transition rounded disabled:opacity-30">
                “ Quote
            </button>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().setHorizontalRule().run()" 
                class="px-3 py-1 text-sm text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white transition rounded disabled:opacity-30">
                — Garis
            </button>

            <div class="w-px h-6 mx-1 bg-gray-300 dark:bg-railway-border"></div>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().undo().run()" 
                class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-400 transition rounded hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white disabled:opacity-20">
                Undo
            </button>

            <button 
                type="button"
                :disabled="isHtmlMode"
                @click="editor.chain().focus().redo().run()" 
                class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-400 transition rounded hover:bg-gray-200 dark:hover:bg-railway-card hover:text-gray-900 dark:hover:text-white disabled:opacity-20">
                Redo
            </button>

            <div class="flex-grow flex justify-end">
                <button 
                    type="button"
                    @click="toggleHtmlMode" 
                    :class="{ 
                        'bg-amber-600 text-white font-semibold': isHtmlMode, 
                        'text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-100 dark:hover:bg-amber-900/70': !isHtmlMode 
                    }"
                    class="px-3 py-1 text-xs transition rounded flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    {{ isHtmlMode ? 'Kembali ke Editor' : 'Source Code (HTML)' }}
                </button>
            </div>
        </div>
        <div class="relative">
            <editor-content v-show="!isHtmlMode" :editor="editor" />

            <textarea 
                v-show="isHtmlMode"
                v-model="rawHtml"
                @input="updateFromHtmlView"
                placeholder="<p>Tulis struktur kode HTML di sini...</p>"
                class="w-full min-h-[300px] p-4 font-mono text-sm bg-gray-900 dark:bg-railway-card text-green-400 border border-t-0 border-gray-300 dark:border-railway-border rounded-b-lg shadow-inner focus:outline-none focus:ring-1 focus:ring-railway-accent resize-y transition-colors duration-200"
            ></textarea>
        </div>
    </div>
</template>

<style>
/* Style Global Dokumentasi Prose (Mendukung Light & Dark Mode) */
.prose ul, .prose-invert ul {
    list-style-type: disc;
    padding-left: 1.5rem;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.prose ol, .prose-invert ol {
    list-style-type: decimal;
    padding-left: 1.5rem;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.ProseMirror:focus {
    outline: none;
}

/* Style Blok Kode Bawaan Tiptap */
.prose pre, .prose-invert pre {
    background-color: #1e293b;
    color: #f8fafc;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    margin: 1rem 0;
    overflow-x: auto;
}

/* --- 3. TAMBAHKAN ATURAN WARNA TOKENS HIGHLIGHT DI SINI --- */
.prose pre .hljs-comment,
.prose pre .hljs-quote {
  color: #64748b !important;
  font-style: italic;
}

.prose pre .hljs-keyword,
.prose pre .hljs-selector-tag,
.prose pre .hljs-literal,
.prose pre .hljs-section,
.prose pre .hljs-link,
.prose pre .hljs-meta {
  color: #f43f5e !important;
  font-weight: bold;
}

.prose pre .hljs-string,
.prose pre .hljs-title,
.prose pre .hljs-name,
.prose pre .hljs-type,
.prose pre .hljs-attribute {
  color: #38bdf8 !important;
}

.prose pre .hljs-number,
.prose pre .hljs-built_in,
.prose pre .hljs-symbol,
.prose pre .hljs-bullet {
  color: #fb923c !important;
}

.prose pre .hljs-params {
  color: #cbd5e1 !important;
}

.prose code, .prose-invert code {
    font-size: 0.875em;
    background-color: rgba(148, 163, 184, 0.1);
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
}

/* Style Blockquote */
.prose blockquote, .prose-invert blockquote {
    border-left-width: 4px;
    border-color: #3b82f6;
    font-style: italic;
    padding-left: 1rem;
    margin: 1rem 0;
    color: inherit;
    opacity: 0.85;
}
</style>