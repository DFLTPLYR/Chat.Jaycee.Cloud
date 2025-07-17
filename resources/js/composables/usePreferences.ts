import { TextSize } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const page = usePage();
const currentTextSize = ref<TextSize>();

export function useUserPreference() {
    onMounted(() => {
        currentTextSize.value = page.props.auth.user.preference.text_size;
    });

    function updateUserPreference(value: TextSize) {
        router.post(
            route('update.preference.text-size'),
            { text_size: value },
            {
                onError: (e) => console.log(e),
            },
        );
    }

    return {
        currentTextSize,
        updateUserPreference,
    };
}
