<template>

    <!-- UNITS DIALOG -->
    <div v-show="this.showDialog">
        <div class="unit_link_dialog shadowRight">
            <div class="dialog_close_bar">
                <div class="units_close_bar_text">Select units for</div>
                <a class="unit_link_dialog_close_icon" v-on:click="discardUnitChanges">X</a>
            </div>
            <div class="unit_link_dialog_body">
                <div class="unit_link_product_description_frame">
                    <input :value="productDescription" class="unit_link_product_description" disabled>
                </div>
                <div class="unit_link_checkboxes">
                    <div class="unit_link_name_frame">
                        <label for="unit_0" class="unit_link_checkbox_label">
                        <input v-show="add" v-model="newUnit" class="new_unit_input" placeholder="Enter a unit"></label>
                        <div class="unit_add_icon">
                            <a v-show="!add" class="unit_add_link" v-on:click="addUnitClick">Add</a>
                            <a v-show="add" class="unit_create_link" v-on:click="createUnitClick">+</a>
                        </div>
                    </div>
                    <div class="unit_links">
                        <unitlink 
                            v-for="(measureUnit, index) in measureUnits" 
                            :key="measureUnit.id"
                            :measure-unit="measureUnit"
                            :index="index"
                            v-on:unit-check-change="unitCheckChange"></unitlink>
                    </div>
                </div>
                <div class="default_measure_select_frame">
                    <div class="default_unit_label">Default unit</div>
                    <select class="default_measure_select" v-model="defUnitId">
                        <option value="-1" selected disabled>Select a default unit</option>
                        <option 
                            v-for="checkedMeasureUnit in checkedMeasureUnits"
                            :value="checkedMeasureUnit.id"
                            :key="checkedMeasureUnit.id">{{checkedMeasureUnit.unit_description}}</option>
                    </select>
                    <input type="text" class="selected_index" hidden>
                </div>
                <div class="unit_link_accept_button">
                    <input type="button" value="Accept" class="accept_button box_shadow w-button" v-on:click="acceptUnitChanges">
                </div>
            </div>    
        </div>
    </div>
</template>

<script>
const axios = require('axios')
import  unitlink  from "./unitlink.vue"

export default {
    components : { unitlink },
    props:{
        showDialog:{
            type:Boolean,
            default:false,
        },
        productDescription:{
            type:String,
            default:'New Product',
        },
        measureUnits:{
            type:Array,
            default:function(){
                return []
            },
        },
        defaultUnitId:{
            type:Number,
            default:-1,
        }
    },
    methods:{
        discardUnitChanges:function(){
            this.$emit('discard')
        },
        addUnitClick:function(){
            this.add = true
            this.$emit('add-unit')
        },
        acceptUnitChanges:function(){ 
            this.$emit('accept', this.units, this.defUnitId)
        },
        createUnitClick:function(){
            this.add = false
            axios.post('/createmeasureunit',
                {
                    params : {
                        unit_description : this.newUnit
                    }
                }
            ).then(
                response => {
                    if(response.status == 200){
                        if(response.data.status == 'ok'){
                            this.$emit('unit-added', response.data.measureunit)
                            this.newUnit = ''
                        }
                    }
                }
            )
        },
        unitCheckChange:function(checked, index){
            this.units[index].checked = checked
            if(checked){
                this.checkedMeasureUnits.push(this.units[index])
            }
            else{
                this.checkedMeasureUnits.splice(this.checkedMeasureUnits.indexOf(this.units[index]), 1)
            }
        },
    },
    data:function(){
        return {
            add : false,
            newUnit : '',
            defUnitId : this.defaultUnitId,
            units : [],
            checkedMeasureUnits : []
        }
    },
    updated: function(){
        this.units = this.measureUnits
    }
}
</script>

<style scope>
    [type='text'], textarea, select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #fff;
        border-color: #6b7280;
        border-width: 1px;
        border-radius: 0px;
        padding-top: 0.5rem;
        padding-right: 0.75rem;
        padding-bottom: 0.5rem;
        padding-left: 0.75rem;
        font-size: 1rem;
        line-height: 1.5rem;
        border-radius: 5px;
    }
</style>