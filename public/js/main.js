$(document).ready(function () {
  window._token = $('meta[name="csrf-token"]').attr('content')

//   moment.updateLocale('en', {
//     week: {dow: 1} // Monday is the first day of the week
//   })

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

//   $('.date').datetimepicker({
//     format: 'YYYY-MM-DD',
//     locale: 'en',
//     icons: {
//       up: 'fas fa-chevron-up',
//       down: 'fas fa-chevron-down',
//       previous: 'fas fa-chevron-left',
//       next: 'fas fa-chevron-right'
//     }
//   })

//   $('.datetime').datetimepicker({
//     format: 'YYYY-MM-DD HH:mm:ss',
//     locale: 'en',
//     sideBySide: true,
//     icons: {
//       up: 'fas fa-chevron-up',
//       down: 'fas fa-chevron-down',
//       previous: 'fas fa-chevron-left',
//       next: 'fas fa-chevron-right'
//     }
//   })

//   $('.timepicker').datetimepicker({
//     format: 'HH:mm:ss',
//     icons: {
//       up: 'fas fa-chevron-up',
//       down: 'fas fa-chevron-down',
//       previous: 'fas fa-chevron-left',
//       next: 'fas fa-chevron-right'
//     }
//   })

  $('.select-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', 'selected')
    $select2.trigger('change')
  })
  $('.deselect-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', '')
    $select2.trigger('change')
  })

  $('.select2').select2()

  $('.treeview').each(function () {
    var shouldExpand = false
    $(this).find('li').each(function () {
      if ($(this).hasClass('active')) {
        shouldExpand = true
      }
    })
    if (shouldExpand) {
      $(this).addClass('active')
    }
  })

$('button.sidebar-toggler').click(function () {
    setTimeout(function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    }, 275);
  })


    $('.quantity-right-plus').on('click', function() {
        var minusBtn = $(this).parent().siblings(".input-group-prepend").find('.quantity-left-minus');
        var $priceInput = $(this).parent().siblings('.item-price');
        var $priceLbl = $(this).closest('form').find('.sub_total');
        var $qty = $(this).parent().siblings(".qty-input");
        var $stock = $(this).parent().siblings(".stock-input");
        var currentVal = parseInt($qty.val());
        var currentStock = parseInt($stock.val())
        var initPrice = parseInt($priceInput.val())
        if (!isNaN(currentVal) && !isNaN(currentStock)) {
            if(currentStock == "-1"){
                let val = $qty.val(currentVal + 1);
                $priceLbl.val(val.val() * initPrice)
            } else {
                if(currentVal < currentStock){
                    let val = $qty.val(currentVal + 1);
                    $priceLbl.val(val.val() * initPrice)
                    minusBtn.attr('disabled', false)
                }else{
                    $(this).parent().siblings(".stock-alert").show().delay(3000).fadeOut();
                    $(this).attr('disabled', true)
                    minusBtn.attr('disabled', false)
                }
            }

        }
    })
    $('.quantity-left-minus').on('click', function() {
        var plusBtn = $(this).parent().siblings(".input-group-prepend").find('.quantity-right-plus');
        var $priceInput = $(this).parent().siblings('.item-price');
        var $priceLbl = $(this).closest('form').find('.sub_total');
        var $qty = $(this).parent().siblings(".qty-input");
        var _val = $($qty).val();
        var currentVal = parseInt($qty.val());
        var initPrice = parseInt($priceInput.val())
        if (!isNaN(currentVal) && currentVal > 1) {
            let val = $qty.val(currentVal - 1);
            $priceLbl.val(val.val() * initPrice)
            plusBtn.attr('disabled', false)
        }else{
            $(this).attr('disabled', true)
        }
    })

})
