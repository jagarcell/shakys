import Vue from 'vue'

import pageheader from './components/pageheader.vue'
import productsadd from './components/products/productsadd.vue'
import productsedit from './components/products/productsedit'
import reportresult from './components/reportresult.vue'

var productsApp = new Vue({
    el:'#products-app',
    components:{ pageheader, productsadd, productsedit, reportresult},
    methods : {
        addClick:function(){
            this.add = true
        },
        created:function(data){
            this.resultMessage = data.message
            this.isErrorMessage = data.isError
            this.report = true
            if(!this.isErrorMessage){
                this.add = false
            }
        },
        discarded:function(){
            this.add = false
        },
        reportOk:function(){
            this.report = false
        },
        reportTimeOut:function(isError){
            if(!isError){
                this.report = false
                this.add = false
            }
        },
    },
    data:function(){
        return {
            resultMessage : '',
            isErrorMessage : false,
            report : false,
            add : false,
        }
    }
})