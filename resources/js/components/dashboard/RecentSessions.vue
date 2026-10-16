<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import type { DashboardRecentSession } from '@/services';
import { BookOpen } from 'lucide-vue-next';

defineProps<{
    sessions: DashboardRecentSession[];
    loading: boolean;
}>();

const titleOf = (session: DashboardRecentSession): string => session.name || session.skill;

const metaOf = (session: DashboardRecentSession): string => [session.skill, session.date].filter(Boolean).join(' · ');
</script>

<template>
    <Card>
        <CardContent class="space-y-4 p-4 md:p-6">
            <HeadingSmall title="Recent Sessions" description="Your latest learning sessions." />
            <template v-if="loading">
                <div v-for="n in 5" :key="n" class="flex items-center gap-3">
                    <Skeleton class="size-8 shrink-0 rounded-full" />
                    <div class="flex-1 space-y-1.5">
                        <Skeleton class="h-4 w-40" />
                        <Skeleton class="h-3 w-28" />
                    </div>
                    <Skeleton class="h-4 w-14" />
                </div>
            </template>
            <p v-else-if="sessions.length === 0" class="text-sm text-muted-foreground">No recent sessions yet.</p>
            <ul v-else class="divide-y divide-border">
                <li v-for="session in sessions" :key="session.id" class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-muted" aria-hidden="true">
                        <BookOpen class="size-4 text-muted-foreground" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-medium">{{ titleOf(session) }}</span>
                        <span class="block truncate text-sm text-muted-foreground">{{ metaOf(session) }}</span>
                    </span>
                    <span class="shrink-0 text-sm text-muted-foreground">{{ session.duration_formatted }}</span>
                </li>
            </ul>
        </CardContent>
    </Card>
</template>
