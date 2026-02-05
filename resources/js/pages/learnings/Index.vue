<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import axios from 'axios';
import { onMounted, reactive } from 'vue';

import DetailedLearning from '@/components/learnings/DetailedLearning.vue';
import LearningList from '@/components/learnings/LearningList.vue';
import MasteryDropdown from '@/components/learnings/MasteryDropdown.vue';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
`import { Badge } from '@/components/ui/badge';`;

const state = reactive({
    form: {
        name: '',
        masteryId: '',
        groupId: '',
    },
    masteryForm: {
        name: '',
    },
    learningFilter: {
        search: '',
        limit: 0,
        column: 'created_at',
        direction: 'desc',
    },
    masteryFilter: {
        search: '',
        limit: 0,
        column: 'created_at',
        direction: 'desc',
    },
    learnings: [],
    masteries: [],
    selectedMastery: '',
    selectedLearning: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Learnings',
        href: '/learnings',
    },
];

const onClickMastery = (id: number) => {
    const mastery = state.masteries.find((m) => m.id === id);
    state.form.masteryId = mastery.id;
    state.selectedMastery = mastery.name;
};

const onRemoveMastery = () => {
    state.form.masteryId = '';
    state.selectedMastery = '';
};

const onDeleteLearning = async () => {
    state.selectedLearning = '';
    await fetchLearnings();
};

const fetchLearnings = async () => {
    try {
        const res = await axios.get('/server/learnings', {
            params: state.learningFilter,
        });

        state.learnings = res.data.data;
    } catch (e) {
        console.error(e);
    }
};

const fetchMasteries = async () => {
    try {
        const res = await axios.get('/server/masteries', {
            params: state.masteryFilter,
        });

        state.masteries = res.data.data;
    } catch (e) {
        console.error(e);
    }
};

const addLearning = async () => {
    try {
        await axios.post('/server/learnings', state.form);

        state.form = {};
        state.selectedMastery = '';
        await fetchLearnings();
    } catch (e) {
        console.error(e);
    } finally {
        state.form.name = '';
    }
};

const addMastery = async (name: string) => {
    try {
        await axios.post('/server/masteries', { name: name });
        await fetchMasteries();
    } catch (e) {
        console.error(e);
    } finally {
        state.masteryForm.name = '';
    }
};

onMounted(async () => {
    await fetchLearnings();
    await fetchMasteries();
});
</script>

<template>
    <Head title="Learnings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative h-full grow">
                <div class="flex h-full gap-4">
                    <div class="w-[60%] space-y-4">
                        <form @submit.prevent="addLearning" class="rounded-lg bg-neutral-800 px-2.5 py-1 text-center font-semibold">
                            <div class="flex w-full gap-2 px-1 py-0.5">
                                <input
                                    v-model="state.form.name"
                                    type="text"
                                    class="flex-1 focus:outline-none"
                                    placeholder="Got something new to do?"
                                    required
                                />
                                <MasteryDropdown :items="state.masteries" @create:mastery="addMastery" @click:mastery="onClickMastery" />
                                <button title="Add" class="size-8cursor-pointer flex items-center justify-center text-neutral-500">
                                    <Plus />
                                </button>
                            </div>

                            <div class="flex items-center">
                                <Badge @click="onRemoveMastery" v-if="state.selectedMastery" class="group cursor-pointer">
                                    {{ state.selectedMastery }}
                                    <span class="ml-1 hidden cursor-pointer group-hover:inline-flex"> &times; </span>
                                </Badge>
                            </div>
                        </form>
                        <LearningList
                            :items="state.learnings"
                            :selected-item="state.selectedLearning"
                            @click:learning="state.selectedLearning = $event"
                        />
                    </div>
                    <div class="border-r border-r-sidebar-border"></div>
                    <DetailedLearning
                        v-if="state.selectedLearning"
                        :item="state.selectedLearning"
                        @delete:learning="onDeleteLearning"
                        @update:learning="fetchLearnings"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
