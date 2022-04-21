Vue.component('page-header', {
    props: ['title'],
    template: `<div class="page-header">
        <a href="/" class="logo-frame">
            <img src="/images/logo.png" loading="lazy" sizes="(max-width: 767px) 100vw, 53vw" srcset="/images/logo.png 500w, /images/logo.png 512w" alt="" class="logo">
        </a>
        <div class="title-frame">
            <div class="text-block text-shadow">{{title}}</div>
        </div>
    </div>`,

})

Vue.component('search-bar', {
    data: function() {
        return {
            searchText: '',
        }
    },
    template: `<div class="search-frame">
        <div class="search-input"> 
          <input v-model="searchText" type="text" class="search-bar w-input" maxlength="256"placeholder="Enter your search here (Press Enter For All)">
          <div class="search-product-icon">
            <img src="/images/MagnifierBk.png">
            <input type="button" v-on:click="$emit('search', searchText)" class="search-button">
          </div>
        </div>  
      </div>`,
})

var app =
    new Vue({
        el: '#app',
        data: {
            users: '',
        },
        methods: {
            onSearch: function(searchText) {
                $.get('getusers', { search_text: searchText },
                    function(data, status) {
                        if (status == 'success') {
                            console.log(data.users)
                            app.users = data.users
                        }
                    }
                )
            }
        }
    })