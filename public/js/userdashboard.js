$(document).ready(function(){
    $('.page-header').hide()
    $('.page_title_frame').hide()
    document.getElementById('navbarSupportedContent').style.zIndex = 1
    document.getElementById('navbarSupportedContent').style.position = 'sticky'
})

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

function searchClick() {
    alert('search')
}
