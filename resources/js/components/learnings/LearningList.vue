<script setup lang="ts">
import { reactive } from 'vue';

const props = defineProps<{
    items: Array<object>;
    selectedItem: number;
}>();
const emits = defineEmits<{
    'click:learning': [id: number];
}>();

const state = reactive({
    filters: {
        search: '',
        limit: 0,
        sort: {
            column: 'created_at',
            direction: 'desc',
        },
    },
});

const onClickLearning = (id: number) => {
    emits('click:learning', id);
};
</script>

<template>
    <div class="w-full divide-y divide-sidebar-border">
        <div
            @click="onClickLearning(learning.id)"
            v-for="learning in items"
            :key="learning.id"
            class="flex items-center justify-between rounded-lg px-2 py-1 hover:bg-neutral-900"
            :class="{ 'bg-neutral-800 hover:bg-neutral-800': learning.id === selectedItem }"
        >
            <div class="flex items-center gap-2 px-2 py-1">
                <div class="flex aspect-square items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"></div>
                <span class="text-sm font-semibold">{{ learning.name }}</span>
            </div>
        </div>
    </div>
</template>
