<template>
    <!-- THIS IS THE SECTION THAT HOLDS THE HTML FOR THE ADDING ICON AND THE ADDING ACTION FORM -->
    <div v-if="this.show" class="search_frame">
        <input v-model="searchText" type="text" class="search_bar" placeholder="Search text or Enter for all" v-on:keyup.enter="search" autofocus>
        <div class="search_product_icon" v-on:click="search">
            <a><img src="/images/MagnifierBk.png"></a>
        </div>
        <div class="add_icon" v-on:click="$emit('add-icon-click')">
            <a>+</a>
        </div>
    </div>
</template>

<script>
export default {
    props:['show'],
    data:function(){
        return {searchText:''}
    },
    methods:{
        search:function(){
            this.$emit('search-click', this.searchText)
            this.searchText = ''
        },
    }
}
</script>

<style scoped>
    .search_frame{
        position: relative;
        width: 100%;
        height: 80px;
    }

    .search_bar{
        position: absolute;
        left: 20%;
        width: 60%;
        height: 40px;
        top: 20px;
    }

    .search_product_icon{
        position: absolute;
        right: 22%;
        top: 30px;
    }

    .add_icon{
        position: absolute;
        top: 20px;
        height: 40px;
        right: 10%;
        line-height: 0.3em;
    }
</style>