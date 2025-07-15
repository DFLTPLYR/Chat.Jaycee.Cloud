<script setup lang="ts">
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { WebsocketUsers } from '@/types/dashboard';
import { useEchoPresence } from '@laravel/echo-vue';
import axios from 'axios';
import { Send } from 'lucide-vue-next';
import { onMounted } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { ref } from 'vue';
import { watch } from 'vue';
import InputError from '@/components/InputError.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const channel = useEchoPresence('OnlineUsers');
const OnlineUsers = ref<WebsocketUsers[]>([]);
const message = ref('');
const messageError = ref('');
const isTyping = ref(false)
let typingTimeout: ReturnType<typeof setTimeout>

function sendMessage() {
    const text = message.value.trim()

    if (!text) {
        messageError.value = 'Please type something before sending.'
        setTimeout(() => {
            messageError.value = ''
        }, 1000)
        return
    }

    message.value = ''
    axios
        .post(route('send-message'), { message: text })
        .then(res => {
            console.log(res.data)
        })
        .catch(err => {
            const errs = err.response?.data?.errors
            messageError.value = errs?.message?.[0]
                ?? err.response?.data?.message
                ?? 'An unexpected error occurred.'
            setTimeout(() => {
                messageError.value = ''
            }, 1000)
            return
        })
        .finally(() => {
            sendTypingStatus(false)
        })
}

function testApi() {
    axios.post('test')
}

function sendTypingStatus(state: boolean) {
    channel.channel().whisper('IsTyping', {
        id: page.props.auth.user.id,
        state,
    })
};

watch(message, (val) => {
    if (val !== '' && !isTyping.value) {
        isTyping.value = true
        sendTypingStatus(true)
    }

    if (typingTimeout) clearTimeout(typingTimeout)
    typingTimeout = setTimeout(() => {
        if (isTyping.value) {
            isTyping.value = false
            sendTypingStatus(false)
        }
    }, 2000)
});

onMounted(() => {
    channel
        .channel()
        .here((users: WebsocketUsers[]) => {
            OnlineUsers.value = users;
        })
        .joining((user: WebsocketUsers) => {
            if (!OnlineUsers.value.find(u => u.id === user.id)) {
                OnlineUsers.value.push(user);
            }
        })
        .leaving((user: WebsocketUsers) => {
            OnlineUsers.value = OnlineUsers.value.filter(u => u.id !== user.id);
        })
        .listenForWhisper("IsTyping", (user: WebsocketUsers) => console.log(user))
        .listen('GlobalMessage', e => console.log('msg:', e.message));
});

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col justify-between gap-4 overflow-x-auto rounded-xl p-4 select-none">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative flex aspect-video flex-col items-center justify-center overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <h1>Current Users</h1>
                    <p class="sm:text-6xl">{{ OnlineUsers.length }}</p>
                </div>
                <div
                    class="relative flex aspect-video flex-col items-center justify-center overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <h1>Current Users</h1>
                    <p class="sm:text-6xl">{{ OnlineUsers.length }}</p>
                </div>
                <div
                    class="relative flex aspect-video flex-col items-center justify-center overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <h1>Created By</h1>
                    <div class="flex flex-row items-center justify-center p-1">
                        <p class="sm:text-md lg:text-4xl">
                            DFLTPLYR
                        </p>
                        <div class="size-10 md:size-20">
                            <svg viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill="#9141ac"
                                        d="M19.906 17.847c0.429 0 0.79 0.21 1.102 0.636 0.31 0.422 0.468 0.944 0.468 1.56 0 0.619-0.156 1.141-0.468 1.563s-0.678 0.634-1.102 0.634c-0.451 0-0.839-0.21-1.151-0.634-0.307-0.422-0.465-0.944-0.465-1.563s0.153-1.139 0.465-1.56c0.312-0.427 0.702-0.636 1.151-0.636zM25.425 12.132c1.202 1.303 1.809 2.884 1.809 4.738 0 1.203-0.142 2.286-0.415 3.249-0.278 0.958-0.629 1.743-1.048 2.343-0.427 0.605-0.943 1.136-1.565 1.585-0.622 0.461-1.195 0.79-1.712 1.002s-1.112 0.376-1.785 0.49c-0.665 0.117-1.168 0.18-1.517 0.198-0.336 0.015-0.702 0.022-1.097 0.022-0.088 0-0.385 0.010-0.879 0.024-0.482 0.020-0.896 0.029-1.218 0.029s-0.736-0.010-1.218-0.029c-0.49-0.015-0.79-0.024-0.879-0.024-0.395 0-0.764-0.005-1.098-0.022-0.35-0.017-0.852-0.080-1.514-0.198-0.676-0.112-1.268-0.273-1.787-0.49-0.517-0.21-1.089-0.541-1.708-1.002-0.624-0.454-1.141-0.983-1.568-1.585-0.419-0.6-0.772-1.385-1.048-2.343-0.272-0.963-0.414-2.046-0.414-3.249 0-1.854 0.605-3.435 1.81-4.738-0.133-0.065-0.14-0.714-0.021-1.952 0.107-1.239 0.37-2.38 0.797-3.421 1.503 0.16 3.352 1.008 5.567 2.539 0.748-0.195 1.772-0.295 3.078-0.295 1.37 0 2.394 0.1 3.079 0.295 1.009-0.681 1.975-1.239 2.896-1.663 0.936-0.419 1.609-0.667 2.033-0.731l0.634-0.145c0.429 1.041 0.692 2.185 0.8 3.421 0.124 1.237 0.117 1.887-0.015 1.952zM16.052 24.683c2.703 0 4.741-0.324 6.125-0.973 1.38-0.651 2.082-1.99 2.082-4.008 0-1.17-0.441-2.15-1.322-2.932-0.454-0.424-0.985-0.681-1.595-0.781-0.595-0.098-1.514-0.098-2.755 0-1.236 0.1-2.082 0.145-2.537 0.145-0.619 0-1.291-0.033-2.125-0.098-0.834-0.062-1.487-0.102-1.954-0.122-0.478-0.015-0.986 0.045-1.538 0.172-0.557 0.133-1.008 0.357-1.373 0.681-0.84 0.75-1.266 1.725-1.266 2.932 0 2.019 0.684 3.357 2.050 4.006 1.365 0.653 3.397 0.975 6.101 0.975zM12.143 17.847c0.424 0 0.789 0.21 1.098 0.636 0.31 0.422 0.467 0.944 0.467 1.56 0 0.619-0.155 1.141-0.467 1.563-0.309 0.422-0.677 0.634-1.098 0.634-0.455 0-0.841-0.21-1.153-0.634-0.309-0.422-0.467-0.944-0.467-1.563s0.155-1.139 0.467-1.56c0.312-0.427 0.699-0.636 1.153-0.636z">
                                    </path>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex h-[calc(90vh-22rem)] flex-col border border-sidebar-border/70 rounded-xl p-2 dark:border-sidebar-border">
                <PlaceholderPattern />

                <!-- Messages Section -->
                <section class="flex-1 overflow-y-auto space-y-2">
                    <h1 v-for="(e, index) in Array(100)" :key="index">test {{ index }}</h1>
                </section>

                <!-- Input Section -->
                <div class="mt-2 flex items-center gap-2 rounded-2xl bg-blue-300/5 p-2 transition duration-300">
                    <Input :placeholder="messageError ? messageError : 'Send Message here'" class="flex-1"
                        v-model="message" :class="{ 'ring-1 ring-red-500': messageError }" @keydown.enter="testApi" />
                    <button class="rounded-2xl bg-white/5 p-2 transition-all duration-300 hover:bg-white/50"
                        @click="sendMessage">
                        <Send />
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
