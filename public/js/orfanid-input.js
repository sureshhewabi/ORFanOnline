$(document).ready(function() {

  $("#advanceparameterslink").click(function(){
    $("#advanceparameterssection").toggle(1000);
  });

      $('#genesequence').trigger('autoresize');
      $('.modal').modal();
      $('select').material_select();
      var organisms = [];

      $.ajax({
        url: 'data/TaxData.json',
        async: false,
        dataType: 'json',
        success: function (response) {
          $.each(response, function(key, val) {
                  var options = "";
                  options = val.SpeciesName + '(' + val.NCBITaxID+')';
                  organisms[options] = "null";
               });
           }
         });
      console.log(organisms);
      $('input.autocomplete').autocomplete({
        data :organisms,
        limit : 10 // The max amount of results that can be shown at once. Default: Infinity.
      });

      var taxonomy = [];

      $( "#organismName" ).change(function() {
        $.ajax({
          url: 'data/TaxData.json',
          async: false,
          dataType: 'json',
          success: function (response) {
            var selectedOrganism = $('#organismName').val();
            var regularExpr = /\((.*)\)/;

            var selectedOrganismTaxID = selectedOrganism.match(regularExpr)[1];
            $.each(response, function(key, val) {
              var options = "";
              if(val.NCBITaxID == selectedOrganismTaxID){
                $('select').empty().html(' ');
                $.each(val.Taxonomy, function(key, val) {
                  var value = val.substr(9,val.length);
                  $('select').append($("<option></option>").attr("value",value).text(value));
                });
                // re-initialize (update)
                $('select').material_select();
              }
            });
          },
          error:function(error){
            alert(error);
          }
        });
      });

      $('#submit').click(function(){
        console.log('submit clicked');
        $('#modal1').modal('open');
      });

      $('#load-example-data').click(function(){
        console.log('#example link clicked');
        $('#genesequence').load('data/Ecoli_511145.fasta');
        $('#genesequence').addClass('active');
        $('#organismName').val('Escherichia coli str. K-12 substr. MG1655 (511145)');
        $.ajax({
          url: 'data/TaxData.json',
          async: false,
          dataType: 'json',
          success: function (response) {
            var selectedOrganism = $('#organismName').val();
            var regularExpr = /\((.*)\)/;

            var selectedOrganismTaxID = selectedOrganism.match(regularExpr)[1];
            $.each(response, function(key, val) {
              var options = "";
              if(val.NCBITaxID == selectedOrganismTaxID){
                $('select').empty().html(' ');
                $.each(val.Taxonomy, function(key, val) {
                  var value = val.substr(9,val.length);
                  $('select').append($("<option></option>").attr("value",value).text(value));
                });
                // re-initialize (update)
                $('select').material_select();
              }
              });
            },
               error:function(error){
               alert(error);
               }
            });
        $(function() {
          Materialize.updateTextFields();
          });
        return true;
          });
    });
