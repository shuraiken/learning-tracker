<script setup lang="ts">
import { computed, reactive } from 'vue';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { GraduationCap, Plus } from 'lucide-vue-next';

const props = defineProps<{
    modelValue?: number;
    items: Array<[object]>;
}>();
const emits = defineEmits<{
    'click:mastery': [id: number];
    'create:mastery': [name: string];
}>();

const state = reactive({
    form: {
        name: '',
    },
});

const filteredItems = computed(() => {
    if (!state.form.name) return props.items;

    return props.items.filter((item) => {
        return item.name.toLowerCase().includes(state.form.name.toLowerCase());
    });
});

const onClickMastery = (id: number) => {
    emits('click:mastery', id);
};
const onCreateMastery = (name: string) => {
    emits('create:mastery', name);
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger class="size-8 text-neutral-500">
            <GraduationCap />
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-56 space-y-1">
            <form @submit.prevent="onCreateMastery(state.form.name)" class="space-y-1">
                <Input v-model="state.form.name" placeholder="Search or create new" required />
                <Button v-if="filteredItems.length === 0" class="w-full">
                    Add <span class="truncate font-bold">{{ state.form.name }}</span>
                    <Plus class="ml-1 h-4 w-4" />
                </Button>
            </form>
            <DropdownMenuItem @click="onClickMastery(item.id)" v-for="item in filteredItems" :key="item.id">
                {{ item.name }}
            </DropdownMenuItem>
            <!-- <div v-if="filteredItems.length === 0" class="h-20 flex-center">
                <p class="text-sm">No results</p>
            </div> -->
        </DropdownMenuContent>
    </DropdownMenu>
</template>
