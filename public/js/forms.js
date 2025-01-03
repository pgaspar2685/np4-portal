App.ProductForm = function(form){
		
	$(".field-resume").val( $("#field-resume-editor .ql-editor").html() );
	$(".field-description").val( $("#field-description-editor .ql-editor").html() );
	
	return true;
}

App.PageForm = function(form){
	$("#html_source").val( $("#editor-html .ql-editor").html() );
}

App.FillAddress = function(elem){
	
	var morada = $(elem).val();

	if(morada > 0) {
		$(elem).fadeTo('fast', 0.6);
		$("#morada").fadeTo('fast', 0.6);
		$("#codPostal").fadeTo('fast', 0.6);
		$("#localidade").fadeTo('fast', 0.6);
		
		$.ajax({
			url: '/users/addressSearch/' + morada,
			dataType: 'json',
			type: 'GET',
			success: function (response) {
				
				if (response.success) {
	
					$("#morada").val(response.record.morada);
					$("#codPostal").val(response.record.codPostal);
					$("#localidade").val(response.record.localidade);
					
				} else {
					alert("Ocorreu um erro ao obter a morada");
				}
				
			},
			complete: function () {
				$(elem).fadeTo('fast', 1);
				$("#morada").fadeTo('fast', 1);
				$("#codPostal").fadeTo('fast', 1);
				$("#localidade").fadeTo('fast', 1);
			},
			error: function (response) {
				alert("Ocorreu um erro");
			}
		});
	}
}

App.changePosition = function(input, url){
	var inp = $(input);
//	var elem = $(input).next(".modif");
	var url = url + "/" + inp.val();
	App.loadDiv(false, inp, url, {});
};


App.showModalForm = function(url, modal){

	console.log("showModalForm: " + url);

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'get',
		success: function (response) {  
			$(modal).find(".modal-body").html(response.html);
			$(modal).modal("show");
		}
	});
	return false;
}

App.relatorioChangeFilter = function(){
	var year = $("#select_year").val();
	var month = $("#select_month").val();

	window.location.href = "/relatorios/index/month/&y="+ year + "&m=" + month;
}

App.relatorioVendasChangeFilter = function(){
	var year = $("#select_year").val();
	var month = $("#select_month").val();

	window.location.href = "/relatorios-sales/index/month/&y="+ year + "&m=" + month;
}

App.barberChange = function(ele){
	var id_barber = $(ele).val();
	window.location.href = "/marcacoes/index/" + id_barber;
}

App.addProduct = function(){

	var table = $("#salesProducts");
	var lines = parseInt($("#salesProducts").attr("data-count"))+ 1;

	$.ajax({
		url: '/sales/addProduct/' + lines,
		dataType: 'json',
		type: 'GET',
		success: function (response) {
			
			if (response.success) {
				$("#salesProducts").attr("data-count", lines);
				table.find("tbody").append(response.html);
			} else {
				alert("Ocorreu um erro ao obter a morada");
			}
			
		}
	});

	return false;
}

App.removeLine = function(ele){

	console.log("ola");
	console.log($(ele));

	$(ele).parents("tr").remove();

	return false;
}

App.updateRow = function(ele){

	var updateLine = $(ele);
	var id_prod = ele.value;

	$.ajax({
		url: '/products/getPrice/' + id_prod,
		dataType: 'json',
		type: 'GET',
		success: function (response) {
			
			if (response.success) {
				updateLine.parents("tr").find(".qtd").val(1);
				updateLine.parents("tr").find(".vl_pvp").val(response.vl_pvp);
				updateLine.parents("tr").find(".vl_pvp").attr("data-min", response.vl_price_vat);
				updateLine.parents("tr").find(".vl_pvp_total").text(response.vl_pvp);
			} else {
				alert("Ocorreu um erro ao obter a morada");
			}
			
		}
	});

	return false;

}

App.updateTotal = function(ele){

	console.log("updateTotal");

	var updateLine = $(ele);
	var qtd = parseInt(updateLine.parents("tr").find(".qtd").val());
	console.log("q: " + qtd);

	var vl_pvp = parseFloat(updateLine.parents("tr").find(".vl_pvp").val());
	console.log("v: " + vl_pvp);

	var vl_pvp_total = vl_pvp * qtd;
	updateLine.parents("tr").find(".vl_pvp_total").text(vl_pvp_total);
	console.log(vl_pvp_total);
}