import Vue from 'vue'

//Main pages
import pageheader from './components/pageheader.vue'
import addicon from './components/addicon.vue'
import measureunitadd from './components/measureunitadd.vue'
import reportresult from './components/reportresult.vue'
import measureunitedit from './components/measureunitedit.vue'

const axios = require('axios')

var measureunitsapp = new Vue({
    el: '#measureunits-app',
    components: { pageheader, addicon, measureunitadd, reportresult, measureunitedit },
    methods:{
        addIconClick:function(){
            this.new = true
        },
        searchClick:function(searchText){
            axios.get('/searchbytext',{
                params:{
                    search_text:searchText
                }
            }).then(response => {
                this.measureunits = response.data.measureunits
            })
        },
        discardButtonClick:function(){
            this.new = false
            this.discardUnit = false
        },
        createButtonClick:function(options){
            this.new = false
            this.reportAction = true
            this.resultMessage = options.message
            this.isErrorMessage = options.isError
        },
        messageOk:function(){
            this.reportAction = false
        },
        inUse:function(description){
            console.log('in-use')
            console.log(description)
            this.description = description
            this.isInUse = true
        },
        deleted:function(responseData){
            this.measureunits.splice(responseData.index, 1)
            this.resultMessage = responseData.message
            this.isErrorMessage = responseData.isError
            this.reportAction = true
        },
        acceptEdit:function(responseData){
            this.resultMessage = responseData.message
            this.isErrorMessage = responseData.isError
            this.reportAction = true
        },
    },
    data:function(){
        return {
            new : false,
            measureunits:[],
            reportAction:false,
            resultMessage:'',
            isErrorMessage:false,
            timeOut:3000,
            isInUse:false,
            description:'',
            verifyBeforeDelete:true,
        }
    }
})
