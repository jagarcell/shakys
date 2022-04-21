<template>
    <!-- THIS IS THE SECTION THAT IS USED TO ENTER DATA TO BE CREATED -->
    <!-- IT WILL BE SHOWN WHEN THE USER CLICKS ON THE ADD ICON -->
    <div>
        <!-- Add Icon and Search Bar -->
        <addicon :show="!addMode" v-on:add-icon-click="add" v-on:search-click="searchClick"></addicon>

        <!-- ADD SECTION -->
        <div v-show="addMode" class="add_section">
            <div class="pic_frame">
                <form id="product-image" action="/productimgupload" method="post" enctype="multipart/form-data" class="dropzone product_image box_shadow" :image="imgToUpload">
                    <input type="hidden" name="_token" :value="csrf">
                </form>
            </div>
            <div class="data_entry">
                <div class="add_form_frame w-form">
                    <form id="product-form" class="product_form add_frame">
                        <div class="product_add_frame">
                            <div class="add_frame_section">
                                <div class="field_wrap">
                                    <input v-model="code" type="text" class="code text_field box_shadow w-input" maxlength="50" placeholder="Product Code">
                                </div>
                                <div class="field_wrap">
                                    <input v-model="description" type="text" class="description text_field box_shadow w-input" maxlength="150" placeholder="Product Description" required="">
                                </div>
                                <div class="field_wrap">
                                    <input v-model="dToCount" type="number" class="days_to_count text_field box_shadow w-input" min="0" placeholder="Days To Count" required="">
                                </div>
                            </div>
                            <div class="add_frame_section">
                                <div class="field_wrap" style="display:flex;">
                                    <div class="measure_unit_input">
                                        <input v-model="defaultUnitDescription" type="text" class="measure_unit text_field box_shadow w-input" placeholder="Default Measure Unit" disabled>
                                    </div>
                                    <div class="default_measure_unit_button_frame">
                                        <input type="button" value="Units" class="edition_button accept_button box_shadow w-button default_measure_unit_button" v-on:click="measuresButtonClick">
                                    </div>
                                </div>
                                <div class="field_wrap">
                                    <select v-model="defaultSupplier" class="default_supplier text_field box_shadow w-input">
                                        <option value="-1" selected="" placeholder="Default Supplier (Optional)">Default Supplier (Optional)</option>
                                        <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{supplier.name}}</option>
                                    </select>
                                </div>
                                <div class="field_wrap">
                                    <select v-model="pType" class="plan_type_select text_field box_shadow w-input" title="TYPE 1 = PRODUCT MUST BE COUNTED TYPE 2 = PRODUCT WON'T BE COUNTED">
                                        <option value="-1" selected="" disabled>Select a plan type ...</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field_wrap" style="display:flex;">
                            <div class="add_buttons_frame">
                                <input type="button" value="Create" data-wait="Please wait..." class="edition_button accept_button  box_shadow w-button" v-on:click="create">
                                <input type="button" value="Discard" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" v-on:click="discard">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <unitsdialog 
            :show-dialog="unitsDialog"
            :product-description="description"
            :measure-units="measureUnits"
            :default-unit-id=-1
            v-on:discard="unitsDiscard"
            v-on:add-unit="addUnit"
            v-on:unit-added="unitAdded"
            v-on:accept="acceptUnitChanges"></unitsdialog>
    </div>
  
</template>

<script>
import addicon from '../addicon.vue'
import unitsdialog from '../measureunits/unitsdialog.vue'

const axios = require('axios')
var thisVue = null

export default {
    components: { addicon, unitsdialog },
    props:{
        productCode:{
            type : String,
            default : '',
        },
        defaultMeasureUnit : {
            type : String,
            default : '',
        },
        productDescription : {
            type : String,
            default : 'New Product',
        },
        defaultSupplierId : {
            type : Number,
            default : -1
        },
        daysToCount : {
            default : '',
        },
        planType : {
            type : Number,
            default : -1,
        },
        imageToUpload : {
            type : String,
            default : '',
        },
        addMode : {
            type : Boolean,
            default : false,
        }
    },
    data:function(){
        return {
            csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            unitsDialog:false,
            imgToUpload:this.imageToUpload,
            code:this.productCode,
            description:this.productDescription,
            dToCount:this.daysToCount,
            defaultUnitDescription:this.defaultMeasureUnit,
            defaultSupplier:this.defaultSupplierId,
            pType : this.planType,
            suppliers:[],
            measureUnits:[],
            defaultUnitId : -1,
        }
    },
    methods:{
        searchClick:function(searchText){
            console.log('searchClick = ' + searchText)
        },
        add:function(){
            axios.get('/searchbytext', {params : {
                    search_text:'',
                }
            }).then(response => {
                this.measureUnits = response.data.measureunits
                this.$emit('add-click')
            })
        },
        discard:function(){
            this.$emit('discarded')
            this.clearFields()
        },
        create:function(){
            var productForm = document.getElementById('product-form')
            if(productForm.checkValidity()){
                axios.post('/createproduct', {
                        _token : this.csrf,
                        internal_code : this.code,
                        internal_description : this.description,
                        days_to_count : this.dToCount,
                        default_measure_unit_id : this.defaultUnitId,
                        plan_type : this.pType,
                        default_supplier_id : this.defaultSupplier,
                        image_to_upload : this.imgToUpload,
                    }
                ).then(response => {
                    if(response.status == 200){
                        this.$emit('created', response.data)
                        if(!response.data.isError){
                            this.clearFields()
                        }
                    }
                })
            }
            else{
                productForm.reportValidity()
            }
        },
        clearFields:function(){
            this.imgToUpload = this.imageToUpload
            this.code = this.productCode
            this.description = this.productDescription
            this.dToCount = this.daysToCount
            this.defaultUnitDescription = this.defaultMeasureUnit
            this.defaultSupplier = this.defaultSupplierId
            this.pType = this.planType
        },
        measuresButtonClick:function(){
            this.unitsDialog = true
        },
        unitsDiscard:function(){
            this.unitsDialog = false
        },
        addUnit:function(){
            console.log('add unit')
        },
        unitAdded:function(newUnit){
            this.measureUnits.push(newUnit)
        },
        acceptUnitChanges:function(units, defaultUnitId){
            this.defaultUnitId = defaultUnitId
            this.measureUnits = units
            this.unitsDialog = false
            axios.get('/getmeasureunit', 
                {
                    params : {
                        id : defaultUnitId
                    }
                }).then(response => { 
                    if(response.status == 200){
                        if(response.data.status == 'ok'){
                            this.defaultUnitDescription = response.data.measureunit.unit_description
                        }
                    }
                }
            )
        },
    },
    created:function(){
        thisVue = this
    },
    mounted:function(){
        Dropzone.options.productImage =
        { 
            url: "/productimgupload", 
            dictDefaultMessage : 'Drop An Image Or Click To Search One',
            init : function dropzoneInit() {
                // body...
                this.on('addedfile', function (file) {
                    // body...
                    var filesAccepted = this.getAcceptedFiles()
                    if(filesAccepted.length > 0){
                        this.removeFile(filesAccepted[0])
                    }
                    file.previewElement.addEventListener("click", function() {
                        this.parentNode.click()
                    })                                            
                })
                this.on('success', function(file, data){
                    thisVue.imgToUpload = data.filename
                })
            },
        }

        axios.get('/getsuppliers').then(response => {
            if(response.status == 200){
                var data = response.data
                if(data.status == 'ok'){
                    this.suppliers = data.suppliers
                    return
                }
            }
        })
    },
}
</script>

<style scope>
    .product_image{
        padding: 10px;
    }

    .unit_link_dialog{
        position: fixed;  
        top: 0;
        height: 85%;
        left: 0;
        width: 50%;
        background-color: dimgrey;
        border-radius: 5px;
        overflow: hidden;
        text-align: end;
        z-index: 12;
    }

    .measure_unit{
        width: 100%;
    }

@media screen and (max-width: 991px) {

    .measure_unit{
        width: 95%;
    }

}

@media screen and (max-width: 767px) {

    .measure_unit{
        width: 90%;
    }

}

@media screen and (max-width: 499px) {

    .measure_unit{
        width: 80%;
    }

}

</style>