var obj = {
	init: function(){
        $('.nav-header .dropdown').hover(function() {
            $(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
        }, function() {
            $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
        });
        /*Filtro de cliente*/
		if($("#filterS").length>0){
			obj.filterSearch();
		}
        /*Filtro de Usario GV*/
        if($("#filterS").length>0){
            obj.gvFilterSearch();
            obj.grFilterSearch();
        }
        if($("#tableCN").length>0){
            obj.nTable();
        }
        /**/
	},
	filterSearch: function(){
		$('#fCodigo').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código',
            buttonWidth: '108px'
        });
        $('#cCNO').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código CNO',
            buttonWidth: '126px'
        });
        $('#cConsultora').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código Consultora',
            buttonWidth: '178px'
        });
	},
    gvFilterSearch: function(){
        $('#gvfCodigo').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código',
            buttonWidth: '118px'
        });
        $('#gvSector').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Sector',
            buttonWidth: '118px'
        });
        $('#gvcCNO').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código CNO',
            buttonWidth: '118px'
        });
        $('#gvSituacion').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Situación',
            buttonWidth: '118px'
        });
    },
    grFilterSearch: function(){
        $('#eInicial').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Estado',
            buttonWidth: '128px'
        });
        $('#grcCNO').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código CNO',
            buttonWidth: '118px'
        });
        $('#grCiclo').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Ciclo',
            buttonWidth: '118px'
        });
    },
    nTable: function(){
        $('.collapse').on('show.bs.collapse', function () {
            $('.collapse.in').collapse('hide');
        });
        $('.popup-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(item) {
                    return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
                }
            }
        });
    },
    nDonut: function(){
        console.log("hik");
        /*new Chartist.Pie('.ct-chart', {
            labels: ['Piece A', 'Piece B', 'Piece C', 'Piece D'],
            series: [75, 5, 8, 15]
        }, {
            height: '200px',
            donut: true,
            donutWidth: 30,
            startAngle: 270,
            total: 100,
            showLabel: true,
            labelInterpolationFnc: function(value) {
                return value + '%';
            },
            plugins: [
                Chartist.plugins.legend()
            ]
        });*/
        
    }
};
(function() {
  	obj.init();
})();