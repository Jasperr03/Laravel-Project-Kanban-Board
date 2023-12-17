<script setup>
import { computed } from 'vue'
import { watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import {useForm} from '@inertiajs/vue3';
import TextareaAutosize from '@/Components/TextareaAutosize.vue';

import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
} from '@headlessui/vue'

const props = defineProps({
    card: Object
});

const isOpen = computed(() => !!props.card);

const form = useForm({
    title: props.card?.title,
    description: props.card?.description,
    redirectUrl: `/boards/${props.card?.board_id}`
});

watch(() => props.card, (card) => {
    if(card) {
        form.title = card.title;
        form.description = card.description;
        form.redirectUrl = `/boards/${card.board_id}` 
    }
});

function closeModal() {
  Inertia.get(route('boards.show', {board: props.card.board_id}), {}, {
    preserveState:true
  });
}

function onSubmit() {
    form.put(route('cards.update', {card: props.card.id}));
}

</script>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

export default {
  data() {
    return {
      dragging: false,
      media: []
    };
  },
  components: {
    AuthenticatedLayout,
  },

  methods: {
    onDroppedFiles($event) {
      this.dragging = false;

      let files = [...$event.dataTransfer.items]
        .filter(item => item.kind === 'file')
        .map(item => item.getAsFile());

      this.uploadFiles(files);
    },
    onSelectedFiles($event) {
      let files = [...$event.target.files];
      this.uploadFiles(files);
      this.$refs.files.value = null;
    },
    uploadFiles(files) {
      files.forEach(file => {
        this.media.unshift({
          file: file,
          progress: 0,
          error: null,
          uploaded: false,
          preview_url: null,
          id: null
        });
      });

      this.media.filter(media => !media.uploaded)
      .forEach(media => {
        let form = new FormData;
        form.append('file', media.file);

        axios.post(this.route('media.store'), form, {
          onUploadProgress: (event) => {
            media.progress = Math.round(event.loaded * 100 / event.total);
          },
        })
          .then(({data}) => {
            media.uploaded = true
            media.id = data.id;
            media.preview_url = data.preview_url;
          })
          .catch(error => {
            media.error = `Upload failed. Please try again later.`;

            if(error?.response.status === 422) {
            media.error = error.response.data.errors.file[0];
          }
        });
      });
      console.log(files);
    }
  },
};
</script>

<template>
  <TransitionRoot 
    appear 
    :show="isOpen" 
    as="template"
    >
    <Dialog 
        as="div" 
        @close="closeModal" 
        class="relative z-10"
    >
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-40"/>
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex justify-center items-start px-4 py-12 min-h-full text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="overflow-hidden w-full max-w-3xl text-left align-middle bg-white rounded-md shadow-xl transition-all transform"
            >
            <div class="flex flex-col sm:flex-row">
                <form
                    @submit.prevent="onSubmit"
                  class="flex-1 p-5"
                >
                  <div>
                    <label 
                        for="title"
                        class="sr-only"
                    > Title</label>
                    <TextareaAutosize 
                        v-model="form.title"
                        id="title"
                        class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring-blue-400"
                        name="title"
                        rows="1"
                    />
                  </div>
 
                  <div class="mt-4">
                    <label
                      class="inline-block mb-1 text-sm font-semibold text-gray-700"
                      for="description"
                    >Description</label>
                    <TextareaAutosize
                        v-model="form.description"
                        id="description"
                        class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring-blue-400"
                        name="description"
                        rows="4"
                    ></TextareaAutosize>
                  </div>

                  <div class="mt-4">
                    <h2 class="font-semibold text-sm mb-1 text-gray-800 leading-tight">
                      Add new media
                    </h2>
                  </div>
                  <div class=" py-12 px-4">
                    <div class="max-w-7xl mx-auto sm:px6 lg:px-8">
                      <div 
                        @drop.prevent="onDroppedFiles"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        :class="[dragging ? 'border-indigo-500' :'border-gray-400', 'flex flex-col border-2 px-6 py-5 items-center border-dashed border-gray-400 rounded-md']"
                        >
                        <svg class="w-12 h-12 text-gray-500 " 
                          aria-hidden="true" fill="none" 
                          stroke="currentColor" 
                          viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            troke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        </svg>
                        <p class="font-semibold text-gray-700">Drop files to upload</p>
                        <p class="text-xs mb-2 text-gray-700">or</p>
                        <label class=" bg-gray-200 px-2 h-7 inline-flex items-center rounded border border-gray-300 shadow-sm text-sm text-gray-700 focus-within:ring-2 focus-within:ring-ofser-2 focus-within:ring-indigo-500 ">
                          Select Files
                          <input ref="files" @input="onSelectedFiles" type="file" name="files" multiple class="sr-only">
                        </label>
                        <p class="text-xs text-gray-800 mt-4">Maximum upload file size: 512MB.</p>
                      </div>
                    </div>
                    <ul class="my-6 bg-gray-200 rounded divide-y divide-gray-300 shadow">
                      <li v-for="(item, index) in media" :key="index"
                          class="p-3 flex items-center space-x-1">
                        <div class="w-9 h-g bg-gray-300 flex-shrink-0">
                          <img :src="item.preview_url" class="h-full w-full rounded" :alt="item.file.name">
                        </div>

                        <div class="text-sm text-gray-800 flex-1">{{ item.file.name }}</div>

                        <div v-if="!item.uploaded && !item.error" class="w-40 bg-gray-500 rounded-full h-5 shadow-inner overflow-hidden relative flex items-center justify-center">
                          <div class="inline-block h-full bg-indigo-600 absolute top-0 left-0" :style="`width: ${item.progress}%`"></div>
                          <div class="relative z-10 text-xs font-semibold text-center text-white drop-shadow text-shadow">{{ item.progress }}%</div>
                        </div>

                        <div v-if="item.error" class="text-sm text-red-600">{{item.error }}</div>
                        <Link href="#" v-if="item.uploaded" class="text-sm text-indigo-600 underline">Edit</Link>
                      </li>
                    </ul>
                  </div>
 
                  <div class="mt-2 space-x-2">
                    <button
                      class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 focus:outline-none"
                      type="submit"
                    >Save card
                    </button>
                    <button
                      class="px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:text-black focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
                      type="button"
                      @click="closeModal()"
                    >Cancel
                    </button>
                  </div>
                </form>
                <div class="p-5 bg-gray-100 sm:w-48">
                  <h3 class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">
                    Actions
                  </h3>
                  <Link
                    :href="`/cards/${card?.id}`"
                    method="delete"
                    as="button"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Delete card</span>
                </Link>
                </div>
              </div>
             
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>