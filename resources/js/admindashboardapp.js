import Vue from 'vue'

//Main pages
import pageheader from './components/pageheader.vue'
import adminpagecontent from './components/adminpage/adminpagecontent.vue'

var admindashboardapp = new Vue({
    el: '#app',
    components: { pageheader, adminpagecontent },
})
