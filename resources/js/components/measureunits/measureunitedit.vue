<template>
    <div class="section_wrap measure_unit_section data_entry">
        <!-- TO SHOW MEASURE UNIT INFO -->
        <div v-if="!edition" class="unit_data_edit">
            <div class="field_wrap">
                <div class="field_label">Unit Description</div>
                <div class="description data_field info_field box_shadow">{{description}}</div>
            </div>
            <div class="measure_units_entry_buttons">
                <div class="bottom_button">
                    <input type="button" class="edit_button box_shadow w-button" value="Edit" v-on:click="editButtonClick">
                </div>
                <div class="bottom_button">
                    <input type="button" class="delete_button box_shadow w-button" value="Delete" v-on:click="deleteButtonClick">
                </div>
            </div>
        </div>
        <!-- TO EDIT MEASURE UNIT INFO -->
        <div v-if="edition" class="unit_data_edit">
            <form class="product_form add_frame">
                <div class="field_wrap">
                    <div class="field_label">Unit Description</div>
                    <input v-model="description" type="text" class="data_field box_shadow unit_description_input" maxlength="50" name="Code" placeholder="Unit Description" required="">
                </div>
                <div class="measure_units_entry_buttons">
                  <div class="bottom_button">
                    <input type="button" value="Accept" data-wait="Please wait..." class="accept_button box_shadow w-button" v-on:click="acceptEdit">
                  </div>
                  <div class="bottom_button">
                      <input type="button" value="Discard" data-wait="Please wait..." class="discard_button box_shadow w-button" v-on:click="discardEdit">
                  </div>
                </div>
            </form>
        </div>
        <!-- THIS IS THE HTML FOR THE ACCEPT/CANCEL LINK REMOVAL -->
        <!-- THIS WILL BE USED FROM JS TO CREATE DIALOGS DYNAMICALLY -->
        <div v-if="showInUse" class="unit_link_frame">
          <div class="accept_cancel_unit_link_dialog shadowRight">
              <div class="accept_cancel_dialog_close_bar">
                  <div class="accept_cancel_units_close_bar_text">This unit is linked to one or more products</div>
                  <a class="accept_cancel_unit_link_dialog_close_icon" v-on:click="closeInUse">X</a>
              </div>
              <div class="accept_cancel_unit_link_dialog_body">
                  <div class="unit_link_product_description_frame">
                      <input v-model="this.description" class="unit_link_product_description" disabled>
                  </div>
                  <div>If you remove this unit all the links to the products will be removed</div>
                  <div class="remove_anyway">
                    <a v-on:click="removeAnyway">Remove Anyway</a>
                  </div>
              </div>    
          </div>
        </div>

    </div>
    
</template>

<script>
const axios = require('axios')
export default {
    props:{
      measureunit:{
        type:Object,
        default:function(){
          return {}
        },
        verifyBeforeDelete:{
          type:Boolean
        },
      },
      index:{
        type:Number,
        default:0
      },
    },
    data:function(){
      return {edition:false, description:this.measureunit.unit_description, showInUse:false}
    },
    methods:{
        editButtonClick:function(){
          this.edition = true
        },
        deleteButtonClick:function(){
          axios.post('/removemeasureunit',
            {
              params :
                {
                  id:this.measureunit.id, verbose:true
                }
            }
          ).then(response => {
            if(response.data.status == 'inuse'){
              this.showInUse = true
            }
            else{
              response.data.index = response.data.isError ? -1 : this.index
              this.$emit('deleted', response.data)
            }
          })
        },
        removeAnyway:function(){
          axios.post('/removemeasureunit',
            {
              paramas : {
                id:this.measureunit.id
              }
            }
          ).then(response => {
              this.showInUse = false
              response.data.index = response.data.isError ? -1 : this.index
              this.$emit('deleted', response.data)
          })
        },
        closeInUse:function(){
          this.showInUse = false
        },
        acceptEdit:function(){
          axios.post('/updatemeasureunit',
            {
              params : {
                id:this.measureunit.id, unit_description:this.description 
              }
            }).then(response => {
              this.edition = false
              this.$emit('accept-edit', response.data)
            }
          )
        },
        discardEdit:function(){
          this.edition = false
        },
    },
}
</script>

<style scope>

.edit_section_html {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  min-height: 100vh;
  padding-top: 40px;
  padding-bottom: 40px;
  border-bottom: 1px solid #000;
}

.section_wrap {
  border-bottom: 15px solid #000;
}

.measure_unit_section{
  padding-top: 20px;
  padding-bottom: 20px;
  width: 100%;
}

.data_entry {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -webkit-align-items: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  -ms-flex: 1;
  flex: 1;
}

.add_form_frame {
  width: 90%;
  height: 100%;
  margin-bottom: 0px;
}

.product_form{
  width: 100%;
}

.add_frame {
  display: block;
  height: 100%;
  width: 100%;
}

.field_wrap{
  width: 100%;
  font-family: inherit;
  padding-top: 10px;
}

.field_label{
  padding-bottom: 5px;
  padding-left: 5px;
}

.text_field{
  width: 100%;
  min-height: 40px;
  border-radius: 5px;
}

.box_shadow{
  box-shadow: 4px 4px 3px 0 #000;
}

.accept_button{
  background-color: #3898EC;
  width: 150px;
}

.discard_button{
  background-color: darkgreen;
  width: 150px;
}

.unit_data_edit{
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  width: 80%;
  height: 100%;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-justify-content: space-around;
  -ms-flex-pack: distribute;
  justify-content: space-around;
  line-height: 1.5em;  
}


.data_field {
  font-size: 14px !important;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  width: 100%;
  min-height:25px;
  max-height: 80px;
  padding-left: 20px;
  padding-right: 20px;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  -ms-flex-pack: center;
  justify-content: center;
  border-radius: 5px;
  max-height: 25px;
  white-space: nowrap;
  overflow: hidden;
}

.info_field{
    background-color: #8d8d8d;
}

.accept_cancel_unit_link_dialog{
  position: fixed;
  margin-top: 24%;
  color: white;
  height: 32%;
  margin-left: 32%;
  width: 36%;
  background-color: #da1717;
  border-radius: 5px;
  overflow: hidden;
  text-align: end;
}

.shadowRight{
  box-shadow: 4px 4px 3px #000;
}

.accept_cancel_dialog_close_bar{
  height: 40px;
  width: 100%;
  background-color: #24731d;
  color: white;
  opacity: 100%;
  display: flex;
  position: relative;
}

.accept_cancel_units_close_bar_text{
  position: relative;
  left: 10px;
  width: 95%;
  text-align: left;
  padding-right: 20px;
  line-height: 1.5em;
}

.accept_cancel_unit_link_dialog_close_icon{
  top: 10px;
  right: 10px;
  position: absolute;
  right: 3%;
}

.accept_cancel_unit_link_dialog_body{
  display: block;
  text-align: -webkit-center;
  height: 90%;
  padding-bottom: 10%;
  padding-right: 5%;
  padding-left: 5%;
}

.unit_link_product_description_frame{
  padding-top: 5%;
  padding-bottom: 5%;
  color: white;
}

.remove_anyway{
  position: absolute;
  bottom: 10px;
  left: 0;
  width: 100%;
  text-decoration: underline;
  text-align: center;
}

.unit_link_frame{
  position: absolute;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  z-index: 12;
}

.unit_link_product_description{
  width: 100%;
  border-radius: 5px;
  padding-left: 10px;
  padding-right: 10px;
  padding-top: 5px;
  padding-bottom: 5px;
}

.unit_link_accept_button{
  margin-top: 8%;
}

@media screen and (max-width: 991px) {
  .unit_data_edit {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-justify-content: space-around;
    -ms-flex-pack: distribute;
    justify-content: space-around;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-align-self: center;
    -ms-flex-item-align: center;
    align-self: center;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    -ms-flex: 1;
    flex: 1;
  }

  .data_field {
    padding-left: 20px;
    border-radius: 5px;
  }

}

@media screen and (max-width: 767px) {

  .edit_section_html {
    min-height: 105vh;
    padding-bottom: 40px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
  }

  .section_wrap {
    display: block;
  }

  .add_form_frame {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    -ms-flex: 1;
    flex: 1;
    padding-top: 50px;
  }

  .add_frame{
    display: block;
    min-height: 30vh;
  }

  .field_wrap{
    width: 100%;
  }

  .accept_cancel_unit_link_dialog{
    font-size: 11px;
  }

}

@media screen and (max-width: 499px) {
  .data_field {
    font-size: 12px;
  }
  
  .accept_cancel_unit_link_dialog{
    font-size: 10px;
  }

}
</style>