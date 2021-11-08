<template>
    <div>
        <div v-if="authuser !== null && authuser.user_type == 'admin'">
            <applogo :appname="this.appname"></applogo>
            <appmenu></appmenu>
            <pagetitle :pagetitle="this.pagetitle"></pagetitle>
        </div>
        <loginstatus :user="authuser"></loginstatus>
    </div>
</template>

<script>
    const axios = require('axios')

    import applogo from './applogo.vue'
    import appmenu from './appmenu.vue'
    import pagetitle from './pagetitle.vue'
    import loginstatus from './loginstatus.vue'

    export default {
        props:['appname', 'pagetitle'],
        components:{
            applogo, appmenu, pagetitle, loginstatus},
        created:function(){
            axios.get('/authuser').then(response => {
                this.authuser = response.data.user
                this.authuser.name = this.authuser.name.length > 0 ? this.authuser.name : this.authuser.username
            })
        },
        data:function(){
            return {
                authuser: null,
            }
        },
    }

</script>

<style>

</style>