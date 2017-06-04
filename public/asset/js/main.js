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
            console.log("Esto es una prueba");
            obj.gvFilterSearch();
            obj.grFilterSearch();
            obj.adFilterSearch();
        }
        if($("#tableCN").length>0){
            obj.nTable();
        }
        /**/
        obj.menuMovil();
        obj.filterActiveMenu();
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
        console.log("sdsd");
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
    adFilterSearch: function(){
        $('#adCiclo').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Ciclo',
            buttonWidth: '108px'
        });
        $('#adEIn').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Estado inicial',
            buttonWidth: '108px'
        });
        $('#adCodigo').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Ciclo', //modificacion
            buttonWidth: '118px'
        });
        $('#adCNO').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Código CNO',
            buttonWidth: '118px'
        });
        $('#adGerencia').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Gerencia',
            buttonWidth: '118px'
        });
        $('#adSector').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Sector',
            buttonWidth: '118px'
        });
        $('#adFecha').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Fecha',
            buttonWidth: '118px'
        });
        $('#adSituacion').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Fecha',
            buttonWidth: '118px'
        });
        $('#adEstInicial').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Estado inicial',
            buttonWidth: '125px'
        });
        $('#adEstFinal').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Estado final',
            buttonWidth: '125px'
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
    menuMovil: function(){
        if($('.drawer').length>0){
            $('.drawer').drawer();
        }
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
        
    },
    filterActiveMenu: function(){
        var eventActive = false;
        $(".btnFilter").click(function(){
            if(eventActive === true){
                $(this).removeClass("btnActive");
                $(".cntFilter").hide();
                eventActive = false;
            }else{
                $(this).addClass("btnActive");
                $(".cntFilter").show();
                eventActive = true;
            }
        });
    }
};
(function() {
    console.log("init");
  	obj.init();
})();