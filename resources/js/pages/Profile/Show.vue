<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';
import PaginationLinks from '../../components/shared/PaginationLinks.vue';
import TweetCard from '../../components/tweets/TweetCard.vue';
import FollowButton from '../../components/users/FollowButton.vue';
import users from '../../routes/users/index';

defineProps<{
    profileUser: any;
    tweets: any;
    followers: any[];
    following: any[];
}>();
</script>

<template>
    <AppLayout :title="profileUser.name">
        <div class="mx-auto max-w-5xl space-y-6 px-4 py-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex gap-4">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-lg font-bold text-slate-700"
                        >
                            {{ profileUser.initials }}
                        </div>

                        <div>
                            <h1 class="text-xl font-bold text-slate-900">{{ profileUser.name }}</h1>
                            <p class="text-sm text-slate-500">@{{ profileUser.username }}</p>
                            <p v-if="profileUser.bio" class="mt-3 text-sm text-slate-700">
                                {{ profileUser.bio }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-4 text-sm text-slate-600">
                                <span>
                                    <strong class="text-slate-900">{{ profileUser.followers_count }}</strong>
                                    followers
                                </span>
                                <span>
                                    <strong class="text-slate-900">{{ profileUser.following_count }}</strong>
                                    following
                                </span>
                            </div>
                        </div>
                    </div>

                    <FollowButton
                        v-if="!profileUser.is_self"
                        :username="profileUser.username"
                        :is-following="profileUser.is_following"
                    />
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-4">
                    <TweetCard v-for="tweet in tweets.data" :key="tweet.id" :tweet="tweet" />

                    <PaginationLinks :links="tweets.links" />
                </div>

                <aside class="space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-sm font-semibold text-slate-900">Followers</h2>
                        <ul class="mt-3 space-y-3">
                            <li v-for="user in followers" :key="`follower-${user.id}`">
                                <Link
                                    :href="users.show(user.username).url"
                                    class="text-sm text-slate-700 hover:underline"
                                >
                                    {{ user.name }}
                                    <span class="text-slate-500">@{{ user.username }}</span>
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-sm font-semibold text-slate-900">Following</h2>
                        <ul class="mt-3 space-y-3">
                            <li v-for="user in following" :key="`following-${user.id}`">
                                <Link
                                    :href="users.show(user.username).url"
                                    class="text-sm text-slate-700 hover:underline"
                                >
                                    {{ user.name }}
                                    <span class="text-slate-500">@{{ user.username }}</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </aside>
            </section>
        </div>
    </AppLayout>
</template>