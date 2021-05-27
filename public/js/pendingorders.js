$(document).ready(function(){
    var optionsHTML = ""
    for(var i = 1; i < 201; i++){
        optionsHTML += "<option value='" + i + "'>" + i + "</option>"
    }
    var orderQtys = document.getElementsByClassName('order_qty_tag')
    $.each(orderQtys, function(index, orderQty){
        orderQty.innerHTML += optionsHTML
    })
})

function tabClick(element){
    console.log(element)
    var tabLinks = document.getElementsByClassName('w-tab-link')
    var tabPanes = document.getElementsByClassName('w-tab-pane')

    $.each(tabLinks, function(index, tabLink){
        tabLink.classList.remove('w--current')
        if(element.getAttribute('data-w-tab') == tabLink.getAttribute('data-w-tab')){
            tabLink.classList.add('w--current')
        }
    })

    $.each(tabPanes, function(index, tabPane){
        tabPane.classList.remove('w--tab-active')
        if(element.getAttribute('data-w-tab') == tabPane.getAttribute('data-w-tab')){
            tabPane.classList.add('w--tab-active')
        }
    })

    if(element.getAttribute('data-w-tab') == "Tab 2"){
        $('#add_to_order_button').show()
    }
    else{
        $('#add_to_order_button').hide()
    }
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
                        var orderTopMostHTML = document.getElementById('order_top_most').innerHTML
                        var orderTopId = document.getElementById('order_top_id')

                        orderTopId.innerHTML = orderTopMostHTML
                        $(orderTopId).find('#product_order_image')[0].src = product.image_path
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/internal-description/g, product.internal_description)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/product-id/g, product.id)

                        $(orderTopId).show()
                        $(orderTopId).find('#qty').focus()
                        break;
                
                    default:
                        break;
                }
            }
        }
    )
}

function orderClick(productId){
    var qty = $('#order_top_id').find('#qty').val()
    if(qty > 0)
    {
        $.post('/markascounted',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:productId,
            qty_to_order:qty,
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
