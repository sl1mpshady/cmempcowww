$(function() {
    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010-01-01',
            iphone: 27,
            ipad: 22,
            itouch: 41
        }, {
            period: '2010-02-01',
            iphone: 27,
            ipad: 22,
            itouch: 41
        }, {
            period: '2010-03-01',
            iphone: 12,
            ipad: 69,
            itouch: 01
        }, {
            period: '2010 Q4',
            iphone: 67,
            ipad: 97,
            itouch: 89
        }, {
            period: '2011 Q1',
            iphone: 10,
            ipad: 14,
            itouch: 93
        }, {
            period: '2011 Q2',
            iphone: 70,
            ipad: 93,
            itouch: 81
        }, {
            period: '2011 Q3',
            iphone: 20,
            ipad: 95,
            itouch: 88
        }, {
            period: '2011 Q4',
            iphone: 73,
            ipad: 67,
            itouch: 75
        }, {
            period: '2012 Q1',
            iphone: 87,
            ipad: 60,
            itouch: 28
        }, {
            period: '2012 Q2',
            iphone: 32,
            ipad: 13,
            itouch: 91
        }, {
            period: '2015-01-01',
            iphone: 32,
            ipad: 13,
            itouch: 91
        }],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['iPhone', 'iPad', 'iPod Touch'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });
});
