<template>
    <div class="section_wrap">
        <addicon :show="!show" v-on:add-icon-click="$emit('add-icon-click')" v-on:search-click="searchClick"></addicon>
        <div v-if="show || edit" class="measure_unit_section">
            <div class="data_entry">
                <div class="add_form_frame w-form">
                    <form class="product_form add_unit_frame w-form">
                        <input v-model="unitDescription" type="text" class="code text_field box_shadow w-input" maxlength="255" placeholder="Unit Description" required="" autofocus>
                        <div class="add_buttons_frame">
                            <input type="button" value="Create Unit" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" v-on:click="createUnit">
                            <input type="button" value="Discard Unit" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" v-on:click="$emit('discard-button-click')">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const axios = require('axios')
import addicon from '../addicon.vue'

export default {
    components:{addicon},
    props:{
        show:{
            type:Boolean,
            default:false
        },
    },
    data:function(){
        return {
            unitDescription:'',
            edit:false,
        }
    },
    methods:{
        searchClick:function(searchText){
            this.$emit('search-click', searchText)
        },
        createUnit:function(){
            axios.post(
                '/createmeasureunit',
                {
                    unit_description:this.unitDescription
                }
                ).then(response => {
                   this.unitDescription = ''
                   this.$emit('create-button-click', response.data)
                }
            )
        }
    },
}
</script>

<style scoped>
    .section_wrap {
        position: sticky;
        top: 0;
        border-bottom: 15px solid #000;
        z-index: 1;
        background-color: white;
    }

    .data_entry {
        font-size: 14px;
        display: -webkit-flex;
        display: -ms-flexbox;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
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

    .add_unit_frame {
        display: -ms-flexbox;
        display: flex;
        height: 100%;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: distribute;
        justify-content: space-around;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .w-input, .w-select {
        display: block;
        height: 38px;
        padding: 8px 12px;
        margin-bottom: 10px;
        font-size: 14px;
        line-height: 1.428571429;
        color: #333333;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        font-family: inherit;
    }

    .text_field {
        width: 100%;
        min-height: 40px;
        border-radius: 5px;
    }
    .box_shadow {
        box-shadow: 4px 4px 3px 0 #000;
    }

    .add_buttons_frame {
        width: 100%;
        display: flex;
        justify-content: space-around;
        font-size: 12px;
    }

    .action_result_message {
        width: 100%;
        font-size: x-large;
        line-height: 1.5em;
        text-align: center;
        padding: 30px;
        border-bottom: 15px solid black;
    }

    @media screen and (max-width: 991px) {
    }

    @media screen and (max-width: 767px) {
        
        .section_wrap {
            display: block;
        }

        .add_form_frame {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            padding-top: 50px;
        }

        .product_form {
            width: 100%;
        }

        .add_unit_frame {
            display: -ms-flexbox;
            display: flex;
            height: 100%;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex-pack: distribute;
            justify-content: space-around;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .add_buttons_frame {
            padding-top: 40px;
        }
    }

    @media screen and (max-width: 575px) {
    }

    @media screen and (max-width: 499px) {
        .add_buttons_frame {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            height: 20vh;
        }
    }
</style>