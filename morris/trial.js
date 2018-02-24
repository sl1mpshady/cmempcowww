$(function() {
    Morris.Area({
        element: 'morris-area-chart',
		data: [
		{period: '2015-8-01',so: 92547.00,po: 92547.00,ao: 92547.00},
		{period: '2015-9-01',so: 17943.00,po: 17943.00,ao: 92547.00},
		{period: '2015-10-01',so: 135.50,po: 135.50,ao: 92547.00},
		{period: '2015-11-01',so: 108180.40,po: 108180.40,ao: 92547.00},
		{period: '2015-12-01',so: 333.20,po: 333.20,ao: 92547.00},
		{period: '2016-1-01',so: 115.00,po: 115.00,ao: 92547.00}],
		xkey: 'period',
		ykeys:['so','po','ao'],
		labels:['Sales Order','Purchase Order','asas'],
		pointSize:2,
		hideHover: 'auto', 
		resize: true
	});
});