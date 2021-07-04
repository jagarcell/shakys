$(document).ready(function(){
    var qtySelectHtml = ""
    for(var i = 0; i < 201; i++){
        qtySelectHtml += "<option value='" + i + "'>" + i + "</option>"
    }

    var qtySelect = document.getElementById('qty')
    qtySelect.innerHTML = qtySelectHtml

//    $('.hide-this').hide()
    document.getElementById('navbarSupportedContent').style.zIndex = 1
    document.getElementById('navbarSupportedContent').style.position = 'sticky'
})

var timeoutInMiliseconds = 10000;
var timeoutId;
  
function startTimer() { 
    // window.setTimeout returns an Id that can be used to start and stop a timer
    setupTimers()
    timeoutId = window.setTimeout(doInactive, timeoutInMiliseconds)
}
  
function doInactive() {
    // does whatever you need it to actually do - probably signs them out or stops polling the server for info
    clearTimers()
    window.location = "/userdashboard"
}
 
function setupTimers () {
    document.addEventListener("mousemove", resetTimer, false)
    document.addEventListener("mousedown", resetTimer, false)
    document.addEventListener("keypress", resetTimer, false)
    document.addEventListener("touchmove", resetTimer, false)
}
 
function clearTimers () {
    document.removeEventListener("mousemove", resetTimer, false)
    document.removeEventListener("mousedown", resetTimer, false)
    document.removeEventListener("keypress", resetTimer, false)
    document.removeEventListener("touchmove", resetTimer, false)
}


function resetTimer() { 
    window.clearTimeout(timeoutId)
    startTimer();
}

function locationClick(locationId){
    alert(locationId)
}

function closeOrder(){
    document.getElementById('order_top_id').style.display = 'none'
}

function productClick(productId){
    $.get('/getproduct',
        {
            id:productId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var orderTopMost = document.getElementById('order_top_most')
                        var orderTopId = document.getElementById('order_top_id')
                        orderTopId.innerHTML = orderTopMost.innerHTML
                        $(orderTopId).find('#product_order_image')[0].src = product.image_path
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/internal-description/g, product.internal_description)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/product-id/g, product.id)
                        $(orderTopId).show()
                        $(orderTopId).find('#qty').focus()

                        var measureUnitSelect = $(orderTopId).find('#measure_unit')[0]
                        // Let's prepare the measure units select
                        $.each(product.measure_units, function(index, measureUnit){
                            var option = document.createElement("option")
                            option.value = measureUnit.id
                            option.text = measureUnit.unit_description
                            if(product.default_measure_unit_id == measureUnit.id){
                                option.selected = true
                            }
                            measureUnitSelect.options.add(option)
                        })                        
                        break;
                
                    default:
                        break;
                }
            }
        }
    )
}

function orderClick(productId){
    console.log(productId)
    var qtySelect = $('#order_top_id').find('#qty')[0]
    var qty = qtySelect.selectedIndex
    var measureUnitSelect = $('#order_top_id').find('#measure_unit')[0]
    var measureUnitId = measureUnitSelect.options[measureUnitSelect.selectedIndex].value

    if(qty > 0)
    {
        $.post('/markascounted',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:productId,
            qty_to_order:qty,
            measure_unit_id:measureUnitId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var markAsCounted = document.getElementById(product.id)
                        markAsCounted.outerHTML = ""
                        var products = $('.product')
                        $.each(products, function(index, product){
                            product.classList.remove("bbg")
                            product.classList.remove("rbg")
                            if(Math.floor(index / 2) * 2 == index){
                                product.classList.add('rbg')
                            }
                            else{
                                product.classList.add('bbg')
                            }
                        })
                        closeOrder()
                        break
                    case 'error':

                        break
                    case '419':
                        break

                    case 'notfound':

                        break

                    default:
                        break
                }
            }
        })
    }
    else{
        closeOrder()
    }
}

function searchClick() {
    var searchtext = $('#search_text').val()
    $.get('/searchfor',
        {
            searchtext:searchtext,
        }, function(data, status){
            if(status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var products = data.products
                        var productsListWrap = document.getElementById('products_list_wrap')
                        productsListWrap.innerHTML = ""
                        $.each(products, function(index, product){
                            productHtml = document.getElementById('product').innerHTML
                            productHtml = productHtml.replace(/product-id/g, product.id)
                            productHtml = productHtml.replace(/image-path/g, product.image_path)
                            productHtml = productHtml.replace(/internal-description/g, product.internal_description)
                            productsListWrap.innerHTML = productsListWrap.innerHTML + productHtml
                            var prod = document.getElementById(product.id)
                            if(Math.floor(index / 2) * 2 == index){
                                prod.classList.add('rbg')
                            }
                            else{
                                prod.classList.add('bbg')
                            }
                        })
                        productsListWrap.scrollIntoView(true)
                        startTimer()
                        break;
                    default:
                        break;
                }
            }
        }
    )
}
