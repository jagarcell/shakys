<template>
    <div class="page-header">
        <a href="/" class="logo-frame">
            <img src="/images/logo.png" loading="lazy" sizes="(max-width: 767px) 100vw, 53vw" srcset="/images/logo.png 500w, /images/logo.png 512w" alt="" class="logo">
        </a>
        <div class="title-frame">
            <div class="text-block text-shadow">{{this.appname}}</div>
        </div>
    </div>
</template>

<script>
const default_layout = "default";

export default {
    props:['appname'],
}
</script>

<style scoped>
/****************************/
/*       Search Bar         */
/****************************/

.page-header{
    width: 100%;
    height: 10vh;
    position: relative;
}

.page-content{
    position: relative;
    width: 100%;
}

.search-frame{
    position: relative;
    width: 100%;
    height: 40px;
}

.search-input{
    position: absolute;
    width: 60%;
    top: 0%;
    right: 25%;
}

.search-bar {
    margin-bottom: 0px;
    border-style: solid;
    border-width: 1px;
    border-color: #8d8d8d;
    border-radius: 20px !important;
    width: 100%;
}
.w-input {
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

.search-product-icon{
    position: absolute;
    right: 1%;
    top:15%;
    height: 25px;
    width: 25px;
}

.search-button{
    position: absolute;
    right: 0%;
    top: 0%;
    width: 100% !important;
    background: transparent;
}

/****************************/
/*         Header           */
/****************************/

.logo-frame{
    z-index: 1;
}

.logo {
    height: 30vh;
    filter:drop-shadow(4px 4px 2px #000)
}

.title-frame {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    width: 70%;
}

.text-block {
    font-size: 26px;
    text-align: center;
}

.text-shadow{
    text-shadow: 3px 2px 2px black
}
  

@media screen and (max-width: 991px) {
}
@media screen and (max-width: 767px) {

/****************************/
/*         Header           */
/****************************/
    
  .title-frame {
    width: auto;
  }

}
@media screen and (max-width: 575px) {
}
@media screen and (max-width: 499px) {

/****************************/
/*         Header           */
/****************************/
  
  .text-block {
     font-size: 18px;
     text-shadow: 3px 2px 4px black;
  }
    
}
  
</style>