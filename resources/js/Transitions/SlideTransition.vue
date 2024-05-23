<template>
    <Transition name="drawer">
      <slot />
    </Transition>
</template>

<style>
.drawer-enter-active,
.drawer-leave-active {
    transition: transform 0.2s ease-out, opacity 0.2s ease-out;
}

.drawer-enter-from,
.drawer-leave-to {
    transform: translateX(-100%);
    opacity: 100;
}
</style>

