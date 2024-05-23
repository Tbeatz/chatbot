<script setup>
    import { ref, onMounted } from 'vue';

    const displayedText = ref('');
    const props = defineProps({
        trigger: {
            type: Boolean,
            default: true,
        },
        text: {
            type: String,
        },
        speed: {
            type: Number,
            default: 30
        }
    });

    function typeWriterEffect() {
        let index = 0;
        const interval = setInterval(() => {
            if (index < props.text.length) {
                displayedText.value += props.text.charAt(index);
                index++;
            } else {
                clearInterval(interval);
            }
        }, props.speed);
    };

        onMounted(() => {
            if (props.trigger) {
                typeWriterEffect();
            } else {
                displayedText.value = props.text;
            }
        });
</script>

<template>
    <p class="text-sm font-arial font-semibold">{{ displayedText }}</p>
</template>

