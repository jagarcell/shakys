<template>
    <div class="unit_link_checkbox_frame">
        <label class="unit_link_checkbox_label">
            <input type="checkbox" v-model="checked" class="unit_link_checkbox" v-on:change="unitCheckChange">
            {{measureUnit.unit_description}}
        </label>
    </div>                    
</template>

<script>
export default {
    props:{
        measureUnit:{
            type:Object,
        },
        index:{
            type:Number,
        }
    },
    methods:{
        unitCheckChange:function(){
            this.$emit('unit-check-change', this.checked, this.index)
        },
    },
    data:function(){
        return {
            checked : false,
        }
    }
}
</script>

<style scope>

</style>