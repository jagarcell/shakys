<template>
    <div class="product_edition">
        <div class="product_section_wrap">
            <div class="section">
                <div class="pic_frame">
                    <img :src="imagePath" loading="lazy" sizes="(max-width: 479px) 80vw, 256px" :srcset="imagePath" 500W alt="" class="picture box_shadow">
                </div>
                <div class="data_entry">
                    <div class="data_edit">
                        <div class="field_wrap">
                            <div class="field_label">Product Code</div>
                            <div class="code data_field box_shadow">{{internalCode}}</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Description</div>
                            <div class="description data_field box_shadow">{{internalDescription}}</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Days To Count</div>
                            <div class="days_to_count data_field box_shadow">{{daysToCount}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section_right">
                <div class="data_entry">
                    <div class="data_edit">
                        <div class="field_wrap">
                            <div class="field_label">Default Measure Unit</div>
                            <div class="measure_unit data_field box_shadow">{{defaultMeasureUnit}}</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Default Supplier</div>
                            <div class="default_supplier data_field box_shadow">{{defaultSupplierName}}</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Planification Type</div>
                            <div class="default_supplier data_field box_shadow">{{planType}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section_buttons">
                <div class="data_entry_buttons">
                    <div class="bottom_button">
                        <input type="button" class="edit_button box_shadow w-button" value="Edit" v-on:click="editClick">
                    </div>
                    <div class="data_entry_buttons_separator"></div>
                    <div class="bottom_button">
                        <input type="button" class="supplier_product_button box_shadow w-button" value="Suppliers" v-on:click="suppliersClick">
                    </div>
                    <div class="data_entry_buttons_separator"></div>
                    <div class="bottom_button">
                        <input type="button" class="delete_button box_shadow w-button" value="Delete" v-on:click="deleteClick">
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</template>

<script>
export default {
    props:{
        imagePath : {
            type:String,
            default:'',
        },
        internalCode : {
            type : String,
            default: '',
        },
        internalDescription : {
            type : String,
            default : '',
        },
        daysToCount : {
            type : Number,
            default : 0,
        },
        defaultMeasureUnit : {
            type : String,
            default : '',
        },
        defaultSupplierName : {
            type : String,
            default : '',
        },
        planType : {
            type : Number,
            default : 0,
        },
    },
    data:function(){
        return {
            
        }
    }
}
</script>
 
<style>

</style>