<template>
    <div id="page-top">
        <div id="wrapper">
            <TheSidebar/>

            <div
                id="content-wrapper"
                class="d-flex flex-column"
            >
                <div id="content">
                    <TheNavbar/>
                    <router-view/>
                </div>
                <TheFooter/>
            </div>
        </div>

        <a
            href="#page-top"
            class="scroll-to-top rounded"
        >
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
</template>

<script>
import TheSidebar from './TheSidebar.vue';
import TheNavbar from './TheNavbar.vue';
import TheFooter from './TheFooter.vue';

export default {
    name: 'DefaultLayout',

    components: {
        TheSidebar,
        TheNavbar,
        TheFooter,
    },

    beforeMount () {
        document.body.className = '';
    },

    mounted () {
        this.$nextTick(() => {
            this.loadScript('/nca/theme/js/sb-admin-2.min.js');
        });
    }
};
</script>
