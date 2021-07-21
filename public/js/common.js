
  $(document).ready(function(){
    $('#app-mobile-menu-options').hover(
      function(){}, 
      function(){
        document.getElementById('app-mobile-menu-options').style.display = 'none'
      }
    )
  })

  function appMobileMenu(){
    var appMobileMenuOptions = document.getElementById('app-mobile-menu-options')
    if(appMobileMenuOptions.style.display == 'none'){
      appMobileMenuOptions.style.display = 'block'
    }
    else{
      appMobileMenuOptions.style.display = 'none'
    }
  }
