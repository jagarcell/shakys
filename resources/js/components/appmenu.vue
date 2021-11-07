<template>
    <div>
        <div class="app-menu-wrap hide-this">
            <div class="app-menu">
                <div class="app-menu-option">
                    <a href="/">Home</a>
                </div>
                <div class="app-menu-option">
                    <a href="/showpendingorderspanel">Orders</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/listproducts">Products</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/measureunits">Measure Units</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/users">Users</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliers">Suppliers</a>
                </div>    
                <!--div class="app-menu-option">
                    <a href="/productslocations">In-Store Product Locations</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliersproductlocation">Product Locations At Suppliers</a>
                </div -->    
            </div>
        </div>
        <a class="app-mobile-menu" v-on:click="appMobileMenu">
            <img src="/images/mobile.png">
        </a>
        <div class="app-mobile-menu-options-wrap" @mouseleave="show=false">
            <div id="app-mobile-menu-options" class="app-mobile-menu-options shadowRight" v-show="show">
                <div class="app-menu-option">
                    <a href="/">Home</a>
                </div>
                <div class="app-menu-option">
                    <a href="/showpendingorderspanel">Orders</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/listproducts">Products</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/measureunits">Measure Units</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/users">Users</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliers">Suppliers</a>
                </div>    
                <!--div class="app-menu-option">
                    <a href="/productslocations">In-Store Product Locations</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliersproductlocation">Product Locations At Suppliers</a>
                </div -->    
            </div>
        </div>
    </div>
</template>

<script>
const default_layout = "default";

export default {
    methods:{
        appMobileMenu:function(){
            this.show=true
        }
    },
    data:function(){
        return {show:false}
    },

}
</script>
