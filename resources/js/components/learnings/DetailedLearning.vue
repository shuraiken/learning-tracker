<script setup lang="ts">
import axios from 'axios';
import { useDebounceFn } from '@vueuse/core';
import { reactive, watch, watchEffect } from 'vue';
import { Trash2, Ellipsis,  } from 'lucide-vue-next'

import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogClose,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import PlayButton from '@/components/PlayButton.vue'

const props = defineProps<{
    item: number;
}>();
const emits = defineEmits<{
    (e: 'delete:learning'): void
    (e: 'update:learning'): void
}>();

const state = reactive({
    isConfirmDeleteOpen: false,
    learning: {
        name: '',
    },
});

const debounce = (callback: () => void, delay: number) => {
    useDebounceFn(callback, delay);
}

const fetchLearning = async () => {
    try {
        const res = await axios.get(`/server/learnings/${props.item}`);

        state.learning = res.data;
    } catch (e) {
        console.error(e);
    }
};

const updateLearning = async () => {
    try {
        await axios.put(`/server/learnings/${props.item}`, state.learning);
        emits('update:learning');
    } catch (e) {
        console.error(e);
    }
}

const debounceUpdateLearning = useDebounceFn(updateLearning, 1000);

const deleteLearning = async () => {
    try {
        const res = await axios.delete(`/server/learnings/${props.item}`);
        state.isConfirmDeleteOpen = false;
        emits('delete:learning');
    } catch (e) {
        console.error(e);
    }
}

watchEffect(async () => {
    if (props.item) {
        await fetchLearning();
    }
});
</script>

<template>
    <div class="flex-1">
        <form class="relative w-full h-full space-y-3">
            <div class="text-xl font-bold">
                <input v-model="state.learning.name" @input="
                debounceUpdateLearning" type="text" class="w-full focus:outline-none" />
            </div>
            <hr class="bg-sidebar-border"></hr>
            <div class="grid grid-cols-2">
                <div></div>
            </div>

            <PlayButton />

            <DropdownMenu class="relative">
                <DropdownMenuTrigger class="absolute right-0 size-8 text-neutral-500 bottom-2">
                    <Button variant="ghost" size="icon"><Ellipsis class="w-6" /></Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="absolute w-56 -right-2 min-h-12 -top-22">
                    <Dialog v-model:open="state.isConfirmDeleteOpen">
                        <DialogTrigger class="w-full">
                            <Button variant="ghost" class="flex items-center justify-start w-full text-red-500 size-full">
                                <Trash2 class="w-5"/>
                            <span class="text-sm">Delete</span>
                        </Button>
                        </DialogTrigger>
                        <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Delete Learning</DialogTitle>
                            <DialogDescription>
                            Are you sure you want to delete this record?
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <DialogClose>
                                <Button type="button" variant="outline">
                                    Close
                                </Button>
                            </DialogClose>
                            <Button variant="destructive" @click="deleteLearning">Delete</Button>
                        </DialogFooter>
                        </DialogContent>
                    </Dialog>
                        <!-- <DropdownMenuItem> -->
                    <!-- </DropdownMenuItem> -->
                </DropdownMenuContent>
            </DropdownMenu>
        </form>
    </div>
</template>
