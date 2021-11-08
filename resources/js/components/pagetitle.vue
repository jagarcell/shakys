<template>
    <div class="page_title_frame hide-this">
        <div class="page_title text_shadow box_shadow">{{pagetitle}}</div>
    </div>
</template>

<script>
    const default_layout = "default";

    export default {
        props:['pagetitle'],
    }
</script>

<style scoped>
    .page_title_frame {
        display: -ms-flexbox;
        display: flex;
        height: 15vh;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        border-bottom: 1px solid #000;
    }
</style>