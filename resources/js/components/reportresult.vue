<template>
    <div v-if="report" class="resultFrame">
        <div class="messageFrame" v-bind:class="{successMessage:!iserrormessage, errorMessage:iserrormessage}">
            <label class="resultMessage">{{resultmessage}}</label>
            <a v-on:click="$emit('ok-click')" class="okLink">OK</a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        report:{
            type:Boolean,
            default:false
        }, 
        resultmessage : {
            type:String,
            default:''
        }, 
        iserrormessage : {
            type:Boolean,
            default:false
        },
        messagetimeout:{
            type:Number,
            default:0
        }
    },
    updated:function(){
        if(this.report && this.messagetimeout > 0){
            setTimeout((function(){
                this.$emit('time-out', this.iserrormessage)
            }).bind(this), this.messagetimeout)
        }
    }
}
</script>

<style>
    .resultFrame{
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        z-index: 20;
    }

    .messageFrame{
        position: fixed;
        width: 50vw;
        height: 24vh;
        margin-left: 25vw;
        margin-top: 38vh;
        background-color: blue;
        color: white;
        text-align: center;
        border-radius: 10px;
        box-shadow: 5px 5px 5px black;
        z-index: 21;
    }

    .successMessage{
        background-color: blue;

    }

    .errorMessage{
        background-color: red;
    }

    .resultMessage{
        position: absolute;
        width: 100%;
        height: 50%;
        left: 0;
        top: 25%;
    }

    .okLink{
        position: absolute;
        width: 6%;
        bottom: 5%;
        left: 47%;
        text-decoration: underline;
    }
</style>