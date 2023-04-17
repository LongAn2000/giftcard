define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote'
    ],
    function ($,Component,quote) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mageplaza_GiftCard/checkout/summary/customdiscount'
            },
            quoteIsVirtual: quote.isVirtual(),
            totals: quote.getTotals(),
            isDisplayedCustomdiscount : function(){
                var segments = this.totals()['total_segments'];
                for( let i = 0; i< segments.length; i++){
                    var code = segments[i];
                    if(code['code'] === 'giftcard_code' && code['value'] !== 0 && code['value'] !== null){
                        debugger;
                        return true;
                    }
                }
                return false;
            },
            getCustomDiscount : function(){
                var segments = this.totals()['total_segments'];
                for( let i = 0; i< segments.length; i++){
                    var code = segments[i];
                    if(code['code'] === 'giftcard_code'){
                        var balance = code['value'];
                    }
                }
                return this.getFormattedPrice(balance);
            }
        });
    }
);
