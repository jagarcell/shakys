<template>
    <ul class="navbar-nav" style="text-shadow: 2px 2px 4px black;position:absolute;top:0;right:5px;">
        <!-- Authentication Links -->
        <div v-if="user">
            <li class="nav-item dropdown">
                <div style="padding-bottom: 10px;" id="navbarDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret">{{user.name}}</span>
                </div>
            </li>
            <li class="nav-item dropdown" style="text-decoration:underline;">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/logout" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                        <input type="hidden" name="_token" :value="csrf">
                    </form>
                </div>
            </li>
        </div>
    </ul>
  
</template>

<script>
const default_layout = "default";

export default {
    props:['user'],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
}
</script>

<style>

</style>